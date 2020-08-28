<?php

class rex_yform_value_spam_protection extends rex_yform_value_abstract
{
    public function postValidateAction()
    {
        rex_login::startSession();

        $debug = (int)$this->getElement(4);
        $session_timestamp_key = "spamfilter".$this->params["form_name"];
        $session_timestamp = rex_request::session($session_timestamp_key);
        $form_timestamp = rex_request($this->getFieldId()."_microtime", 'int', false);
        $js_enabled = rex_request($this->getFieldId()."_js_enabled", 'int', false);

        $ipv4 = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        $ipv6 = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);

        if ($debug) {
            rex_sql::factory()->setDebug($debug)->setQuery("DELETE FROM rex_tmp_yform_spam_protection_frequency  WHERE createdate < (NOW() - INTERVAL ".rex_config::get('yform_spam_protection', 'ip_block_timer')." SECOND)");
        }

        $count = rex_sql::factory()->setDebug($debug)->getArray("SELECT count(`createdate`) AS `count` FROM rex_tmp_yform_spam_protection_frequency WHERE `ipv4` = INET_ATON(:ipv4) AND `ipv6` = :ipv6", [':ipv4' => $ipv4, ':ipv6' => $ipv6])[0]['count'];

        $log = [];

        if ($this->params['send'] == 1) {
            if (rex_request($this->getFieldId()) != "") {
                $this->params['warning'][$this->getId()] = $this->params['error_class'];
                $this->params['warning_messages'][$this->getId()] = $this->getElement(3);
                $log[] = "honeypot wurde ausgefüllt: ".rex_request($this->getFieldId());
            }

            if (rex_config::get('yform_spam_protection', 'ip_block_limit') < $count) {
                rex_sql::factory()->setDebug($debug)->setQuery("INSERT INTO rex_tmp_yform_spam_protection_frequency (`ipv4`, `ipv6`, `createdate`, `was_blocked`) VALUES (INET_ATON(:ipv4), :ipv6, NOW(), 1)", [':ipv4'=>$ipv4, ':ipv6'=>$ipv6]);
                $this->params['warning'][$this->getId()] = $this->params['error_class'];
                $this->params['warning_messages'][$this->getId()] = $this->getElement(3);
                $log[] = "ip hat zu viele Versuche in kürzester Zeit unternommen";
            } else {
                rex_sql::factory()->setDebug($debug)->setQuery("INSERT INTO rex_tmp_yform_spam_protection_frequency (`ipv4`, `ipv6`, `createdate`, `was_blocked`) VALUES (INET_ATON(:ipv4), :ipv6, NOW(), 0)", [':ipv4'=>$ipv4, ':ipv6'=>$ipv6]);
            }

            if (($session_timestamp + rex_config::get('yform_spam_protection', 'timer_session')) > microtime(true)) {
                $this->params['warning'][$this->getId()] = $this->params['error_class'];
                $this->params['warning_messages'][$this->getId()] = $this->getElement(3);
                $log[] = "session-microtime nicht eingehalten: $session_timestamp + ".rex_config::get('yform_spam_protection', 'timer_session')." > ".microtime(true);
            } else {
                $log[] = "session-microtime eingehalten: $session_timestamp + ".rex_config::get('yform_spam_protection', 'timer_session')." > ".microtime(true);
            }

            if (($form_timestamp + rex_config::get('yform_spam_protection', 'timer_form')) > microtime(true)) {
                $this->params['warning'][$this->getId()] = $this->params['error_class'];
                $this->params['warning_messages'][$this->getId()] = $this->getElement(3);
                $log[] = "formular-microtime nicht eingehalten: $form_timestamp + ".rex_config::get('yform_spam_protection', 'timer_form')." > ".microtime(true);
            } else {
                $log[] = "formular-microtime eingehalten: $form_timestamp + ".rex_config::get('yform_spam_protection', 'timer_form')." > ".microtime(true);
            }
        }

        if ($debug) {
            dump($log);
        }

        rex_request::setSession($session_timestamp_key, microtime(true));
    }

    public function enterObject()
    {
        if ($this->needsOutput()) {
            $this->params['form_output'][$this->getId()] = $this->parse('value.spam_protection.tpl.php', []);
        }
    }

    public function getDescription()
    {
        return 'spam_protection|honeypot|label(Bitte nicht ausfüllen)|Fehler(Ihre Anfrage wurde als Spam erkannt.)|Debugmodus(0/1)';
    }
}
