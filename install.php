<?php

$addon = rex_addon::get('yform_spam_protection');

rex_sql_table::get(rex::getTable('tmp_yform_spam_protection_frequency'))
    ->ensureColumn(new rex_sql_column('ipv4', 'int(10) unsigned', true))
    ->ensureColumn(new rex_sql_column('ipv6', 'varbinary(16)', true))
    ->ensureColumn(new rex_sql_column('createdate', 'datetime', false))
    ->ensureColumn(new rex_sql_column('was_blocked', 'bit(1)'))
    ->ensure();
