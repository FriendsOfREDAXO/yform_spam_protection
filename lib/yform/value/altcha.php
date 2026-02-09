<?php

class rex_yform_value_altcha extends rex_yform_value_abstract
{
    public function enterObject()
    {
        if (1 == $this->params['send']) {
            $payload = rex_request('altcha', 'string', '');
            $secret = self::getSecret();
            $altcha = new \AltchaOrg\Altcha\Altcha($secret);

            if (!$payload || !$altcha->verifySolution($payload)) {
                $this->params['warning'][$this->getId()] = $this->params['error_class'];
                $this->params['warning_messages'][$this->getId()] = $this->getElement(2);
            }
        }

        if ($this->needsOutput()) {
            $this->params['form_output'][$this->getId()] = $this->parse('value.altcha.tpl.php');
        }
    }

    public static function createChallenge(): string
    {
        $options = new \AltchaOrg\Altcha\ChallengeOptions();
        $altcha = new \AltchaOrg\Altcha\Altcha(self::getSecret());
        try {
            return json_encode($altcha->createChallenge($options));
        } catch (\Exception $e) {
            \rex_logger::factory()->error('Altcha: ' . $e->getMessage());
            return '';
        }
    }

    private static function getSecret(): string
    {
        $secret = \rex_config::get('yform_spam_protection', 'altcha_secret', '');
        if ($secret === '') {
            $secret = bin2hex(random_bytes(32));
            \rex_config::set('yform_spam_protection', 'altcha_secret', $secret);
        }
        return $secret;
    }

    public function getDescription(): string
    {
        return 'altcha|name|error_message|';
    }
}
