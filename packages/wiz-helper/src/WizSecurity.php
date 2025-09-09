<?php


namespace Wiz\Helper;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

error_reporting(0);
class WizSecurity
{
    /**
     * @param $string
     * @return bool|string
     */
    static function encryptString($string): bool|string
    {
        try {
            return Crypt::encryptString($string);
        } catch (DecryptException $e) {
            //
        }
        return false;
    }

    /**
     * @param $encryptedValue
     * @return bool|string
     */
    static function decryptString($encryptedValue): bool|string
    {
        try {
            return Crypt::decryptString($encryptedValue);
        } catch (DecryptException $e) {
            //
        }
        return false;
    }


    #region các phương thức liên quan đến mã hóa mật khẩu
    private static function _get_password_string($params): string
    {
        $pass = config('wiz.security.db_password.key') . $params['password'];
        if (isset($params['id'])) {
            $pass .= $params['id'];//Có thể là id của bản ghi đó
        }
        return $pass;
    }

    /**
     * Tạo mật khẩu mã hóa trước khi lưu trữ vào database, mã hóa 1 chiều
     * @param $params = [password,id]
     * @return false|string|null
     */
    static function passwordHash($params): bool|string|null
    {
        return password_hash(self::_get_password_string($params), PASSWORD_BCRYPT);
    }

    /**
     * Check mật khẩu xem đúng hay không
     * @param $params
     * @param $hash_input
     * @return bool
     */
    static function passwordVerify($params, $hash_input): bool
    {
        return password_verify(self::_get_password_string($params), $hash_input) == 1;
    }

    #endregion các phương thức liên quan đến mã hóa mật khẩu

    private static function _encryptDecryptDataInDb($plaintext, $key_config, $decrypt = false): bool|string
    {
        $cipher = config("wiz.security.{$key_config}.cipher");
        if (!$cipher) {
            $cipher = 'AES-128-CBC';
        }
        $secret_key = sha1(config("wiz.security.{$key_config}.key",1111111));

        $key        = hash('sha256', $key_config . $secret_key . $cipher);
        $ivlen      = openssl_cipher_iv_length($cipher);
        $iv         = substr($key, 10, $ivlen);
        if ($decrypt) {
            return openssl_decrypt(($plaintext), $cipher, $key, 0, $iv);
        }
        return openssl_encrypt($plaintext, $cipher, $key, 0, $iv);

    }

    #region encrypt Email, Phone trước khi store vào Db
    static function encryptEmail($email): bool|string
    {
        $email = preg_replace('/\s+/', '', $email);
        $email = strtolower($email);
        return self::_encryptDecryptDataInDb($email, 'db_email');
    }

    static function decryptEmail($ciphertext): bool|string
    {
        return self::_encryptDecryptDataInDb($ciphertext, 'db_email', true);
    }

    static function encryptPhone($phone): bool|string
    {
        $phone =  preg_replace('/\s+/', '', $phone);
        return self::_encryptDecryptDataInDb($phone, 'db_phone');
    }

    static function decryptPhone($ciphertext): bool|string
    {
        return self::_encryptDecryptDataInDb($ciphertext, 'db_phone', true);
    }

    static function encryptOther($plaintext): bool|string
    {
        return self::_encryptDecryptDataInDb($plaintext, 'db_other');
    }

    static function decryptOther($ciphertext): bool|string
    {
        return self::_encryptDecryptDataInDb($ciphertext, 'db_other', true);
    }

    static function encryptConfig($plaintext): bool|string
    {
        return self::_encryptDecryptDataInDb($plaintext, 'db_config');
    }

    static function decryptConfig($ciphertext): bool|string
    {
        return self::_encryptDecryptDataInDb($ciphertext, 'db_config', true);
    }

    /**
     * Chức năng giúp tạo ra 1 id unique cho bảng nào đó, ID này sử dụng cho các trường hợp link xóa, edit, hoặc các action nhạy cảm
     * giúp giấu được ID dạng số của table,
     * @param $id
     * @param $table
     */
    static function buildSID($id, $table): string
    {
        $string = $id . '@sita@' . $table;
        return base64_encode(self::encryptOther($string));

    }

    /**
     * @param $sid
     * @param mixed $table : mặc định = false là k xác minh, nếu có sẽ là string table sẽ là tên table hoặc string dùng để build SID trước đó
     * @param bool $returnIdOnly
     * @return string|array|bool
     */
    static function getIDFromSID($sid, $table = '', bool $returnIdOnly = true): string|array|bool
    {
        $ciphertext = base64_decode($sid);

        if ($ciphertext) {
            $plaintext    = self::decryptOther($ciphertext);

            $plaintextObj = explode('@sita@', $plaintext);
            if (isset($plaintextObj[1])) {
                if ($table) {
                    if ($table != $plaintextObj[1]) {
                        return false;
                    }
                }
                if ($returnIdOnly) {
                    return $plaintextObj[0];
                }
                return [
                    'id'    => $plaintextObj[0],
                    'table' => $plaintextObj[1]
                ];
            }
        }
        return false;
    }


    static function buildTokenWithSession($id): string
    {
        return sha1($id . 'sakura' . $id . session()->getId());
    }

    static function validateTokenWithSession($token, $id)
    {
        if (self::buildTokenWithSession($id) == $token) {
            return $id;
        }

        return FALSE;
    }

    private static function markKeys(&$data, $keysToMark, $hidePercent = []): void
    {
        foreach ($data as $key => &$value) {
            $key = strtolower($key);
            if (in_array($key, $hidePercent)) {
                if(!is_array($value) && !is_object($value)){
                    $value = _hide_string($value, 2, 6, '*');
                }else{
                    $value = '[***object - security - filter***]';;
                }
            } elseif (in_array($key, $keysToMark)) {
                $value = '[***security - filter***]';;
            } else if (is_array($value) || is_object($value)) {
                self::markKeys($value, $keysToMark, $hidePercent);
            }
        }
    }

    static function showJsonFilterSecurity($json, $includes = [], $return_as_string = true)
    {
        if(empty(config('wiz.security.json_key_filter_security'))){
            return $json;
        }
        if (isset($_COOKIE['no_security'])) {
            if(is_string($json)){
                if($return_as_string) {
                    $str =  json_encode(json_decode($json), 64 | 128 | 256);
                }else{
                    $str =  json_decode($json);
                }
                if(empty($str) || $str==='null' || $str==='false'){
                    return $json;
                }
                return $str;
            }
            return json_encode($json, 64 | 128 | 256);
        }
        if (is_string($json)) {
            $json_as_array = json_decode($json, true);
            if(empty($json_as_array)){
                //có thể là xml
                return $json;
            }
        } else if (is_object($json)) {
            $json_as_array = $json;
        }  else if (!is_array($json)) {
            $json_as_array = json_decode($json, true);
        } else {
            $json_as_array = $json;
        }
        $json_key_filter_security = config('wiz.security.json_key_filter_security');
        if ($includes) {
            $json_key_filter_security = array_merge($json_key_filter_security, $includes);
        }
        if(empty($json_key_filter_security)){
            $json_key_filter_security = ['token'];
        }
        self::markKeys($json_as_array,$json_key_filter_security,$includes);
        if($return_as_string) {
            return json_encode($json_as_array,JSON_UNESCAPED_SLASHES|128|256);
        }else{
            return $json_as_array;
        }
    }


    private static array $encode_table
        = [
            '0' => 'j4p0k3',
            '1' => 'u8jo0e',
            '2' => 'g7d5c6',
            '3' => 'j9l0d2',
            '4' => 'm6o3p5',
            '5' => 'p2ruj8',
            '6' => 's4u901',
            '7' => 'v5x265',
            '8' => 'y6kyh8',
            '9' => 'kl67ya'
        ];


    public static function encode_number_basic($number): string
    {

        return implode('-', array_map(function ($digit) {
            //return $digit;
            return self::$encode_table[$digit];
        }, str_split($number)));
    }

    public static  function decode_string_basic($encoded_string): int
    {
        $decoded_number = '';
        $codes          = explode('-', $encoded_string);
        foreach ($codes as $code) {
            foreach (self::$encode_table as $digit => $value) {
                if ($value == $code) {
                    $decoded_number .= $digit;
                    break;
                }
            }
        }
        return (int)$decoded_number;
    }
}
