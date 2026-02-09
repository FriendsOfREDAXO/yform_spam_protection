<?php

declare(strict_types=1);

namespace AltchaOrg\Altcha;

use AltchaOrg\Altcha\Hasher\Algorithm;

class CheckChallengeOptions extends BaseChallengeOptions
{
    public function __construct(
        Algorithm $algorithm,
        string $salt,
        int $number,
    ) {
        parent::__construct($algorithm, self::DEFAULT_MAX_NUMBER, null, $salt, $number, []);
    }
}
