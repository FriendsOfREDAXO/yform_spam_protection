<?php

class rex_yform_value_spam_protection extends rex_yform_value_abstract
{
    public function postValidateAction(): void
    {
        $ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

        $form_microtime = rex_request($this->getFieldId()."_microtime", 'int', null);
        rex_login::startSession();
        $session_timestamp_key = "spam_protection_".$this->params["form_name"];
        $session_microtime = rex_request::session($session_timestamp_key, 'int', null);

        $email = "";
        foreach ($this->getObjects() as $Object) {
            if ($Object->getName() == $this->getElement(3)) {
                $email = $Object->getValue();
            }
        }

        
        if ($this->params['send'] == 1) {
            $ip_dataset = spam_protection::check($ip, 1, $form_microtime, $session_microtime, rex_request($this->getFieldId(), 'string', ''), $email);

            /* Fehlermeldeungen erhalten */
            if ($ip_dataset->getValue('reason')) {
                $reasons = explode(",", $ip_dataset->getValue('reason'));
                $this->params['warning'][$this->getId()] = $this->params['error_class'];
                foreach ($reasons as $reason => $message) {
                    $this->params['warning_messages'][$this->getId()."_".$reason] = "{{ yform_spam_protection_warning }} ". "{{ $message }}";
                }
            }
        }

        rex_request::setSession($session_timestamp_key, microtime(true));
    }

    public function enterObject()
    {
        if ($this->needsOutput()) {
            $this->params['form_output'][$this->getId()] = $this->parse('value.spam_protection.tpl.php', []);
        }
    }

    public function getDescription(): string
    {
        return 'spam_protection2|honeypot|label(Bitte nicht ausfüllen)|email_field(email)|Allgemeine Fehlermeldung(Ihre Anfrage wurde als Spam erkannt.)|Debug-Ausgabe aktivieren (0/1)';
    }
}
