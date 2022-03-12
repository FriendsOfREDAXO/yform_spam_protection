<?php

$yform = $this->getProperty('yform', []);
$yform = $yform[\rex_be_controller::getCurrentPage()] ?? [];

$table_name = 'rex_tmp_spam_protection_log';

if ($table_name) {
    $_REQUEST['table_name'] = $table_name;
}

include \rex_path::plugin('yform', 'manager', 'pages/data_edit.php');
