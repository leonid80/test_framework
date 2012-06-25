<?php

class LibCrypt{
    static $check = false;
    static $iv;
    static $crypt_key;

    static function decrypt ($crypt_data)
    {
        self::cryptEnable();
        if(self::$check) {
            return trim(mcrypt_decrypt(CRYPT_ALG, self::$crypt_key, base64_decode($crypt_data), CRYPT_MODE, self::$iv));
        }
    }

    static function encrypt ($in)
    {
        self::cryptEnable();
        if(self::$check) {
            return base64_encode(mcrypt_encrypt(CRYPT_ALG, self::$crypt_key, $in, CRYPT_MODE, self::$iv));
        }
    }

    static function cryptEnable()
    {
        if(self::$check)
            return;
        @$iv_size = mcrypt_get_iv_size(CRYPT_ALG,CRYPT_MODE);
        self::$iv = substr( md5(self::$crypt_key), 0, $iv_size);
        $algorithms = mcrypt_list_algorithms();
        $modes = mcrypt_list_modes();

        if(in_array(CRYPT_ALG, $algorithms) && in_array(CRYPT_MODE, $modes) && defined('CRYPT_KEY'))
            self::$check = true;
        else
            trigger_error("crypt_error", E_USER_ERROR);

        return self::$check;
    }


}
