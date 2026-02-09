<?php

rex_extension::register('PACKAGES_INCLUDED', function (rex_extension_point $ep) {
    rex_yform::addTemplatePath($this->getPath('ytemplates'));
});

// Altcha Challenge-Endpoint
if (rex::isFrontend()) {
    rex_extension::register('OUTPUT_FILTER', function () {
        if (rex_request('altcha') === 'challenge') {
            rex_response::sendContent(
                rex_yform_value_altcha::createChallenge(),
                'application/json'
            );
            exit;
        }
    });
}
