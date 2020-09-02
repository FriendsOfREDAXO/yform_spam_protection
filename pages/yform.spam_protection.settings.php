<?php

echo rex_view::title(rex_i18n::msg('yform'));


if (rex::getUser()->isAdmin()) {
    $form = rex_config_form::factory($this->getProperty('package'));

    $form->addFieldset($this->i18n('settings'));

    // $field = $form->addTextField('warning');
    // $field->setLabel($this->i18n('error_message'));
    // $field->setNotice($this->i18n('error_notice'));
    // $field->setAttribute('disabled', true);

    $form->addFieldset($this->i18n('checks'));

    // $field = $form->addSelectField('timer');
    // $field->setLabel($this->i18n('timer'));
    // $select = $field->getSelect();
    // $select->setSize(1);
    // $select->addOption($this->i18n('activate'), 1);
    // $select->addOption($this->i18n('deactivate'), 0);
    // $field->setAttribute('disabled', true);

    // $field = $form->addSelectField('honeypot');
    // $field->setLabel($this->i18n('honeypot'));
    // $select = $field->getSelect();
    // $select->setSize(1);
    // $select->addOption($this->i18n('activate'), 1);
    // $select->addOption($this->i18n('deactivate'), 0);
    // $field->setAttribute('disabled', true);

    // $field = $form->addSelectField('ip_block');
    // $field->setLabel($this->i18n('block_ip_dyn'));
    // $field->setNotice($this->i18n('block_ip_dyn_notice'));
    // $select = $field->getSelect();
    // $select->setSize(1);
    // $select->addOption($this->i18n('activate'), 1);
    // $select->addOption($this->i18n('deactivate'), 0);
    // $field->setAttribute('disabled', true);

    $form->addFieldset($this->i18n('advanced_settings'));

    // $field = $form->addReadOnlyTextField('notification_email');
    // $field->setLabel($this->i18n('email'));
    // $field->setNotice($this->i18n('email_notice'));
    // $field->setAttribute('disabled', true);

    $field = $form->addTextField('timer_session');
    $field->setLabel($this->i18n('timer_1'));
    $field->setNotice($this->i18n('timer_1_notice'));

    $field = $form->addTextField('timer_form');
    $field->setLabel($this->i18n('timer_2'));
    $field->setNotice($this->i18n('timer_2_notice'));

    $field = $form->addTextField('ip_block_limit');
    $field->setLabel($this->i18n('timer_block_limit'));
    $field->setNotice($this->i18n('timer_block_limit_notice'));

    $field = $form->addTextField('ip_block_timer');
    $field->setLabel($this->i18n('timer_block_limit_window'));
    $field->setNotice($this->i18n('timer_block_limit_window_notice'));

    // $field = $form->addSelectField('geo_block');
    // $field->setLabel($this->i18n('geoip'));
    // $select = $field->getSelect();
    // $select->setSize(1);
    // $select->addOption($this->i18n('activate'), 1);
    // $select->addOption($this->i18n('deactivate'), 0);
    // $field->setAttribute('disabled', true);

    // $field = $form->addSelectField('tld_block');
    // $field->setLabel($this->i18n('tld_block'));
    // $select = $field->getSelect();
    // $select->setSize(1);
    // $select->addOption($this->i18n('activate'), 1);
    // $select->addOption($this->i18n('deactivate'), 0);
    // $field->setAttribute('disabled', true);

    // $field = $form->addTextField('tld_list');
    // $field->setLabel($this->i18n('tld_list'));
    // $field->setNotice($this->i18n('tld_list_notice'));
    // $field->setAttribute('disabled', true);

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $this->i18n('title'), false);
    $fragment->setVar('body', $form->get(), false);
    echo $fragment->parse('core/page/section.php');
}
