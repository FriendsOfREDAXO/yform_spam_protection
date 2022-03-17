<?php
rex_extension::register('PACKAGES_INCLUDED', function (rex_extension_point $ep) {
    rex_yform::addTemplatePath($this->getPath('ytemplates'));
});

spam_protection::release();

rex_yform_manager_dataset::setModelClass(
    'rex_tmp_spam_protection_log',
    spam_protection::class
);
