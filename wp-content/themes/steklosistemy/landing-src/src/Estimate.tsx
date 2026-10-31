import {useState, type FormEvent} from 'react';
import {getContent} from './content';
import './estimate.css';

function CutSurface({button = false}: {button?: boolean}) {
  return (
    <span className={`estimate-surface ${button ? 'estimate-surface--button' : ''}`} aria-hidden="true">
      {['tl', 'tr', 'bl', 'br'].map((corner) => (
        <span className={`estimate-corner estimate-corner--${corner}`} key={corner} />
      ))}
    </span>
  );
}

function TextField({phone = false}: {phone?: boolean}) {
  const [value, setValue] = useState('');
  const placeholder = phone ? '+7 000 000 00 00' : 'Ваше имя';
  return (
    <label className="estimate-field">
      <CutSurface />
      <input
        name={phone ? 'phone' : 'name'}
        type={phone ? 'tel' : 'text'}
        autoComplete={phone ? 'tel' : 'name'}
        aria-label={phone ? 'Телефон' : placeholder}
        required
        pattern={phone ? '[+0-9() \\-]{10,20}' : '.*\\S.*'}
        value={value}
        onChange={(event) => setValue(event.target.value)}
        maxLength={phone ? 20 : 100}
      />
      {!value && (
        <span className="estimate-placeholder">
          {placeholder} <em>*</em>
        </span>
      )}
    </label>
  );
}

export function Estimate() {
  const {estimate, assetBase} = getContent();
  const [message, setMessage] = useState('');
  const [file, setFile] = useState<File | null>(null);
  const [fileError, setFileError] = useState('');
  const [status, setStatus] = useState('');
  const asset = (name: string) => `${assetBase}estimate-${name}.svg`;

  function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setStatus('Отправка пока недоступна: сервер приёма заявок ещё не подключён. Заявка и файл не отправлены.');
  }

  return (
    <section id="estimate" className="estimate" data-header-theme="light" aria-labelledby="estimate-title">
      <div className="estimate-art" tabIndex={0} aria-label="Как получить расчёт: пришлите фото или размеры, мы уточним детали, предложим варианты конструкции и подготовим предварительный расчёт">
        <img src={estimate.glass} alt="Съёмка пространства на телефон через рифлёное стекло" loading="lazy" />
        <div className="estimate-art-hover" aria-hidden="true">
          <img className="estimate-hover-photo" src={estimate.hover} alt="" />
          <div className="estimate-step estimate-step--intro">
            Пришлите нам фото
            <br />
            или размеры
          </div>
          <div className="estimate-step estimate-step--one">мы уточним детали;</div>
          <div className="estimate-step estimate-step--two">
            предложим несколько
            <br />
            вариантов конструкции;
          </div>
          <div className="estimate-step estimate-step--three">
            подготовим для вас
            <br />
            предварительный расчёт.
          </div>
          <img className="estimate-route estimate-route--one" src={`${assetBase}estimate-hover-imgVector3.svg`} alt="" />
          <img className="estimate-route estimate-route--two" src={`${assetBase}estimate-hover-imgVector4.svg`} alt="" />
          <img className="estimate-route estimate-route--three" src={`${assetBase}estimate-hover-imgVector5.svg`} alt="" />
          {[1, 2, 3].map((n) => (
            <span className={`estimate-step-number estimate-step-number--${n}`} key={n}>
              {n}
            </span>
          ))}
        </div>
      </div>
      <div className="estimate-content">
        <h2 id="estimate-title">
          {estimate.titleLines.map((line) => (
            <span key={line}>{line}</span>
          ))}
        </h2>
        <form className="estimate-form" onSubmit={submit} onChange={() => setStatus('')}>
          <div className="estimate-fields">
            <div className="estimate-inputs">
              <TextField />
              <TextField phone />
            </div>
            <div className="estimate-message">
              <CutSurface />
              <label className="estimate-message-label">
                <textarea name="message" aria-label="Описание задачи" value={message} onChange={(event) => setMessage(event.target.value)} maxLength={500} required />
                {!message && (
                  <span className="estimate-placeholder">
                    Placeholder <em>*</em>
                  </span>
                )}
              </label>
              <div className="estimate-attachment">
                <label className="estimate-file">
                  <img src={asset('imgAntDesignPaperClipOutlined')} alt="" />
                  <span>{file ? file.name : 'Файл'}</span>
                  <input
                    type="file"
                    name="attachment"
                    accept=".jpg,.jpeg,.png,.pdf,.doc"
                    aria-label="Прикрепить файл JPG, PNG, PDF, DOC до 10 МБ"
                    onChange={(event) => {
                      const next = event.target.files?.[0];
                      setFileError('');
                      if (next && (next.size > 10 * 1024 * 1024 || !/\.(jpe?g|png|pdf|doc)$/i.test(next.name))) {
                        setFileError('Выберите JPG, PNG, PDF или DOC размером до 10 МБ.');
                        event.target.value = '';
                        setFile(null);
                        return;
                      }
                      setFile(next ?? null);
                    }}
                  />
                </label>
                <img className="estimate-file-divider" src={asset('imgLine14')} alt="" />
                <span className="estimate-file-hint">JPG, PNG, PDF, DOC (до 10 МБ)</span>
                <span className="estimate-counter">{message.length}/500</span>
              </div>
            </div>
          </div>
          {fileError && (
            <p className="estimate-notice" role="alert">
              {fileError}
            </p>
          )}
          <label className="estimate-consent">
            <input type="checkbox" name="consent" required aria-label="Согласие на обработку персональных данных" />
            <span className="estimate-check" aria-hidden="true">
              <img src={asset('imgFrame123')} alt="" />
            </span>
            <span>
              Даю согласие на <span className="estimate-policy" title="Документ пока не предоставлен">обработку персональных данных</span> для рассмотрения заявки и обратной связи в соответствии с{' '}
              <span className="estimate-policy" title="Документ пока не предоставлен">политикой конфиденциальности</span>
            </span>
          </label>
          <button className="estimate-submit" type="submit">
            <CutSurface button />
            <span>{estimate.submitLabel}</span>
            <img src={asset('imgIconsArrowDownRight')} alt="" />
          </button>
          {status && (
            <p className="estimate-notice" role="status">
              {status}
            </p>
          )}
        </form>
      </div>
    </section>
  );
}
