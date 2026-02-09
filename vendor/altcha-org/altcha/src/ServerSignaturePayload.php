<?php

declare(strict_types=1);

namespace AltchaOrg\Altcha;

use AltchaOrg\Altcha\Hasher\Algorithm;

class ServerSignaturePayload
{
    public function __construct(
        public readonly Algorithm $algorithm,
        public readonly string $verificationData,
        public readonly string $signature,
        public readonly bool $verified,
    ) {
    }
}
