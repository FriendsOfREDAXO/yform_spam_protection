<?php

echo rex_view::title(rex_i18n::msg('yform_spam_protection_title'));

if (rex_request::get('func', 'string') == "flush") {
    spam_protection::release();
    echo rex_view::info($this->i18n("flushed"));
}

    $form = rex_config_form::factory($this->getProperty('package'));

    $form->addFieldset($this->i18n('rules'));

    $field = $form->addTextField('block_session_time');
    $field->setLabel($this->i18n('block_session_time_label'));
    $field->setNotice($this->i18n('block_session_time_notice'));

    $field = $form->addTextField('block_form_time');
    $field->setLabel($this->i18n('block_form_time_label'));
    $field->setNotice($this->i18n('block_form_time_notice'));

    $field = $form->addTextField('block_threshold_limit');
    $field->setLabel($this->i18n('block_threshold_limit_label'));
    $field->setNotice($this->i18n('block_threshold_limit_notice'));

    $field = $form->addTextField('block_threshold_time');
    $field->setLabel($this->i18n('block_threshold_time_label'));
    $field->setNotice($this->i18n('block_threshold_time_notice'));
    
    $field = $form->addTextField('block_tlds');
    $field->setLabel($this->i18n('block_tlds_label'));
    $field->setNotice($this->i18n('block_tlds_notice'));

    $form->addFieldset($this->i18n('whitelist'));

    $field = $form->addTextField('whitelist_ips');
    $field->setLabel($this->i18n('whitelist_ips_label'));
    $field->setNotice($this->i18n('whitelist_ips_notice'));


    $field = $form->addSelectField('whitelist_user');
    $field->setLabel($this->i18n('whitelist_user_label'));
    $field->setNotice($this->i18n('whitelist_user_notice'));
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption($this->i18n('activate'), 1);
    $select->addOption($this->i18n('deactivate'), 0);

    $field = $form->addSelectField('whitelist_ycom_user');
    $field->setLabel($this->i18n('whitelist_ycom_user_label'));
    $field->setNotice($this->i18n('whitelist_ycom_user_notice'));
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption($this->i18n('activate'), 1);
    $select->addOption($this->i18n('deactivate'), 0);

    if (!rex_addon::get('ycom')->isInstalled()) {
        $field->setAttribute('disabled', 'disabled');
        $field->setNotice($this->i18n('whitelist_ycom_user_notice_disabled'));
    }

    $form->addFieldset($this->i18n('external_services'));

    $field = $form->addSelectField('block_stopforumspam');
    $field->setLabel($this->i18n('block_stopforumspam_label'));
    $field->setNotice($this->i18n('block_stopforumspam_notice'));
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption($this->i18n('activate'), 1);
    $select->addOption($this->i18n('deactivate'), 0);


    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $this->i18n('title'), false);
    $fragment->setVar('body', $form->get(), false);
    echo $fragment->parse('core/page/section.php');
