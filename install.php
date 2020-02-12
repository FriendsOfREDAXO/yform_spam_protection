<?php

rex_sql_table::get(rex::getTable('tmp_yform_spam_protection_frequency'))
    ->ensureColumn(new rex_sql_column('ipv4', 'int(10) unsigned', true))
    ->ensureColumn(new rex_sql_column('ipv6', 'varbinary(16)', true))
    ->ensureColumn(new rex_sql_column('createdate', 'datetime', false))
    ->ensureColumn(new rex_sql_column('was_blocked', 'bit(1)'))
    ->ensure();

    if (!$this->hasConfig("notification_email")) {
        $this->setConfig('notification_email', "");
    }

    if (!$this->hasConfig("timer")) {
        $this->setConfig('timer', 1);
    }

    if (!$this->hasConfig("timer_session")) {
        $this->setConfig('timer_session', 2);
    }

    if (!$this->hasConfig("timer_form")) {
        $this->setConfig('timer_form', 5);
    }

    if (!$this->hasConfig("honeypot")) {
        $this->setConfig('honeypot', 1);
    }
    
    if (!$this->hasConfig("ip_block")) {
        $this->setConfig('ip_block', 1);
    }

    if (!$this->hasConfig("ip_block_limit")) {
        $this->setConfig('ip_block_limit', 10);
    }
    if (!$this->hasConfig("ip_block_timer")) {
        $this->setConfig('ip_block_timer', 300);
    }
    
    if (!$this->hasConfig("geo_block")) {
        $this->setConfig('geo_block', 0);
    }
    
    if (!$this->hasConfig("tld_block")) {
        $this->setConfig('tld_block', 0);
    }
    if (!$this->hasConfig("tld_list")) {
        $this->setConfig('tld_list', ".ru");
    }
    if (!$this->hasConfig("warning")) {
        $this->setConfig('warning', "Ihre Anfrage wurde als Spam erkannt und nicht zugestellt. Sollte dies irrtümlich passiert sein, wenden Sie sich bitte an den Betreiber der Website.");
    }
