<div id="<?= $this->getHTMLId() ?>" class="<?= $this->getWarningClass() ?>">
    <altcha-widget challengeurl="/?altcha=challenge" auto="onload"></altcha-widget>
    <script type="module" nonce="<?= rex_response::getNonce() ?>"
        src="<?= rex_url::addonAssets('yform_spam_protection', 'altcha.min.js') ?>"
        async defer></script>
</div>
