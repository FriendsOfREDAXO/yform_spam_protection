<?php

class rex_yform_validate_cleantalk extends rex_tmp_yform_value_abstract
{
    public function enterObject()
    {

        $compare_type = $this->getElement('compare_type');
        $compare_value = $this->getElement('compare_value');

        rex_login::startSession();

        $debug = (int)$this->getElement(7);
        $log = [];
        
        $config_url = 'http://moderate.cleantalk.ru';
        $auth_key = rex_config::get('yform_spam_protection', 'cleantalk_api_key');
        $ct_request = new CleantalkRequest();

        $ct_request->auth_key = $auth_key;
        $ct_request->message = $this->getElement('message') ?? "";
        $ct_request->sender_email = $this->getElement('email');
        $ct_request->sender_nickname = $this->getElement('name');
        // $ct_request->example = str_repeat('Just text ', 10);
        $ct_request->agent = $_SERVER['HTTP_USER_AGENT'];
        $ct_request->sender_ip = $_SERVER['REMOTE_ADDR'];
        $ct_request->js_on = rex_request($this->getElement('spam_protection')."_js_enabled", 'int', 0);
        $ct_request->submit_time = rex_request($this->getElement('spam_protection')."_microtime", 'int', 0);

        if($debug) {
            dump($ct_request);
        }

        $ct = new Cleantalk();
        $ct->server_url = $config_url;
        
        // Check
        $ct_result = $ct->isAllowMessage($ct_request);
        
        if($debug) {
            dump($ct_result);
        }

        if ($this->params['send'] == 1) {
            if ($ct_result->allow == 1) {
                $log[] = "cleantalk erlaubt: ". $ct_result->comment;

            } else {
                $this->params['warning'][$this->getId()] = $this->params['error_class'];
                $this->params['warning_messages'][$this->getId()] = $this->getElement(6);
                $log[] = "cleantalk blockiert: ". $ct_result->comment;
            }

            if ($debug) {
                dump($log);
            }
        }
    }

    public function getDescription()
    {
        return 'validate|cleantalk|feldname(name)|feldname(email)|feldname(message)|feldname(spam_protection)|warning_message|debug';
    }

    public function getDefinitions()
    {
        return [
            'type' => 'validate',
            'name' => 'cleantalk',
            'values' => [
                'name' => ['type' => 'select_name', 'label' => rex_i18n::msg('yform_validate_cleantalk_value_name')],
                'email' => ['type' => 'text', 'label' => rex_i18n::msg('yform_validate_cleantalk_value_compare_email')],
                'message' => ['type' => 'text', 'label' => rex_i18n::msg('yform_validate_cleantalk_value_compare_message')],
                'spam_protection' => ['type' => 'text', 'label' => rex_i18n::msg('yform_validate_cleantalk_value_compare_spam_protection')],
                'warning' => ['type' => 'text', 'label' => rex_i18n::msg('yform_validate_cleantalk_value_warning')],
                'debug' => ['type' => 'text', 'label' => rex_i18n::msg('yform_validate_cleantalk_value_debug')],
            ],
            'description' => rex_i18n::msg('yform_validate_cleantalk_value_description'),
            'multi_edit' => false,
        ];
    }
}
