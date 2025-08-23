<?php

/**
 * Zend Framework.
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @version    $Id$
 */

/**
 * @see Zend_Ldap_Converter
 */
// require_once 'Zend/Ldap/Converter.php';

/** @see Zend_Crypt_Math */
// require_once 'Zend/Crypt/Math.php';

/**
 * Zend_Ldap_Attribute is a collection of LDAP attribute related functions.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Ldap_Attribute
{
    public const PASSWORD_HASH_MD5 = 'md5';
    public const PASSWORD_HASH_SMD5 = 'smd5';
    public const PASSWORD_HASH_SHA = 'sha';
    public const PASSWORD_HASH_SSHA = 'ssha';
    public const PASSWORD_UNICODEPWD = 'unicodePwd';

    /**
     * Sets a LDAP attribute.
     *
     * @param string                   $attribName
     * @param scalar|array|Traversable $value
     * @param bool                     $append
     *
     * @return void
     */
    public static function setAttribute(array &$data, $attribName, $value, $append = false)
    {
        $attribName = strtolower((string) $attribName);
        $valArray = [];
        if (is_array($value) || ($value instanceof Traversable)) {
            foreach ($value as $v) {
                $v = self::_valueToLdap($v);
                if (null !== $v) {
                    $valArray[] = $v;
                }
            }
        } elseif (null !== $value) {
            $value = self::_valueToLdap($value);
            if (null !== $value) {
                $valArray[] = $value;
            }
        }

        if (true === $append && isset($data[$attribName])) {
            if (is_string($data[$attribName])) {
                $data[$attribName] = [$data[$attribName]];
            }
            $data[$attribName] = array_merge($data[$attribName], $valArray);
        } else {
            $data[$attribName] = $valArray;
        }
    }

    /**
     * Gets a LDAP attribute.
     *
     * @param string $attribName
     * @param int    $index
     *
     * @return array|mixed
     */
    public static function getAttribute(array $data, $attribName, $index = null)
    {
        $attribName = strtolower((string) $attribName);
        if (null === $index) {
            if (!isset($data[$attribName])) {
                return [];
            }
            $retArray = [];
            foreach ($data[$attribName] as $v) {
                $retArray[] = self::_valueFromLdap($v);
            }

            return $retArray;
        } elseif (is_int($index)) {
            if (!isset($data[$attribName])) {
                return null;
            } elseif ($index >= 0 && $index < count($data[$attribName])) {
                return self::_valueFromLdap($data[$attribName][$index]);
            } else {
                return null;
            }
        }

        return null;
    }

    /**
     * Checks if the given value(s) exist in the attribute.
     *
     * @param string      $attribName
     * @param mixed|array $value
     *
     * @return bool
     */
    public static function attributeHasValue(array &$data, $attribName, $value)
    {
        $attribName = strtolower((string) $attribName);
        if (!isset($data[$attribName])) {
            return false;
        }

        if (is_scalar($value)) {
            $value = [$value];
        }

        foreach ($value as $v) {
            $v = self::_valueToLdap($v);
            if (!in_array($v, $data[$attribName], true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Removes duplicate values from a LDAP attribute.
     *
     * @param string $attribName
     *
     * @return void
     */
    public static function removeDuplicatesFromAttribute(array &$data, $attribName)
    {
        $attribName = strtolower((string) $attribName);
        if (!isset($data[$attribName])) {
            return;
        }
        $data[$attribName] = array_values(array_unique($data[$attribName]));
    }

    /**
     * Remove given values from a LDAP attribute.
     *
     * @param string      $attribName
     * @param mixed|array $value
     *
     * @return void
     */
    public static function removeFromAttribute(array &$data, $attribName, $value)
    {
        $attribName = strtolower((string) $attribName);
        if (!isset($data[$attribName])) {
            return;
        }

        if (is_scalar($value)) {
            $value = [$value];
        }

        $valArray = [];
        foreach ($value as $v) {
            $v = self::_valueToLdap($v);
            if (null !== $v) {
                $valArray[] = $v;
            }
        }

        $resultArray = $data[$attribName];
        foreach ($valArray as $rv) {
            $keys = array_keys($resultArray, $rv);
            foreach ($keys as $k) {
                unset($resultArray[$k]);
            }
        }
        $resultArray = array_values($resultArray);
        $data[$attribName] = $resultArray;
    }

    /**
     * @return string|null
     */
    private static function _valueToLdap($value)
    {
        return Zend_Ldap_Converter::toLdap($value);
    }

    /**
     * @param string $value
     */
    private static function _valueFromLdap($value)
    {
        try {
            $return = Zend_Ldap_Converter::fromLdap($value, Zend_Ldap_Converter::STANDARD, false);
            if ($return instanceof DateTime) {
                return Zend_Ldap_Converter::toLdapDateTime($return, false);
            } else {
                return $return;
            }
        } catch (InvalidArgumentException $e) {
            return $value;
        }
    }

    /**
     * Converts a PHP data type into its LDAP representation.
     *
     * @deprected    use Zend_Ldap_Converter instead
     *
     * @return string|null - null if the PHP data type cannot be converted
     */
    public static function convertToLdapValue($value)
    {
        return self::_valueToLdap($value);
    }

    /**
     * Converts an LDAP value into its PHP data type.
     *
     * @deprected    use Zend_Ldap_Converter instead
     *
     * @param string $value
     */
    public static function convertFromLdapValue($value)
    {
        return self::_valueFromLdap($value);
    }

    /**
     * Converts a timestamp into its LDAP date/time representation.
     *
     * @param int  $value
     * @param bool $utc
     *
     * @return string|null - null if the value cannot be converted
     */
    public static function convertToLdapDateTimeValue($value, $utc = false)
    {
        return self::_valueToLdapDateTime($value, $utc);
    }

    /**
     * Converts LDAP date/time representation into a timestamp.
     *
     * @param string $value
     *
     * @return string|null - null if the value cannot be converted
     */
    public static function convertFromLdapDateTimeValue($value)
    {
        return self::_valueFromLdapDateTime($value);
    }

    /**
     * Sets a LDAP password.
     *
     * @param string      $password
     * @param string      $hashType
     * @param string|null $attribName
     *
     * @return null
     */
    public static function setPassword(array &$data, $password, $hashType = self::PASSWORD_HASH_MD5,
        $attribName = null)
    {
        if (null === $attribName) {
            if (self::PASSWORD_UNICODEPWD === $hashType) {
                $attribName = 'unicodePwd';
            } else {
                $attribName = 'userPassword';
            }
        }

        $hash = self::createPassword($password, $hashType);
        self::setAttribute($data, $attribName, $hash, false);
    }

    /**
     * Creates a LDAP password.
     *
     * @param string $password
     * @param string $hashType
     *
     * @return string
     */
    public static function createPassword($password, $hashType = self::PASSWORD_HASH_MD5)
    {
        switch ($hashType) {
            case self::PASSWORD_UNICODEPWD:
                /* see:
                 * http://msdn.microsoft.com/en-us/library/cc223248(PROT.10).aspx
                 */
                $password = '"'.$password.'"';
                if (function_exists('mb_convert_encoding')) {
                    $password = mb_convert_encoding($password, 'UTF-16LE', 'UTF-8');
                } elseif (function_exists('iconv')) {
                    $password = iconv('UTF-8', 'UTF-16LE', $password);
                } else {
                    $len = strlen((string) $password);
                    $new = '';
                    for ($i = 0; $i < $len; ++$i) {
                        $new .= $password[$i]."\x00";
                    }
                    $password = $new;
                }

                return $password;
            case self::PASSWORD_HASH_SSHA:
                $salt = Zend_Crypt_Math::randBytes(4);
                $rawHash = sha1($password.$salt, true).$salt;
                $method = '{SSHA}';
                break;
            case self::PASSWORD_HASH_SHA:
                $rawHash = sha1($password, true);
                $method = '{SHA}';
                break;
            case self::PASSWORD_HASH_SMD5:
                $salt = Zend_Crypt_Math::randBytes(4);
                $rawHash = md5((string) $password.$salt, true).$salt;
                $method = '{SMD5}';
                break;
            case self::PASSWORD_HASH_MD5:
            default:
                $rawHash = md5((string) $password, true);
                $method = '{MD5}';
                break;
        }

        return $method.base64_encode($rawHash);
    }

    /**
     * Sets a LDAP date/time attribute.
     *
     * @param string                $attribName
     * @param int|array|Traversable $value
     * @param bool                  $utc
     * @param bool                  $append
     *
     * @return null
     */
    public static function setDateTimeAttribute(array &$data, $attribName, $value, $utc = false,
        $append = false)
    {
        $convertedValues = [];
        if (is_array($value) || ($value instanceof Traversable)) {
            foreach ($value as $v) {
                $v = self::_valueToLdapDateTime($v, $utc);
                if (null !== $v) {
                    $convertedValues[] = $v;
                }
            }
        } elseif (null !== $value) {
            $value = self::_valueToLdapDateTime($value, $utc);
            if (null !== $value) {
                $convertedValues[] = $value;
            }
        }
        self::setAttribute($data, $attribName, $convertedValues, $append);
    }

    /**
     * @param int  $value
     * @param bool $utc
     *
     * @return string|null
     */
    private static function _valueToLdapDateTime($value, $utc)
    {
        if (is_int($value)) {
            return Zend_Ldap_Converter::toLdapDateTime($value, $utc);
        } else {
            return null;
        }
    }

    /**
     * Gets a LDAP date/time attribute.
     *
     * @param string $attribName
     * @param int    $index
     *
     * @return array|int
     */
    public static function getDateTimeAttribute(array $data, $attribName, $index = null)
    {
        $values = self::getAttribute($data, $attribName, $index);
        if (is_array($values)) {
            for ($i = 0; $i < count($values); ++$i) {
                $newVal = self::_valueFromLdapDateTime($values[$i]);
                if (null !== $newVal) {
                    $values[$i] = $newVal;
                }
            }
        } else {
            $newVal = self::_valueFromLdapDateTime($values);
            if (null !== $newVal) {
                $values = $newVal;
            }
        }

        return $values;
    }

    /**
     * @param string|DateTime $value
     *
     * @return string|null
     */
    private static function _valueFromLdapDateTime($value)
    {
        if ($value instanceof DateTime) {
            return $value->format('U');
        } elseif (is_string($value)) {
            try {
                return Zend_Ldap_Converter::fromLdapDateTime($value, false)->format('U');
            } catch (InvalidArgumentException $e) {
                return null;
            }
        } else {
            return null;
        }
    }
}
