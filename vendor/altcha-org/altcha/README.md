# ALTCHA PHP Library

The ALTCHA PHP Library is a lightweight, zero-dependency library designed for creating and verifying [ALTCHA](https://altcha.org) challenges, specifically tailored for PHP applications.

## Compatibility

This library is compatible with:

- PHP 8.2+ (use version v0.x.x for older PHP version)
- All major platforms (Linux, Windows, macOS)

## Example

- [Demo server](https://github.com/altcha-org/altcha-starter-php)

## Installation

To install the ALTCHA PHP Library, use the following command:

```sh
composer require altcha-org/altcha
```

## Usage

Here’s a basic example of how to use the ALTCHA PHP Library:

```php
<?php

require 'vendor/autoload.php';

use AltchaOrg\Altcha\ChallengeOptions;
use AltchaOrg\Altcha\Altcha;

$altcha = new Altcha('secret hmac key');

// Create a new challenge
$options = new ChallengeOptions(
    maxNumber: 50000, // the maximum random number
    expires: (new \DateTimeImmutable())->add(new \DateInterval('PT10S')),
);

$challenge = $altcha->createChallenge($options);
echo "Challenge created: " . json_encode($challenge) . "\n";

// Example payload to verify
$payload = [
    'algorithm' => $challenge->algorithm,
    'challenge' => $challenge->challenge,
    'number'    => 12345, // Example number
    'salt'      => $challenge->salt,
    'signature' => $challenge->signature,
];

// Verify the solution
$ok = $altcha->verifySolution($payload, true);

if ($ok) {
    echo "Solution verified!\n";
} else {
    echo "Invalid solution.\n";
}
```

## API

### `Altcha::createChallenge(ChallengeOptions $options): Challenge`

Creates a new challenge for ALTCHA.

**Returns:** `Challenge`

#### `ChallengeOptions`

```php
$options = new ChallengeOptions(
    algorithm: Algorithm::SHA256,
    maxNumber: BaseChallengeOptions::DEFAULT_MAX_NUMBER,
    expires: (new \DateTimeImmutable())->add(new \DateInterval('PT10S')),
    params: ['query_param' => '123'],
    saltLength: 12
);
```

### `Altcha::verifySolution(array|string $payload, bool $checkExpires): bool`

Verifies an ALTCHA solution.

**Parameters:**

- `data array|string`: The solution payload to verify.
- `checkExpires bool`: Whether to check if the challenge has expired.

**Returns:** `bool`

### `Altcha::verifyFieldsHash(array $formData, array $fields, string $fieldsHash, Algorithm $algorithm): bool`

Verifies the hash of form fields.

**Parameters:**

- `formData array`: The form data to hash.
- `fields array`: The fields to include in the hash.
- `fieldsHash string`: The expected hash value.
- `algorithm string`: Hashing algorithm (`SHA-1`, `SHA-256`, `SHA-512`).

**Returns:** `bool`

### `Altcha::verifyServerSignature(array|string $payload): ServerSignatureVerification`

Verifies the server signature.

**Parameters:**

- `data array|string`: The payload to verify (string or `ServerSignaturePayload` array).

**Returns:** `ServerSignatureVerification`

### `Altcha::solveChallenge(string $challenge, string $salt, Algorithm $algorithm, int $max, int $start = 0): array`

Finds a solution to the given challenge.

**Parameters:**

- `challenge string`: The challenge hash.
- `salt string`: The challenge salt.
- `algorithm string`: Hashing algorithm (`SHA-1`, `SHA-256`, `SHA-512`).
- `max int`: Maximum number to iterate to.
- `start int`: Starting number.

**Returns:** `null|Solution`

## Generate obfuscation payload

Generate an obfuscated payload for client-side clarification:

```php
<?php

// With optional maxNumber (defaults to 10_000)
$obfuscator = new \AltchaOrg\Altcha\Obfuscator(); 

// Text to reveal after client-side PoW
$plaintext = 'mailto:hello@example.com';

// Optional shared key
$key = 'shared-secret';

// Optionally fix the counter; omit to use a random counter in [0, maxNumber]
$fixedCounter = null;

// Generate base64 obfuscated payload 
$payload = $obfuscator->obfuscateData($plaintext, $key, $fixedCounter);

echo $payload;
// P7bJsUgzxP416d1voeF/QnQOD5g7GItB/zdfkoBrKgZK4N4IYkDJqg==
```

## Tests

```sh
vendor/bin/phpunit --bootstrap src/Altcha.php tests/AltchaTest.php
```

## License

MIT
