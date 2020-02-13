<?php

echo \rex_view::title(\rex_i18n::msg('yform'));


if (rex::getUser()->isAdmin()) {
    $form = rex_config_form::factory($this->getProperty('package'));

    $form->addFieldset("Einstellungen");

    $field = $form->addTextField('warning');
    $field->setLabel('Fehlermeldung');
    $field->setNotice("Fehlermeldung, die die Validierung ausgeben soll. Kann mit dem Addon Sprog oder XOutputFilter mehrsprachig übersetzt werden.");

    $field = $form->addSelectField('timer');
    $field->setLabel("Timer");
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption("aktivieren", 1);
    $select->addOption("deaktivieren", 0);

    $field = $form->addSelectField('honeypot');
    $field->setLabel("Honeypot");
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption("aktivieren", 1);
    $select->addOption("deaktivieren", 0);

    $field = $form->addSelectField('ip_block');
    $field->setLabel("dynamische IP-Sperre");
    $field->setNotice("Sollten zu viele Formulare über diese IP-Adresse versendet werden, wird die IP für eine bestimmte Zeit geblockt.");
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption("aktivieren", 1);
    $select->addOption("deaktivieren", 0);

    $form->addFieldset("Erweiterte Einstellungen");

    $field = $form->addReadOnlyTextField('notification_email');
    $field->setLabel('E-Mail-Adresse');
    $field->setNotice("Adresse, die bei erhöhtem Spam-Aufkommen benachrichtigt wird.");

    $field = $form->addTextField('timer_session');
    $field->setLabel('Timer 1');
    $field->setNotice("Anzahl der Sekunden, die mind. für die Bearbeitung oder Korrektur eines Formulars benötigt werden.");

    $field = $form->addTextField('timer_form');
    $field->setLabel('Timer 2');
    $field->setNotice("Anzahl der Sekunden, die mind. seit dem 1. Aufruf eines Formulars vergehen muss.");

    $field = $form->addTextField('ip_block_limit');
    $field->setLabel('IP-Sperren-Limit');
    $field->setNotice("Anzahl, die im IP-Sperren-Zeitfenster überschritten werden muss, z.B. <code>10</code> pro Zeitfenster");

    $field = $form->addTextField('ip_block_timer');
    $field->setLabel('IP-Sperren-Zeitfenster');
    $field->setNotice("In Sekunden, in denen das Anfrage-Limit überschritten werden muss, z.B. <code>600</code> für 10 Minuten.");

    $field = $form->addSelectField('geo_block');
    $field->setLabel("GeoIP-Sperre");
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption("aktivieren", 1);
    $select->addOption("deaktivieren", 0);

    $field = $form->addSelectField('tld_block');
    $field->setLabel("IP-Sperre");
    $select = $field->getSelect();
    $select->setSize(1);
    $select->addOption("aktivieren", 1);
    $select->addOption("deaktivieren", 0);
    
    $field = $form->addTextField('tld_list');
    $field->setLabel('Top-Level-Domains');
    $field->setNotice("Top-Level-Domains, an die kein Versand erfolgen soll, bspw. <code>.ru</code>");

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', "Schaltflächen zur Datenschutzerklärung", false);
    $fragment->setVar('body', $form->get(), false);
    echo $fragment->parse('core/page/section.php');
}
