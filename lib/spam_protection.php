<?php

class spam_protection extends \rex_yform_manager_dataset
{
    public static function releaseIp($ip)
    {
        rex_sql::factory()->setQuery("DELETE FROM rex_tmp_spam_protection_log  WHERE ip = :ip", ['ip' => $ip]);
    }
    public static function flush()
    {
        rex_sql::factory()->setQuery("DELETE FROM rex_tmp_spam_protection_log");
    }
    public static function release()
    {
        rex_sql::factory()->setQuery("DELETE FROM rex_tmp_spam_protection_log  WHERE releasedate < NOW()");
    }

    public static function checkWhitelist($ip, $debug):bool
    {
        if (rex_backend_login::createUser() && self::getConfig('whitelist_user')) {
            $debug ? dump("whitelist_user") : "";
            return true;
        }
        if (rex_addon::get('ycom')->isInstalled()) {
            if (rex_ycom_auth::getUser() && self::getConfig('whitelist_ycom_user')) {
                $debug ? dump("whitelist_ycom_user") : "";
                return true;
            }
        }

        $ip_whitelist = explode(',', self::getConfig('whitelist_ips'));
        if (in_array($ip, $ip_whitelist)) {
            $debug ? dump("whitelist_ips") : "";
            return true;
        };

        return false;
    }

    public static function checkTld($email) :bool
    {
        $tlds = explode(",", self::getConfig('block_tlds'));
        $blocked = false;
        foreach ($tlds as $tld) {
            $tld = '\.'. trim($tld, ". ");
            $pattern = '/^.*\@[-a-zA-Z0-9]*'.$tld.'$/m';
            if (preg_match($pattern, $email)) {
                $blocked = true;
                break;
            };
        }

        return $blocked;
    }

    public static function check($ip, $debug = false, $form_microtime = null, $session_microtime = null, $honeypot = null, $email = null) :rex_yform_manager_dataset
    {
        $createdate = new DateTime('now');
        $releasedate = new DateTime('now + '.self::getConfig('block_threshold_time') .' seconds');
        $entry = self::create()
        ->setValue('ip', $ip)
        ->setValue('createdate', $createdate->format("Y-m-d H:i:s"))
        ->setValue('releasedate', $releasedate->format("Y-m-d H:i:s"));
        $entry->save();
        $reason = [];

        if (self::checkWhitelist($ip, $debug)) {
            return $entry;
            $debug ? dump("whitelisted") : null;
        }
        if (self::checkBlockedAlready($ip)) {
            $reason[] = 'already';
            $debug ? dump("already") : null;
        }
        if (self::checkHoneypot($honeypot)) {
            $reason[] = 'honeypot';
            $debug ? dump("honeypot") : null;
        }
        if (self::checkThreshold($ip)) {
            $reason[] = 'threshold';
            $debug ? dump("threshold") : null;
        }
        if (self::checkTld($email)) {
            $reason[] = 'tld';
            $debug ? dump("tld") : null;
        }
        if (self::checkStopForumSpam($ip)) {
            $reason[] = 'block_stop_spam';
        }
        if (self::checkFormMicrotime($form_microtime)) {
            $releasedate = new DateTime('now + '.self::getConfig('block_form_time') .' seconds');
            $entry->setValue('releasedate', $releasedate->format("Y-m-d H:i:s"));

            $reason[] = 'form_microtime';
            $debug ? dump("form_microtime") : null;
        }
        if (self::checkSessionMicrotime($session_microtime)) {
            $releasedate = new DateTime('now + '.self::getConfig('block_session_time') .' seconds');
            $entry->setValue('releasedate', $releasedate->format("Y-m-d H:i:s"));

            $reason[] = 'session_microtime';
            $debug ? dump("session_microtime") : null;
        }
        $entry->setValue('reason', implode(",", $reason));
        $entry->save();
                
        return $entry;
    }

    public function block($reason = 'other')
    {
        $this->setValue('blocked', $reason)->save();
    }

    public static function checkStopForumSpam($ip) :bool
    {
        if (!self::getConfig('block_stop_spam')) {
            return false;
        }
        
        $result = file_get_contents('https://api.stopforumspam.org/api?ip='.$ip);
        if ($result) {
            $doc = new DOMDocument();
            $doc->loadXML($result);
            if ($doc->getElementsByTagName('appears')[0]->nodeValue == 'yes') {
                return true;
            }
        }

        return false;
    }

    public static function checkBlockedAlready($ip):bool
    {
        if (count(spam_protection::query()->where('ip', $ip)->where('reason', '', '!=')->find())) {
            return true;
        }
        return false;
    }

    public static function checkThreshold($ip)
    {
        if (count(spam_protection::query()->where('ip', $ip)->find()) > self::getConfig('block_threshold_limit')) {
            return true;
        }
        return false;
    }

    public static function checkFormMicrotime($form_timestamp = null)
    {
        if ($form_timestamp && ($form_timestamp + self::getConfig('block_form_time') > microtime(true))) {
            return true;
        }
        return false;
    }

    public static function checkSessionMicrotime($session_timestamp = null)
    {
        if ($session_timestamp && ($session_timestamp + self::getConfig('block_session_time') > microtime(true))) {
            return true;
        }
        return false;
    }


    public static function checkHoneypot($string = null)
    {
        if ($string) {
            return true;
        }
        return false;
    }


    public static function getConfig($key)
    {
        return rex_config::get('yform_spam_protection', $key);
    }
}
