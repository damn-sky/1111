<?php
/**
 * Plugin Name: ispmanager auto login
 * Description: Generating login links via WP-CLI with after login redirect support
 * Version: 1.2.0
 * Author: ispmanager team
 */

if (!defined('ABSPATH')) {
    exit;
}

class AutoLogin {

    private $token_meta_key = 'ispmanager_auto_login_token';
    private $token_expiry_meta_key = 'ispmanager_auto_login_token_expiry';

    public function __construct() {
        add_action('init', array($this, 'handle_magic_login'));

        if (defined('WP_CLI') && WP_CLI) {
            // Register individual commands
            WP_CLI::add_command('auto-login generate', array($this, 'generate_login_url'), array(
                'synopsis' => array(
                    array(
                        'type'     => 'positional',
                        'name'     => 'user',
                        'optional' => false,
                        'description' => 'Username, email or user ID',
                    ),
                    array(
                        'type'     => 'assoc',
                        'name'     => 'redirect_to',
                        'optional' => true,
                        'description' => 'URL to redirect to after login',
                        'default' => '',
                    ),
                    array(
                        'type'     => 'assoc',
                        'name'     => 'expires',
                        'optional' => true,
                        'description' => 'Link lifetime in seconds',
                        'default' => 3600,
                    ),
                ),
                'when' => 'after_wp_load',
            ));

            WP_CLI::add_command('auto-login cleanup', array($this, 'cleanup_expired_tokens'));
        }
    }

    /**
    * Generate login URL - public method for WP-CLI
    */
    public function generate_login_url($args, $assoc_args) {
        // The positional argument user is the first element of the args array
        $user_login = isset($args[0]) ? $args[0] : '';
        $redirect_to = isset($assoc_args['redirect_to']) ? $assoc_args['redirect_to'] : home_url();
        $expires = isset($assoc_args['expires']) ? intval($assoc_args['expires']) : 3600;

        WP_CLI::debug("Params: user='{$user_login}', redirect_to='{$redirect_to}', expires={$expires}");

        if (empty($user_login)) {
            WP_CLI::error('No user specified. Use: wp auto-login generate <username|email|ID> [--redirect_to=<url>] [--expires=<seconds>]');
            return;
        }

        // Find the user
        $user = $this->get_user($user_login);
        if (!$user) {
            WP_CLI::error("User not found: {$user_login}");
            return;
        }

        // Generate a token
        $token = bin2hex(random_bytes(32));
        $expiry_time = time() + $expires;

        // Save the token and expiration time
        update_user_meta($user->ID, $this->token_meta_key, $token);
        update_user_meta($user->ID, $this->token_expiry_meta_key, $expiry_time);

        // Form the URL
        $login_url = add_query_arg(array(
            'auto-login' => $token,
            'user_id' => $user->ID,
            'redirect_to' => urlencode($redirect_to)
        ), home_url('/'));

        WP_CLI::line($login_url);

        return $login_url;
    }

    /**
    * Clearing expired tokens - public method for WP-CLI
    */
    public function cleanup_expired_tokens($args = array(), $assoc_args = array()) {
        global $wpdb;

        $current_time = time();
        $expired_users = $wpdb->get_col($wpdb->prepare("
            SELECT user_id
            FROM {$wpdb->usermeta}
            WHERE meta_key = %s
            AND meta_value < %d
        ", $this->token_expiry_meta_key, $current_time));

        if (empty($expired_users)) {
            WP_CLI::success('Expired tokens not found');
            return;
        }

        $count = 0;
        foreach ($expired_users as $user_id) {
            delete_user_meta($user_id, $this->token_meta_key);
            delete_user_meta($user_id, $this->token_expiry_meta_key);
            $count++;
        }

        WP_CLI::success(sprintf('%d expired tokens removed', $count));
    }

    /**
    * Processing magic login
    */
    public function handle_magic_login() {
        if (!isset($_GET['auto-login']) || !isset($_GET['user_id'])) {
            return;
        }

        $token = sanitize_text_field($_GET['auto-login']);
        $user_id = intval($_GET['user_id']);
        $redirect_to = isset($_GET['redirect_to']) ? urldecode($_GET['redirect_to']) : home_url();

        // Validate redirect_to - allow only internal URLs
        $redirect_to = $this->validate_redirect_url($redirect_to);

        // Check the user
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            wp_die('Invalid login link');
        }

        // Check the token and its expiration date
        $stored_token = get_user_meta($user_id, $this->token_meta_key, true);
        $token_expiry = get_user_meta($user_id, $this->token_expiry_meta_key, true);

        if (empty($stored_token) || empty($token_expiry)) {
            wp_die('The login link is invalid.');
        }

        if (!hash_equals($stored_token, $token)) {
            wp_die('Invalid token');
        }

        if (time() > $token_expiry) {
            // Delete the expired token
            delete_user_meta($user_id, $this->token_meta_key);
            delete_user_meta($user_id, $this->token_expiry_meta_key);
            wp_die('The login link has expired.');
        }

        // Login
        wp_clear_auth_cookie();
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);

        // Delete the used token
        delete_user_meta($user_id, $this->token_meta_key);
        delete_user_meta($user_id, $this->token_expiry_meta_key);

        // Redirect
        wp_redirect($redirect_to);
        exit;
    }

    /**
    * Validate URL for redirect
    */
    private function validate_redirect_url($url) {
        $home_url = home_url();

        // If the URL is relative, make it absolute
        if (strpos($url, '/') === 0) {
            $url = $home_url . $url;
        }

        // Check that the URL belongs to this site
        if (strpos($url, $home_url) !== 0) {
            $url = $home_url;
        }

        return esc_url_raw($url);
    }

    /**
    * Search for a user by login, email, or ID
    */
    private function get_user($user_login) {
        if (is_numeric($user_login)) {
            return get_user_by('ID', $user_login);
        }

        if (is_email($user_login)) {
            return get_user_by('email', $user_login);
        }

        return get_user_by('login', $user_login);
    }
}

new AutoLogin();
