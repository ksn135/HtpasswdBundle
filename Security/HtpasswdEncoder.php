<?php

/**
 * This file is part of the Ksn135HtpasswdBundle package.
 *
 * (c) Serg N. Kalachev <serg@kalachev.ru>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ksn135\HtpasswdBundle\Security;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Ksn135\HtpasswdBundle\Services\StringUtils;


class HtpasswdEncoder implements PasswordEncoderInterface
{
    public function encodePassword($raw, $salt)
    {
        return self::crypt_apr1_md5($raw, $salt);
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {

        if ("{SHA}" === substr($encoded, 0, 5)) {
            return $this->comparePasswords($encoded, sprintf("{SHA}%s", base64_encode(sha1($raw, true))));
        } else {
            return $this->comparePasswords($encoded, $this->encodePassword($raw, $salt));
        }

    }

    protected function comparePasswords($password1, $password2)
    {
        return StringUtils::equals($password1, $password2);
    }

    public static function crypt_apr1_md5($password, $salt = null)
    {
        if (!$salt) {
            $salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
        }
        $len = strlen($password);

        $text = $password . '$apr1$' . $salt;

        $bin = pack("H32", md5($password . $salt . $password));

        for ($i = $len; $i > 0; $i -= 16) {
            $text .= substr($bin, 0, min(16, $i));
        }

        for ($i = $len; $i > 0; $i >>= 1) {
            $text .= ($i & 1) ? chr(0) : $password{0};
        }

        $bin = pack("H32", md5($text));

        for ($i = 0; $i < 1000; $i++) {
            $new = ($i & 1) ? $password : $bin;

            if ($i % 3) {
                $new .= $salt;
            }

            if ($i % 7) {
                $new .= $password;
            }

            $new .= ($i & 1) ? $bin : $password;
            $bin = pack("H32", md5($new));
        }

        $tmp = '';

        for ($i = 0; $i < 5; $i++) {
            $k = $i + 6;
            $j = $i + 12;

            if ($j == 16) {
                $j = 5;
            }

            $tmp = $bin[$i] . $bin[$k] . $bin[$j] . $tmp;
        }

        $tmp = chr(0) . chr(0) . $bin[11] . $tmp;
        $tmp = strtr(
            strrev(substr(base64_encode($tmp), 2)),
            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
            "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
        );

        return "$" . "apr1" . "$" . $salt . "$" . $tmp;
    }

}