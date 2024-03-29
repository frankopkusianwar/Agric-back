<?php

/**
 * Libsodium compatibility layer
 *
 * This is the only class you should be interfacing with, as a user of
 * sodium_compat.
 *
 * If the PHP extension for libsodium is installed, it will always use that
 * instead of our implementations. You get better performance and stronger
 * guarantees against side-channels that way.
 *
 * However, if your users don't have the PHP extension installed, we offer a
 * compatible interface here. It will give you the correct results as if the
 * PHP extension was installed. It won't be as fast, of course.
 *
 * CAUTION * CAUTION * CAUTION * CAUTION * CAUTION * CAUTION * CAUTION * CAUTION *
 *                                                                               *
 *     Until audited, this is probably not safe to use! DANGER WILL ROBINSON     *
 *                                                                               *
 * CAUTION * CAUTION * CAUTION * CAUTION * CAUTION * CAUTION * CAUTION * CAUTION *
 */

if (!class_exists('ParagonIE_Sodium_Compat', false)) {
    class ParagonIE_Sodium_Compat
    {
        /**
         * This parameter prevents the use of the PECL extension.
         * It should only be used for unit testing.
         *
         * @var bool
         */
        public static $disableFallbackForUnitTests = false;

        /**
         * Use fast multiplication rather than our constant-time multiplication
         * implementation. Can be enabled at runtime. Only enable this if you
         * are absolutely certain that there is no timing leak on your platform.
         *
         * @var bool
         */
        public static $fastMult = false;

        const LIBRARY_VERSION_MAJOR = 9;
        const LIBRARY_VERSION_MINOR = 1;
        const VERSION_STRING = 'polyfill-1.0.8';

        // From libsodium
        const CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES = 32;
        const CRYPTO_AEAD_CHACHA20POLY1305_NSECBYTES = 0;
        const CRYPTO_AEAD_CHACHA20POLY1305_NPUBBYTES = 8;
        const CRYPTO_AEAD_CHACHA20POLY1305_ABYTES = 16;
        const CRYPTO_AEAD_CHACHA20POLY1305_IETF_KEYBYTES = 32;
        const CRYPTO_AEAD_CHACHA20POLY1305_IETF_NSECBYTES = 0;
        const CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES = 12;
        const CRYPTO_AEAD_CHACHA20POLY1305_IETF_ABYTES = 16;
        const CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES = 32;
        const CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NSECBYTES = 0;
        const CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES = 24;
        const CRYPTO_AEAD_XCHACHA20POLY1305_IETF_ABYTES = 16;
        const CRYPTO_AUTH_BYTES = 32;
        const CRYPTO_AUTH_KEYBYTES = 32;
        const CRYPTO_BOX_SEALBYTES = 16;
        const CRYPTO_BOX_SECRETKEYBYTES = 32;
        const CRYPTO_BOX_PUBLICKEYBYTES = 32;
        const CRYPTO_BOX_KEYPAIRBYTES = 64;
        const CRYPTO_BOX_MACBYTES = 16;
        const CRYPTO_BOX_NONCEBYTES = 24;
        const CRYPTO_BOX_SEEDBYTES = 32;
        const CRYPTO_KX_BYTES = 32;
        const CRYPTO_KX_PUBLICKEYBYTES = 32;
        const CRYPTO_KX_SECRETKEYBYTES = 32;
        const CRYPTO_GENERICHASH_BYTES = 32;
        const CRYPTO_GENERICHASH_BYTES_MIN = 16;
        const CRYPTO_GENERICHASH_BYTES_MAX = 64;
        const CRYPTO_GENERICHASH_KEYBYTES = 32;
        const CRYPTO_GENERICHASH_KEYBYTES_MIN = 16;
        const CRYPTO_GENERICHASH_KEYBYTES_MAX = 64;
        const CRYPTO_SCALARMULT_BYTES = 32;
        const CRYPTO_SCALARMULT_SCALARBYTES = 32;
        const CRYPTO_SHORTHASH_BYTES = 8;
        const CRYPTO_SHORTHASH_KEYBYTES = 16;
        const CRYPTO_SECRETBOX_KEYBYTES = 32;
        const CRYPTO_SECRETBOX_MACBYTES = 16;
        const CRYPTO_SECRETBOX_NONCEBYTES = 24;
        const CRYPTO_SIGN_BYTES = 64;
        const CRYPTO_SIGN_SEEDBYTES = 32;
        const CRYPTO_SIGN_PUBLICKEYBYTES = 32;
        const CRYPTO_SIGN_SECRETKEYBYTES = 64;
        const CRYPTO_SIGN_KEYPAIRBYTES = 96;
        const CRYPTO_STREAM_KEYBYTES = 32;
        const CRYPTO_STREAM_NONCEBYTES = 24;

        /**
         * Cache-timing-safe implementation of bin2hex().
         *
         * @param string $string A string (probably raw binary)
         * @return string        A hexadecimal-encoded string
         * @throws TypeError
         */
        public static function bin2hex($string)
        {
            if (!is_string($string)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($string) . ' given.');
            }
            if (self::isPhp72OrGreater()) {
                return bin2hex($string);
            }
            if (self::use_fallback('bin2hex')) {
                return call_user_func('\\Sodium\\bin2hex', $string);
            }
            return ParagonIE_Sodium_Core_Util::bin2hex($string);
        }

        /**
         * Compare two strings, in constant-time.
         * Compared to memcmp(), compare() is more useful for sorting.
         *
         * @param string $left The left operand; must be a string
         * @param string $right The right operand; must be a string
         * @return int          < 0 if the left operand is less than the right
         *                      0 if both strings are equal
         *                      > 0 if the right operand is less than the left
         * @throws TypeError
         */
        public static function compare($left, $right)
        {
            if (!is_string($left)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($left) . ' given.');
            }
            if (!is_string($right)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($right) . ' given.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_compare($left, $right);
            }
            if (self::use_fallback('compare')) {
                return call_user_func('\\Sodium\\compare', $left, $right);
            }
            return ParagonIE_Sodium_Core_Util::compare($left, $right);
        }

        /**
         * Authenticated Encryption with Associated Data: Decryption
         *
         * Algorithm:
         *     ChaCha20-Poly1305
         *
         * This mode uses a 64-bit random nonce with a 64-bit counter.
         * IETF mode uses a 96-bit random nonce with a 32-bit counter.
         *
         * @param string $ciphertext Encrypted message (with Poly1305 MAC appended)
         * @param string $assocData Authenticated Associated Data (unencrypted)
         * @param string $nonce Number to be used only Once; must be 8 bytes
         * @param string $key Encryption key
         *
         * @return string            The original plaintext message
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_aead_chacha20poly1305_decrypt(
            $ciphertext = '',
            $assocData = '',
            $nonce = '',
            $key = ''
        ) {
            if (!is_string($ciphertext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($ciphertext) . ' given.');
            }
            if (!is_string($assocData)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($assocData) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 4 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_AEAD_CHACHA20POLY1305_NPUBBYTES) {
                throw new Error('Nonce must be CRYPTO_AEAD_CHACHA20POLY1305_NPUBBYTES long');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES) {
                throw new Error('Key must be CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES long');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($ciphertext) < self::CRYPTO_AEAD_CHACHA20POLY1305_ABYTES) {
                throw new Error('Message must be at least CRYPTO_AEAD_CHACHA20POLY1305_ABYTES long');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_aead_chacha20poly1305_decrypt(
                    $ciphertext,
                    $assocData,
                    $nonce,
                    $key
                );
            }
            if (self::use_fallback('crypto_aead_chacha20poly1305_decrypt')) {
                return call_user_func(
                    '\\Sodium\\crypto_aead_chacha20poly1305_decrypt',
                    $ciphertext,
                    $assocData,
                    $nonce,
                    $key
                );
            }
            return ParagonIE_Sodium_Crypto::aead_chacha20poly1305_decrypt(
                $ciphertext,
                $assocData,
                $nonce,
                $key
            );
        }

        /**
         * Authenticated Encryption with Associated Data
         *
         * Algorithm:
         *     ChaCha20-Poly1305
         *
         * This mode uses a 64-bit random nonce with a 64-bit counter.
         * IETF mode uses a 96-bit random nonce with a 32-bit counter.
         *
         * @param string $plaintext Message to be encrypted
         * @param string $assocData Authenticated Associated Data (unencrypted)
         * @param string $nonce Number to be used only Once; must be 8 bytes
         * @param string $key Encryption key
         *
         * @return string           Ciphertext with a 16-byte Poly1305 message
         *                          authentication code appended
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_aead_chacha20poly1305_encrypt(
            $plaintext = '',
            $assocData = '',
            $nonce = '',
            $key = ''
        ) {
            if (!is_string($plaintext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($plaintext) . ' given.');
            }
            if (!is_string($assocData)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($assocData) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 4 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_AEAD_CHACHA20POLY1305_NPUBBYTES) {
                throw new Error('Nonce must be CRYPTO_AEAD_CHACHA20POLY1305_NPUBBYTES long');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES) {
                throw new Error('Key must be CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES long');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_aead_chacha20poly1305_encrypt(
                    $plaintext,
                    $assocData,
                    $nonce,
                    $key
                );
            }
            if (self::use_fallback('crypto_aead_chacha20poly1305_encrypt')) {
                return call_user_func(
                    '\\Sodium\\crypto_aead_chacha20poly1305_encrypt',
                    $plaintext,
                    $assocData,
                    $nonce,
                    $key
                );
            }
            return ParagonIE_Sodium_Crypto::aead_chacha20poly1305_encrypt(
                $plaintext,
                $assocData,
                $nonce,
                $key
            );
        }

        /**
         * Authenticated Encryption with Associated Data: Decryption
         *
         * Algorithm:
         *     ChaCha20-Poly1305
         *
         * IETF mode uses a 96-bit random nonce with a 32-bit counter.
         * Regular mode uses a 64-bit random nonce with a 64-bit counter.
         *
         * @param string $ciphertext Encrypted message (with Poly1305 MAC appended)
         * @param string $assocData Authenticated Associated Data (unencrypted)
         * @param string $nonce Number to be used only Once; must be 12 bytes
         * @param string $key Encryption key
         *
         * @return string            The original plaintext message
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_aead_chacha20poly1305_ietf_decrypt(
            $ciphertext = '',
            $assocData = '',
            $nonce = '',
            $key = ''
        ) {
            if (!is_string($ciphertext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($ciphertext) . ' given.');
            }
            if (!is_string($assocData)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($assocData) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 4 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES) {
                throw new Error('Nonce must be CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES long');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES) {
                throw new Error('Key must be CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES long');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($ciphertext) < self::CRYPTO_AEAD_CHACHA20POLY1305_ABYTES) {
                throw new Error('Message must be at least CRYPTO_AEAD_CHACHA20POLY1305_ABYTES long');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_aead_chacha20poly1305_ietf_decrypt(
                    $ciphertext,
                    $assocData,
                    $nonce,
                    $key
                );
            }
            if (self::use_fallback('crypto_aead_chacha20poly1305_ietf_decrypt')) {
                return call_user_func(
                    '\\Sodium\\crypto_aead_chacha20poly1305_ietf_decrypt',
                    $ciphertext,
                    $assocData,
                    $nonce,
                    $key
                );
            }
            return ParagonIE_Sodium_Crypto::aead_chacha20poly1305_ietf_decrypt(
                $ciphertext,
                $assocData,
                $nonce,
                $key
            );
        }

        /**
         * Authenticated Encryption with Associated Data
         *
         * Algorithm:
         *     ChaCha20-Poly1305
         *
         * IETF mode uses a 96-bit random nonce with a 32-bit counter.
         * Regular mode uses a 64-bit random nonce with a 64-bit counter.
         *
         * @param string $plaintext Message to be encrypted
         * @param string $assocData Authenticated Associated Data (unencrypted)
         * @param string $nonce Number to be used only Once; must be 8 bytes
         * @param string $key Encryption key
         *
         * @return string           Ciphertext with a 16-byte Poly1305 message
         *                          authentication code appended
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_aead_chacha20poly1305_ietf_encrypt(
            $plaintext = '',
            $assocData = '',
            $nonce = '',
            $key = ''
        ) {
            if (!is_string($plaintext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($plaintext) . ' given.');
            }
            if (!is_string($assocData)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($assocData) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 4 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES) {
                throw new Error('Nonce must be CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES long');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES) {
                throw new Error('Key must be CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES long');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_aead_chacha20poly1305_ietf_encrypt(
                    $plaintext,
                    $assocData,
                    $nonce,
                    $key
                );
            }
            if (self::use_fallback('crypto_aead_chacha20poly1305_ietf_encrypt')) {
                return call_user_func(
                    '\\Sodium\\crypto_aead_chacha20poly1305_ietf_encrypt',
                    $plaintext,
                    $assocData,
                    $nonce,
                    $key
                );
            }
            return ParagonIE_Sodium_Crypto::aead_chacha20poly1305_ietf_encrypt(
                $plaintext,
                $assocData,
                $nonce,
                $key
            );
        }

        /**
         * Authenticated Encryption with Associated Data: Decryption
         *
         * Algorithm:
         *     XChaCha20-Poly1305
         *
         * This mode uses a 64-bit random nonce with a 64-bit counter.
         * IETF mode uses a 96-bit random nonce with a 32-bit counter.
         *
         * @param string $ciphertext Encrypted message (with Poly1305 MAC appended)
         * @param string $assocData Authenticated Associated Data (unencrypted)
         * @param string $nonce Number to be used only Once; must be 8 bytes
         * @param string $key Encryption key
         *
         * @return string            The original plaintext message
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_aead_xchacha20poly1305_ietf_decrypt(
            $ciphertext = '',
            $assocData = '',
            $nonce = '',
            $key = ''
        ) {
            if (!is_string($ciphertext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($ciphertext) . ' given.');
            }
            if (!is_string($assocData)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($assocData) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 4 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES) {
                throw new Error('Nonce must be CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES long');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES) {
                throw new Error('Key must be CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES long');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($ciphertext) < self::CRYPTO_AEAD_XCHACHA20POLY1305_IETF_ABYTES) {
                throw new Error('Message must be at least CRYPTO_AEAD_XCHACHA20POLY1305_IETF_ABYTES long');
            }
            return ParagonIE_Sodium_Crypto::aead_xchacha20poly1305_ietf_decrypt(
                $ciphertext,
                $assocData,
                $nonce,
                $key
            );
        }

        /**
         * Authenticated Encryption with Associated Data
         *
         * Algorithm:
         *     XChaCha20-Poly1305
         *
         * This mode uses a 64-bit random nonce with a 64-bit counter.
         * IETF mode uses a 96-bit random nonce with a 32-bit counter.
         *
         * @param string $plaintext Message to be encrypted
         * @param string $assocData Authenticated Associated Data (unencrypted)
         * @param string $nonce Number to be used only Once; must be 8 bytes
         * @param string $key Encryption key
         *
         * @return string           Ciphertext with a 16-byte Poly1305 message
         *                          authentication code appended
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_aead_xchacha20poly1305_ietf_encrypt(
            $plaintext = '',
            $assocData = '',
            $nonce = '',
            $key = ''
        ) {
            if (!is_string($plaintext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($plaintext) . ' given.');
            }
            if (!is_string($assocData)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($assocData) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 4 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES) {
                throw new Error('Nonce must be CRYPTO_AEAD_XCHACHA20POLY1305_NPUBBYTES long');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES) {
                throw new Error('Key must be CRYPTO_AEAD_XCHACHA20POLY1305_KEYBYTES long');
            }
            return ParagonIE_Sodium_Crypto::aead_xchacha20poly1305_ietf_encrypt(
                $plaintext,
                $assocData,
                $nonce,
                $key
            );
        }

        /**
         * Authenticate a message. Uses symmetric-key cryptography.
         *
         * Algorithm:
         *     HMAC-SHA512-256. Which is HMAC-SHA-512 truncated to 256 bits.
         *     Not to be confused with HMAC-SHA-512/256 which would use the
         *     SHA-512/256 hash function (uses different initial parameters
         *     but still truncates to 256 bits to sidestep length-extension
         *     attacks).
         *
         * @param string $message Message to be authenticated
         * @param string $key Symmetric authentication key
         * @return string         Message authentication code
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_auth($message, $key)
        {
            if (!is_string($message)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($message) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_AUTH_KEYBYTES) {
                throw new Error('Argument 2 must be CRYPTO_AUTH_KEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_auth($message, $key);
            }
            if (self::use_fallback('crypto_auth')) {
                return call_user_func('\\Sodium\\crypto_auth', $message, $key);
            }
            return ParagonIE_Sodium_Crypto::auth($message, $key);
        }

        /**
         * Verify the MAC of a message previously authenticated with crypto_auth.
         *
         * @param string $mac Message authentication code
         * @param string $message Message whose authenticity you are attempting to
         *                        verify (with a given MAC and key)
         * @param string $key Symmetric authentication key
         * @return bool           TRUE if authenticated, FALSE otherwise
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_auth_verify($mac, $message, $key)
        {
            if (!is_string($mac)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($mac) . ' given.');
            }
            if (!is_string($message)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($message) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($mac) !== self::CRYPTO_AUTH_BYTES) {
                throw new Error('Argument 1 must be CRYPTO_AUTH_BYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_AUTH_KEYBYTES) {
                throw new Error('Argument 3 must be CRYPTO_AUTH_KEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_auth_verify($mac, $message, $key);
            }
            if (self::use_fallback('crypto_auth_verify')) {
                return call_user_func('\\Sodium\\crypto_auth_verify', $mac, $message, $key);
            }
            return ParagonIE_Sodium_Crypto::auth_verify($mac, $message, $key);
        }

        /**
         * Authenticated asymmetric-key encryption. Both the sender and recipient
         * may decrypt messages.
         *
         * Algorithm: X25519-Xsalsa20-Poly1305.
         *     X25519: Elliptic-Curve Diffie Hellman over Curve25519.
         *     Xsalsa20: Extended-nonce variant of salsa20.
         *     Poyl1305: Polynomial MAC for one-time message authentication.
         *
         * @param string $plaintext The message to be encrypted
         * @param string $nonce A Number to only be used Once; must be 24 bytes
         * @param string $keypair Your secret key and your recipient's public key
         * @return string           Ciphertext with 16-byte Poly1305 MAC
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_box($plaintext, $nonce, $keypair)
        {
            if (!is_string($plaintext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($plaintext) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($keypair)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($keypair) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_BOX_NONCEBYTES) {
                throw new Error('Argument 2 must be CRYPTO_BOX_NONCEBYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($keypair) !== self::CRYPTO_BOX_KEYPAIRBYTES) {
                throw new Error('Argument 3 must be CRYPTO_BOX_KEYPAIRBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_box($plaintext, $nonce, $keypair);
            }
            if (self::use_fallback('crypto_box')) {
                return call_user_func('\\Sodium\\crypto_box', $plaintext, $nonce, $keypair);
            }
            return ParagonIE_Sodium_Crypto::box($plaintext, $nonce, $keypair);
        }

        /**
         * Anonymous public-key encryption. Only the recipient may decrypt messages.
         *
         * Algorithm: X25519-Xsalsa20-Poly1305, as with crypto_box.
         *     The sender's X25519 keypair is ephemeral.
         *     Nonce is generated from the BLAKE2b hash of both public keys.
         *
         * This provides ciphertext integrity.
         *
         * @param string $plaintext Message to be sealed
         * @param string $publicKey Your recipient's public key
         * @return string           Sealed message that only your recipient can
         *                          decrypt
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_box_seal($plaintext, $publicKey)
        {
            if (!is_string($plaintext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($plaintext) . ' given.');
            }
            if (!is_string($publicKey)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($publicKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($publicKey) !== self::CRYPTO_BOX_PUBLICKEYBYTES) {
                throw new Error('Argument 2 must be CRYPTO_BOX_PUBLICKEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_box_seal($plaintext, $publicKey);
            }
            if (self::use_fallback('crypto_box_seal')) {
                return call_user_func('\\Sodium\\crypto_box_seal', $plaintext, $publicKey);
            }
            return ParagonIE_Sodium_Crypto::box_seal($plaintext, $publicKey);
        }

        /**
         * Opens a message encrypted with crypto_box_seal(). Requires
         * the recipient's keypair (sk || pk) to decrypt successfully.
         *
         * This validates ciphertext integrity.
         *
         * @param string $ciphertext Sealed message to be opened
         * @param string $keypair Your crypto_box keypair
         * @return string            The original plaintext message
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_box_seal_open($ciphertext, $keypair)
        {
            if (!is_string($ciphertext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($ciphertext) . ' given.');
            }
            if (!is_string($keypair)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($keypair) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($keypair) !== self::CRYPTO_BOX_KEYPAIRBYTES) {
                throw new Error('Argument 2 must be CRYPTO_BOX_KEYPAIRBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_box_seal_open($ciphertext, $keypair);
            }
            if (self::use_fallback('crypto_box_seal_open')) {
                return call_user_func('\\Sodium\\crypto_box_seal_open', $ciphertext, $keypair);
            }
            return ParagonIE_Sodium_Crypto::box_seal_open($ciphertext, $keypair);
        }

        /**
         * Generate a new random X25519 keypair.
         *
         * @return string A 64-byte string; the first 32 are your secret key, while
         *                the last 32 are your public key. crypto_box_secretkey()
         *                and crypto_box_publickey() exist to separate them so you
         *                don't accidentally get them mixed up!
         */
        public static function crypto_box_keypair()
        {
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_box_keypair();
            }
            if (self::use_fallback('crypto_sign_keypair')) {
                return call_user_func('\\Sodium\\crypto_box_keypair');
            }
            return ParagonIE_Sodium_Crypto::box_keypair();
        }

        /**
         * Combine two keys into a keypair for use in library methods that expect
         * a keypair. This doesn't necessarily have to be the same person's keys.
         *
         * @param string $secretKey Secret key
         * @param string $publicKey Public key
         * @return string    Keypair
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_box_keypair_from_secretkey_and_publickey($secretKey, $publicKey)
        {
            if (!is_string($secretKey)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($secretKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($secretKey) !== self::CRYPTO_BOX_SECRETKEYBYTES) {
                throw new Error('Argument 1 must be CRYPTO_BOX_SECRETKEYBYTES long.');
            }
            if (!is_string($publicKey)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($publicKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($publicKey) !== self::CRYPTO_BOX_PUBLICKEYBYTES) {
                throw new Error('Argument 2 must be CRYPTO_BOX_PUBLICKEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_box_keypair_from_secretkey_and_publickey($secretKey, $publicKey);
            }
            if (self::use_fallback('box_keypair_from_secretkey_and_publickey')) {
                return call_user_func('\\Sodium\\box_keypair_from_secretkey_and_publickey', $secretKey, $publicKey);
            }
            return ParagonIE_Sodium_Crypto::box_keypair_from_secretkey_and_publickey($secretKey, $publicKey);
        }

        /**
         * Decrypt a message previously encrypted with crypto_box().
         *
         * @param string $ciphertext Encrypted message
         * @param string $nonce      Number to only be used Once; must be 24 bytes
         * @param string $keypair    Your secret key and the sender's public key
         * @return string            The original plaintext message
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_box_open($ciphertext, $nonce, $keypair)
        {
            if (!is_string($ciphertext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($ciphertext) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($keypair)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($keypair) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($ciphertext) < self::CRYPTO_BOX_MACBYTES) {
                throw new Error('Argument 1 must be at least CRYPTO_BOX_MACBYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_BOX_NONCEBYTES) {
                throw new Error('Argument 2 must be CRYPTO_BOX_NONCEBYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($keypair) !== self::CRYPTO_BOX_KEYPAIRBYTES) {
                throw new Error('Argument 3 must be CRYPTO_BOX_KEYPAIRBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_box_open($ciphertext, $nonce, $keypair);
            }
            if (self::use_fallback('crypto_box_open')) {
                return call_user_func('\\Sodium\\crypto_box_open', $ciphertext, $nonce, $keypair);
            }
            return ParagonIE_Sodium_Crypto::box_open($ciphertext, $nonce, $keypair);
        }

        /**
         * Extract the public key from a crypto_box keypair.
         *
         * @param string $keypair
         * @return string         Your crypto_box public key
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_box_publickey($keypair)
        {
            if (!is_string($keypair)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($keypair) . ' given.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_box_publickey($keypair);
            }
            if (ParagonIE_Sodium_Core_Util::strlen($keypair) !== self::CRYPTO_BOX_KEYPAIRBYTES) {
                throw new Error('Argument 1 must be CRYPTO_BOX_KEYPAIRBYTES long.');
            }
            if (self::use_fallback('crypto_box_publickey')) {
                return call_user_func('\\Sodium\\crypto_box_publickey', $keypair);
            }
            return ParagonIE_Sodium_Crypto::box_publickey($keypair);
        }

        /**
         * Calculate the X25519 public key from a given X25519 secret key.
         *
         * @param string $secretKey Any X25519 secret key
         * @return string      The corresponding X25519 public key
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_box_publickey_from_secretkey($secretKey)
        {
            if (!is_string($secretKey)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($secretKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($secretKey) !== self::CRYPTO_BOX_SECRETKEYBYTES) {
                throw new Error('Argument 1 must be CRYPTO_BOX_SECRETKEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_box_publickey_from_secretkey($secretKey);
            }
            if (self::use_fallback('crypto_box_publickey_from_secretkey')) {
                return call_user_func('\\Sodium\\crypto_box_publickey_from_secretkey', $secretKey);
            }
            return ParagonIE_Sodium_Crypto::box_publickey_from_secretkey($secretKey);
        }

        /**
         * Extract the secret key from a crypto_box keypair.
         *
         * @param string $keypair
         * @return string         Your crypto_box secret key
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_box_secretkey($keypair)
        {
            if (!is_string($keypair)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($keypair) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($keypair) !== self::CRYPTO_BOX_KEYPAIRBYTES) {
                throw new Error('Argument 1 must be CRYPTO_BOX_KEYPAIRBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_box_secretkey($keypair);
            }
            if (self::use_fallback('crypto_box_secretkey')) {
                return call_user_func('\\Sodium\\crypto_box_secretkey', $keypair);
            }
            return ParagonIE_Sodium_Crypto::box_secretkey($keypair);
        }

        /**
         * Calculates a BLAKE2b hash, with an optional key.
         *
         * @param string $message The message to be hashed
         * @param string $key If specified, must be a string between 16 and 64
         *                        bytes long
         * @param int $length Output length in bytes; must be between 16 and 64
         *                        (default = 32)
         * @return string         Raw binary
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_generichash($message, $key = '', $length = self::CRYPTO_GENERICHASH_BYTES)
        {
            if (!is_string($message)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($message) . ' given.');
            }
            if (!is_string($key)) {
                if ($key === null) {
                    $key = '';
                } else {
                    throw new TypeError('Argument 2 must be a string, ' . gettype($key) . ' given.');
                }
            }
            if (!is_int($length)) {
                if (is_numeric($length)) {
                    $length = (int) $length;
                } else {
                    throw new TypeError('Argument 3 must be an integer, ' . gettype($length) . ' given.');
                }
            }
            if (!empty($key)) {
                if (ParagonIE_Sodium_Core_Util::strlen($key) < self::CRYPTO_GENERICHASH_KEYBYTES_MIN) {
                    throw new Error('Unsupported key size. Must be at least CRYPTO_GENERICHASH_KEYBYTES_MIN bytes long.');
                }
                if (ParagonIE_Sodium_Core_Util::strlen($key) > self::CRYPTO_GENERICHASH_KEYBYTES_MAX) {
                    throw new Error('Unsupported key size. Must be at most CRYPTO_GENERICHASH_KEYBYTES_MAX bytes long.');
                }
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_generichash($message, $key, $length);
            }
            if (self::use_fallback('crypto_generichash')) {
                return call_user_func('\\Sodium\\crypto_generichash', $message, $key, $length);
            }
            return ParagonIE_Sodium_Crypto::generichash($message, $key, $length);
        }

        /**
         * Get the final BLAKE2b hash output for a given context.
         *
         * @param string &$ctx BLAKE2 hashing context. Generated by crypto_generichash_init().
         * @param int $length Hash output size.
         * @return string      Final BLAKE2b hash.
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_generichash_final(&$ctx, $length = self::CRYPTO_GENERICHASH_BYTES)
        {
            if (!is_string($ctx)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($ctx) . ' given.');
            }
            if (!is_int($length)) {
                if (is_numeric($length)) {
                    $length = (int)$length;
                } else {
                    throw new TypeError('Argument 2 must be an integer, ' . gettype($length) . ' given.');
                }
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_generichash_final($ctx, $length);
            }
            if (self::use_fallback('crypto_generichash_final')) {
                $func = '\\Sodium\\crypto_generichash_final';
                return $func($ctx, $length);
            }
            $result = ParagonIE_Sodium_Crypto::generichash_final($ctx, $length);
            try {
                self::memzero($ctx);
            } catch (Error $ex) {
                unset($ctx);
            }
            return $result;
        }

        /**
         * Initialize a BLAKE2b hashing context, for use in a streaming interface.
         *
         * @param string $key If specified must be a string between 16 and 64 bytes
         * @param int $length The size of the desired hash output
         * @return string     A BLAKE2 hashing context, encoded as a string
         *                    (To be 100% compatible with ext/libsodium)
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_generichash_init($key = '', $length = self::CRYPTO_GENERICHASH_BYTES)
        {
            if (!is_string($key)) {
                if ($key === null) {
                    $key = '';
                } else {
                    throw new TypeError('Argument 1 must be a string, ' . gettype($key) . ' given.');
                }
            }
            if (!is_int($length)) {
                if (is_numeric($length)) {
                    $length = (int) $length;
                } else {
                    throw new TypeError('Argument 2 must be an integer, ' . gettype($length) . ' given.');
                }
            }
            if (!empty($key)) {
                if (ParagonIE_Sodium_Core_Util::strlen($key) < self::CRYPTO_GENERICHASH_KEYBYTES_MIN) {
                    throw new Error('Unsupported key size. Must be at least CRYPTO_GENERICHASH_KEYBYTES_MIN bytes long.');
                }
                if (ParagonIE_Sodium_Core_Util::strlen($key) > self::CRYPTO_GENERICHASH_KEYBYTES_MAX) {
                    throw new Error('Unsupported key size. Must be at most CRYPTO_GENERICHASH_KEYBYTES_MAX bytes long.');
                }
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_generichash_init($key, $length);
            }
            if (self::use_fallback('crypto_generichash_init')) {
                return call_user_func('\\Sodium\\crypto_generichash_init', $key, $length);
            }
            return ParagonIE_Sodium_Crypto::generichash_init($key, $length);
        }

        /**
         * Update a BLAKE2b hashing context with additional data.
         *
         * @param string &$ctx BLAKE2 hashing context. Generated by crypto_generichash_init().
         *                        $ctx is passed by reference and gets updated in-place.
         * @param string $message The message to append to the existing hash state.
         * @return void
         * @throws TypeError
         */
        public static function crypto_generichash_update(&$ctx, $message)
        {
            if (!is_string($ctx)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($ctx) . ' given.');
            }
            if (!is_string($message)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($message) . ' given.');
            }
            if (self::isPhp72OrGreater()) {
                sodium_crypto_generichash_update($ctx, $message);
                return;
            }
            if (self::use_fallback('crypto_generichash_update')) {
                $func = '\\Sodium\\crypto_generichash_update';
                $func($ctx, $message);
                return;
            }
            $ctx = ParagonIE_Sodium_Crypto::generichash_update($ctx, $message);
        }

        /**
         * Perform a key exchange, between a designated client and a server.
         *
         * Typically, you would designate one machine to be the client and the
         * other to be the server. The first two keys are what you'd expect for
         * scalarmult() below, but the latter two public keys don't swap places.
         *
         * | ALICE                          | BOB                                 |
         * | Client                         | Server                              |
         * |--------------------------------|-------------------------------------|
         * | shared = crypto_kx(            | shared = crypto_kx(                 |
         * |     alice_sk,                  |     bob_sk,                         | <- contextual
         * |     bob_pk,                    |     alice_pk,                       | <- contextual
         * |     alice_pk,                  |     alice_pk,                       | <----- static
         * |     bob_pk                     |     bob_pk                          | <----- static
         * | )                              | )                                   |
         *
         * They are used along with the scalarmult product to generate a 256-bit
         * BLAKE2b hash unique to the client and server keys.
         *
         * @param string $my_secret
         * @param string $their_public
         * @param string $client_public
         * @param string $server_public
         * @return string
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_kx($my_secret, $their_public, $client_public, $server_public)
        {
            if (!is_string($my_secret)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($my_secret) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($my_secret) !== self::CRYPTO_BOX_SECRETKEYBYTES) {
                throw new Error('Argument 1 must be CRYPTO_BOX_SECRETKEYBYTES long.');
            }
            if (!is_string($their_public)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($their_public) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($their_public) !== self::CRYPTO_BOX_PUBLICKEYBYTES) {
                throw new Error('Argument 2 must be CRYPTO_BOX_PUBLICKEYBYTES long.');
            }
            if (!is_string($client_public)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($client_public) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($client_public) !== self::CRYPTO_BOX_PUBLICKEYBYTES) {
                throw new Error('Argument 3 must be CRYPTO_BOX_PUBLICKEYBYTES long.');
            }
            if (!is_string($server_public)) {
                throw new TypeError('Argument 4 must be a string, ' . gettype($server_public) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($server_public) !== self::CRYPTO_BOX_PUBLICKEYBYTES) {
                throw new Error('Argument 4 must be CRYPTO_BOX_PUBLICKEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_kx(
                    $my_secret,
                    $their_public,
                    $client_public,
                    $server_public
                );
            }
            if (self::use_fallback('crypto_kx')) {
                return call_user_func(
                    '\\Sodium\\crypto_kx',
                    $my_secret,
                    $their_public,
                    $client_public,
                    $server_public
                );
            }
            return ParagonIE_Sodium_Crypto::keyExchange(
                $my_secret,
                $their_public,
                $client_public,
                $server_public
            );
        }

        /**
         * Calculate the shared secret between your secret key and your
         * recipient's public key.
         *
         * Algorithm: X25519 (ECDH over Curve25519)
         *
         * @param string $secretKey
         * @param string $publicKey
         * @return string
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_scalarmult($secretKey, $publicKey)
        {
            if (!is_string($secretKey)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($secretKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($secretKey) !== self::CRYPTO_BOX_SECRETKEYBYTES) {
                throw new Error('Argument 1 must be CRYPTO_BOX_SECRETKEYBYTES long.');
            }
            if (!is_string($publicKey)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($publicKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($publicKey) !== self::CRYPTO_BOX_PUBLICKEYBYTES) {
                throw new Error('Argument 2 must be CRYPTO_BOX_PUBLICKEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_scalarmult($secretKey, $publicKey);
            }
            if (self::use_fallback('crypto_scalarmult')) {
                return call_user_func('\\Sodium\\crypto_scalarmult', $secretKey, $publicKey);
            }
            if (ParagonIE_Sodium_Core_Util::hashEquals($secretKey, str_repeat("\0", self::CRYPTO_BOX_SECRETKEYBYTES))) {
                throw new Error('Zero secret key is not allowed');
            }
            if (ParagonIE_Sodium_Core_Util::hashEquals($publicKey, str_repeat("\0", self::CRYPTO_BOX_PUBLICKEYBYTES))) {
                throw new Error('Zero public key is not allowed');
            }
            return ParagonIE_Sodium_Crypto::scalarmult($secretKey, $publicKey);
        }

        /**
         * Calculate an X25519 public key from an X25519 secret key.
         *
         * @param string $secretKey
         * @return string
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_scalarmult_base($secretKey)
        {
            if (!is_string($secretKey)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($secretKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($secretKey) !== self::CRYPTO_BOX_SECRETKEYBYTES) {
                throw new Error('Argument 1 must be CRYPTO_BOX_SECRETKEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_scalarmult_base($secretKey);
            }
            if (self::use_fallback('crypto_scalarmult_base')) {
                return call_user_func('\\Sodium\\crypto_scalarmult_base', $secretKey);
            }
            if (ParagonIE_Sodium_Core_Util::hashEquals($secretKey, str_repeat("\0", self::CRYPTO_BOX_SECRETKEYBYTES))) {
                throw new Error('Zero secret key is not allowed');
            }
            return ParagonIE_Sodium_Crypto::scalarmult_base($secretKey);
        }

        /**
         * Authenticated symmetric-key encryption.
         *
         * Algorithm: Xsalsa20-Poly1305
         *
         * @param string $plaintext The message you're encrypting
         * @param string $nonce A Number to be used Once; must be 24 bytes
         * @param string $key Symmetric encryption key
         * @return string           Ciphertext with Poly1305 MAC
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_secretbox($plaintext, $nonce, $key)
        {
            if (!is_string($plaintext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($plaintext) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_SECRETBOX_NONCEBYTES) {
                throw new Error('Argument 2 must be CRYPTO_SECRETBOX_NONCEBYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_SECRETBOX_KEYBYTES) {
                throw new Error('Argument 3 must be CRYPTO_SECRETBOX_KEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_secretbox($plaintext, $nonce, $key);
            }
            if (self::use_fallback('crypto_secretbox')) {
                return call_user_func('\\Sodium\\crypto_secretbox', $plaintext, $nonce, $key);
            }
            return ParagonIE_Sodium_Crypto::secretbox($plaintext, $nonce, $key);
        }

        /**
         * Decrypts a message previously encrypted with crypto_secretbox().
         *
         * @param string $ciphertext Ciphertext with Poly1305 MAC
         * @param string $nonce      A Number to be used Once; must be 24 bytes
         * @param string $key        Symmetric encryption key
         * @return string            Original plaintext message
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_secretbox_open($ciphertext, $nonce, $key)
        {
            if (!is_string($ciphertext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($ciphertext) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_SECRETBOX_NONCEBYTES) {
                throw new Error('Argument 2 must be CRYPTO_SECRETBOX_NONCEBYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_SECRETBOX_KEYBYTES) {
                throw new Error('Argument 3 must be CRYPTO_SECRETBOX_KEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
            }
            if (self::use_fallback('crypto_secretbox_open')) {
                return call_user_func('\\Sodium\\crypto_secretbox_open', $ciphertext, $nonce, $key);
            }
            return ParagonIE_Sodium_Crypto::secretbox_open($ciphertext, $nonce, $key);
        }

        /**
         * Authenticated symmetric-key encryption.
         *
         * Algorithm: XChaCha20-Poly1305
         *
         * @param string $plaintext The message you're encrypting
         * @param string $nonce     A Number to be used Once; must be 24 bytes
         * @param string $key       Symmetric encryption key
         * @return string           Ciphertext with Poly1305 MAC
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_secretbox_xchacha20poly1305($plaintext, $nonce, $key)
        {
            if (!is_string($plaintext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($plaintext) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_SECRETBOX_NONCEBYTES) {
                throw new Error('Argument 2 must be CRYPTO_SECRETBOX_NONCEBYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_SECRETBOX_KEYBYTES) {
                throw new Error('Argument 3 must be CRYPTO_SECRETBOX_KEYBYTES long.');
            }
            return ParagonIE_Sodium_Crypto::secretbox_xchacha20poly1305($plaintext, $nonce, $key);
        }
        /**
         * Decrypts a message previously encrypted with crypto_secretbox_xchacha20poly1305().
         *
         * @param string $ciphertext Ciphertext with Poly1305 MAC
         * @param string $nonce      A Number to be used Once; must be 24 bytes
         * @param string $key        Symmetric encryption key
         * @return string            Original plaintext message
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_secretbox_xchacha20poly1305_open($ciphertext, $nonce, $key)
        {
            if (!is_string($ciphertext)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($ciphertext) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_SECRETBOX_NONCEBYTES) {
                throw new Error('Argument 2 must be CRYPTO_SECRETBOX_NONCEBYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_SECRETBOX_KEYBYTES) {
                throw new Error('Argument 3 must be CRYPTO_SECRETBOX_KEYBYTES long.');
            }
            return ParagonIE_Sodium_Crypto::secretbox_xchacha20poly1305_open($ciphertext, $nonce, $key);
        }

        /**
         * Calculates a SipHash-2-4 hash of a message for a given key.
         *
         * @param string $message Input message
         * @param string $key SipHash-2-4 key
         * @return string         Hash
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_shorthash($message, $key)
        {
            if (!is_string($message)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($message) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_SHORTHASH_KEYBYTES) {
                throw new Error('Argument 2 must be CRYPTO_SHORTHASH_KEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_shorthash($message, $key);
            }
            if (self::use_fallback('crypto_shorthash')) {
                return call_user_func('\\Sodium\\crypto_shorthash', $message, $key);
            }
            return ParagonIE_Sodium_Core_SipHash::sipHash24($message, $key);
        }

        /**
         * Expand a key and nonce into a keystream of pseudorandom bytes.
         *
         * @param int $len Number of bytes desired
         * @param string $nonce Number to be used Once; must be 24 bytes
         * @param string $key Xsalsa20 key
         * @return string       Pseudorandom stream that can be XORed with messages
         *                      to provide encryption (but not authentication; see
         *                      Poly1305 or crypto_auth() for that, which is not
         *                      optional for security)
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_stream($len, $nonce, $key)
        {
            if (!is_int($len)) {
                if (is_numeric($len)) {
                    $len = (int) $len;
                } else {
                    throw new TypeError('Argument 1 must be an integer, ' . gettype($len) . ' given.');
                }
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_STREAM_NONCEBYTES) {
                throw new Error('Argument 2 must be CRYPTO_SECRETBOX_NONCEBYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_STREAM_KEYBYTES) {
                throw new Error('Argument 3 must be CRYPTO_STREAM_KEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_stream($len, $nonce, $key);
            }
            if (self::use_fallback('crypto_stream')) {
                return call_user_func('\\Sodium\\crypto_stream', $len, $nonce, $key);
            }
            return ParagonIE_Sodium_Core_Xsalsa20::xsalsa20($len, $nonce, $key);
        }

        /**
         * DANGER! UNAUTHENTICATED ENCRYPTION!
         *
         * Unless you are following expert advice, do not used this feature.
         *
         * Algorithm: Xsalsa20
         *
         * This DOES NOT provide ciphertext integrity.
         *
         * @param string $message Plaintext message
         * @param string $nonce Number to be used Once; must be 24 bytes
         * @param string $key Encryption key
         * @return string         Encrypted text which is vulnerable to chosen-
         *                        ciphertext attacks unless you implement some
         *                        other mitigation to the ciphertext (i.e.
         *                        Encrypt then MAC)
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_stream_xor($message, $nonce, $key)
        {
            if (!is_string($message)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($message) . ' given.');
            }
            if (!is_string($nonce)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($nonce) . ' given.');
            }
            if (!is_string($key)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($key) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($nonce) !== self::CRYPTO_STREAM_NONCEBYTES) {
                throw new Error('Argument 2 must be CRYPTO_SECRETBOX_NONCEBYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($key) !== self::CRYPTO_STREAM_KEYBYTES) {
                throw new Error('Argument 3 must be CRYPTO_SECRETBOX_KEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_stream_xor($message, $nonce, $key);
            }
            if (self::use_fallback('crypto_stream_xor')) {
                return call_user_func('\\Sodium\\crypto_stream_xor', $message, $nonce, $key);
            }
            return ParagonIE_Sodium_Core_Xsalsa20::xsalsa20_xor($message, $nonce, $key);
        }

        /**
         * Returns a signed message. You probably want crypto_sign_detached()
         * instead, which only returns the signature.
         *
         * Algorithm: Ed25519 (EdDSA over Curve25519)
         *
         * @param string $message Message to be signed.
         * @param string $secretKey Secret signing key.
         * @return string           Signed message (signature is prefixed).
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_sign($message, $secretKey)
        {
            if (!is_string($message)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($message) . ' given.');
            }
            if (!is_string($secretKey)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($secretKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($secretKey) !== self::CRYPTO_SIGN_SECRETKEYBYTES) {
                throw new Error('Argument 2 must be CRYPTO_SIGN_SECRETKEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_sign($message, $secretKey);
            }
            if (self::use_fallback('crypto_sign')) {
                return call_user_func('\\Sodium\\crypto_sign', $message, $secretKey);
            }
            return ParagonIE_Sodium_Crypto::sign($message, $secretKey);
        }

        /**
         * Validates a signed message then returns the message.
         *
         * @param string $signedMessage A signed message
         * @param string $publicKey A public key
         * @return string               The original message (if the signature is
         *                              valid for this public key)
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_sign_open($signedMessage, $publicKey)
        {
            if (!is_string($signedMessage)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($signedMessage) . ' given.');
            }
            if (!is_string($publicKey)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($publicKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($signedMessage) < self::CRYPTO_SIGN_BYTES) {
                throw new Error('Argument 1 must be at least CRYPTO_SIGN_BYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($publicKey) !== self::CRYPTO_SIGN_PUBLICKEYBYTES) {
                throw new Error('Argument 2 must be CRYPTO_SIGN_PUBLICKEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_sign_open($signedMessage, $publicKey);
            }
            if (self::use_fallback('crypto_sign_open')) {
                return call_user_func('\\Sodium\\crypto_sign_open', $signedMessage, $publicKey);
            }
            return ParagonIE_Sodium_Crypto::sign_open($signedMessage, $publicKey);
        }

        /**
         * Generate a new random Ed25519 keypair.
         *
         * @return string
         */
        public static function crypto_sign_keypair()
        {
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_sign_keypair();
            }
            if (self::use_fallback('crypto_sign_keypair')) {
                return call_user_func(
                    '\\Sodium\\crypto_sign_keypair'
                );
            }
            return ParagonIE_Sodium_Core_Ed25519::keypair();
        }

        /**
         * Generate an Ed25519 keypair from a seed.
         *
         * @param string $seed Input seed
         * @return string      Keypair
         */
        public static function crypto_sign_seed_keypair($seed)
        {
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_sign_seed_keypair($seed);
            }
            if (self::use_fallback('crypto_sign_keypair')) {
                return call_user_func('\\Sodium\\crypto_sign_seed_keypair', $seed);
            }
            $publicKey = '';
            $secretKey = '';
            ParagonIE_Sodium_Core_Ed25519::seed_keypair($publicKey, $secretKey, $seed);
            return $secretKey . $publicKey;
        }

        /**
         * Extract an Ed25519 public key from an Ed25519 keypair.
         *
         * @param string $keypair Keypair
         * @return string         Public key
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_sign_publickey($keypair)
        {
            if (!is_string($keypair)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($keypair) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($keypair) !== self::CRYPTO_SIGN_KEYPAIRBYTES) {
                throw new Error('Argument 1 must be CRYPTO_SIGN_KEYPAIRBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_sign_publickey($keypair);
            }
            if (self::use_fallback('crypto_sign_publickey')) {
                return call_user_func('\\Sodium\\crypto_sign_publickey', $keypair);
            }
            return ParagonIE_Sodium_Core_Ed25519::publickey($keypair);
        }

        /**
         * Calculate an Ed25519 public key from an Ed25519 secret key.
         *
         * @param string $secretKey Your Ed25519 secret key
         * @return string           The corresponding Ed25519 public key
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_sign_publickey_from_secretkey($secretKey)
        {
            if (!is_string($secretKey)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($secretKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($secretKey) !== self::CRYPTO_SIGN_SECRETKEYBYTES) {
                throw new Error('Argument 1 must be CRYPTO_SIGN_SECRETKEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_sign_publickey_from_secretkey($secretKey);
            }
            if (self::use_fallback('crypto_sign_publickey_from_publickey')) {
                return call_user_func('\\Sodium\\crypto_sign_publickey_from_publickey', $secretKey);
            }
            return ParagonIE_Sodium_Core_Ed25519::publickey_from_secretkey($secretKey);
        }

        /**
         * Extract an Ed25519 secret key from an Ed25519 keypair.
         *
         * @param string $keypair Keypair
         * @return string         Secret key
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_sign_secretkey($keypair)
        {
            if (!is_string($keypair)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($keypair) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($keypair) !== self::CRYPTO_SIGN_KEYPAIRBYTES) {
                throw new Error('Argument 1 must be CRYPTO_SIGN_KEYPAIRBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_sign_secretkey($keypair);
            }
            if (self::use_fallback('crypto_sign_secretkey')) {
                return call_user_func('\\Sodium\\crypto_sign_secretkey', $keypair);
            }
            return ParagonIE_Sodium_Core_Ed25519::secretkey($keypair);
        }

        /**
         * Calculate the Ed25519 signature of a message and return ONLY the signature.
         *
         * Algorithm: Ed25519 (EdDSA over Curve25519)
         *
         * @param string $message Message to be signed
         * @param string $secretKey Secret signing key
         * @return string           Digital signature
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_sign_detached($message, $secretKey)
        {
            if (!is_string($message)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($message) . ' given.');
            }
            if (!is_string($secretKey)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($secretKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($secretKey) !== self::CRYPTO_SIGN_SECRETKEYBYTES) {
                throw new Error('Argument 2 must be CRYPTO_SIGN_SECRETKEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_sign_detached($message, $secretKey);
            }
            if (self::use_fallback('crypto_sign_detached')) {
                return call_user_func('\\Sodium\\crypto_sign_detached', $message, $secretKey);
            }
            return ParagonIE_Sodium_Crypto::sign_detached($message, $secretKey);
        }

        /**
         * Verify the Ed25519 signature of a message.
         *
         * @param string $signature Digital sginature
         * @param string $message Message to be verified
         * @param string $publicKey Public key
         * @return bool             TRUE if this signature is good for this public key;
         *                          FALSE otherwise
         * @throws Error
         * @throws TypeError
         */
        public static function crypto_sign_verify_detached($signature, $message, $publicKey)
        {
            if (!is_string($signature)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($signature) . ' given.');
            }
            if (!is_string($message)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($message) . ' given.');
            }
            if (!is_string($publicKey)) {
                throw new TypeError('Argument 3 must be a string, ' . gettype($publicKey) . ' given.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($signature) !== self::CRYPTO_SIGN_BYTES) {
                throw new Error('Argument 1 must be CRYPTO_SIGN_BYTES long.');
            }
            if (ParagonIE_Sodium_Core_Util::strlen($publicKey) !== self::CRYPTO_SIGN_PUBLICKEYBYTES) {
                throw new Error('Argument 3 must be CRYPTO_SIGN_PUBLICKEYBYTES long.');
            }
            if (self::isPhp72OrGreater()) {
                return sodium_crypto_sign_verify_detached($signature, $message, $publicKey);
            }
            if (self::use_fallback('crypto_sign_verify_detached')) {
                return call_user_func('\\Sodium\\crypto_sign_verify_detached', $signature, $message, $publicKey);
            }
            return ParagonIE_Sodium_Crypto::sign_verify_detached($signature, $message, $publicKey);
        }

        /**
         * Cache-timing-safe implementation of hex2bin().
         *
         * @param string $string Hexadecimal string
         * @return string        Raw binary string
         * @throws TypeError
         */
        public static function hex2bin($string)
        {
            if (!is_string($string)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($string) . ' given.');
            }
            if (self::isPhp72OrGreater()) {
                return self::hex2bin($string);
            }
            if (self::use_fallback('hex2bin')) {
                return call_user_func('\\Sodium\\hex2bin', $string);
            }
            return ParagonIE_Sodium_Core_Util::hex2bin($string);
        }

        /**
         * The equivalent to the libsodium minor version we aim to be compatible
         * with (sans pwhash and memzero).
         *
         * @return int
         */
        public static function library_version_major()
        {
            if (self::use_fallback('library_version_major')) {
                return (int)call_user_func('\\Sodium\\library_version_major');
            }
            return self::LIBRARY_VERSION_MAJOR;
        }

        /**
         * The equivalent to the libsodium minor version we aim to be compatible
         * with (sans pwhash and memzero).
         *
         * @return int
         */
        public static function library_version_minor()
        {
            if (self::use_fallback('library_version_minor')) {
                return (int)call_user_func('\\Sodium\\library_version_minor');
            }
            return self::LIBRARY_VERSION_MINOR;
        }

        /**
         * Compare two strings.
         *
         * @param string $left
         * @param string $right
         * @return int
         * @throws TypeError
         */
        public static function memcmp($left, $right)
        {
            if (!is_string($left)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($left) . ' given.');
            }
            if (!is_string($right)) {
                throw new TypeError('Argument 2 must be a string, ' . gettype($right) . ' given.');
            }
            if (self::use_fallback('memcmp')) {
                return call_user_func('\\Sodium\\memcmp', $left, $right);
            }
            return ParagonIE_Sodium_Core_Util::memcmp($left, $right);
        }

        /**
         * It's actually not possible to zero memory buffers in PHP. You need the
         * native library for that.
         *
         * @param &string $var
         *
         * @return void
         * @throws Error (Unless libsodium is installed)
         */
        public static function memzero(&$var)
        {
            if (!is_string($var)) {
                throw new TypeError('Argument 1 must be a string, ' . gettype($var) . ' given.');
            }
            if (self::isPhp72OrGreater()) {
                sodium_memzero($var);
                return;
            }
            if (self::use_fallback('memzero')) {
                @call_user_func('\\Sodium\\memzero', $var);
                return;
            }
            // This is the best we can do.
            throw new Error(
                'This is not implemented, as it is not possible to securely wipe memory from PHP'
            );
        }

        /**
         * Generate a string of bytes from the kernel's CSPRNG.
         * Proudly uses /dev/urandom (if getrandom(2) is not available).
         *
         * @param int $numBytes
         * @return string
         * @throws TypeError
         */
        public static function randombytes_buf($numBytes)
        {
            if (!is_int($numBytes)) {
                if (is_numeric($numBytes)) {
                    $numBytes = (int)$numBytes;
                } else {
                    throw new TypeError('Argument 1 must be an integer, ' . gettype($numBytes) . ' given.');
                }
            }
            if (self::use_fallback('randombytes_buf')) {
                return call_user_func('\\Sodium\\randombytes_buf', $numBytes);
            }
            return random_bytes($numBytes);
        }

        /**
         * Generate an integer between 0 and $range (non-inclusive).
         *
         * @param int $range
         * @return int
         * @throws TypeError
         */
        public static function randombytes_uniform($range)
        {
            if (!is_int($range)) {
                if (is_numeric($range)) {
                    $range = (int)$range;
                } else {
                    throw new TypeError('Argument 1 must be an integer, ' . gettype($range) . ' given.');
                }
            }
            if (self::use_fallback('randombytes_uniform')) {
                return (int)call_user_func('\\Sodium\\randombytes_uniform', $range);
            }
            return random_int(0, $range - 1);
        }

        /**
         * Generate a random 16-bit integer.
         *
         * @return int
         */
        public static function randombytes_random16()
        {
            if (self::use_fallback('randombytes_random16')) {
                return (int) call_user_func('\\Sodium\\randombytes_random16');
            }
            return random_int(0, 65535);
        }

        /**
         * This emulates libsodium's version_string() function, except ours is
         * prefixed with 'polyfill-'.
         *
         * @return string
         */
        public static function version_string()
        {
            if (self::use_fallback('version_string')) {
                return (string)call_user_func('\\Sodium\\version_string');
            }
            return self::VERSION_STRING;
        }

        /**
         * Should we use the libsodium core function instead?
         * This is always a good idea, if it's available. (Unless we're in the
         * middle of running our unit test suite.)
         *
         * If ext/libsodium is available, use it. Return TRUE.
         * Otherwise, we have to use the code provided herein. Return FALSE.
         *
         * @param string $sodium_func_name
         *
         * @return bool
         */
        protected static function use_fallback($sodium_func_name = '')
        {
            static $res = null;
            if ($res === null) {
                $res = extension_loaded('libsodium') && PHP_VERSION_ID >= 50300;
            }
            if ($res === false) {
                // No libsodium installed
                return false;
            }
            if (self::$disableFallbackForUnitTests) {
                // Don't fallback. Use the PHP implementation.
                return false;
            }
            if (!empty($sodium_func_name)) {
                return is_callable('\\Sodium\\' . $sodium_func_name);
            }
            return true;
        }

        /**
         * Libsodium as implemented in PHP 7.2
         *
         * @ref https://wiki.php.net/rfc/libsodium
         * @return bool
         */
        protected static function isPhp72OrGreater()
        {
            static $res = null;
            if ($res === null) {
                $res = PHP_VERSION_ID >= 70200 && extension_loaded('libsodium');
            }
            if (self::$disableFallbackForUnitTests) {
                // Don't fallback. Use the PHP implementation.
                return false;
            }
            return $res;
        }
    }
}
