<?php

/* Tablesets und Tabellen löschen */
rex_sql_table::get(rex::getTable('tmp_yform_spam_protection_frequency'))->drop();

/* Konfiguration löschen */
rex_config::removeNamespace('yform_spam_protection');
