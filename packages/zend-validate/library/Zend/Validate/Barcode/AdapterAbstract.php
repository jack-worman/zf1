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
 */
abstract class Zend_Validate_Barcode_AdapterAbstract implements Zend_Validate_Barcode_AdapterInterface
{
    /**
     * Allowed barcode lengths.
     *
     * @var int|array|string
     */
    protected $_length;

    /**
     * Allowed barcode characters.
     *
     * @var string
     */
    protected $_characters;

    /**
     * Callback to checksum function.
     *
     * @var string|array
     */
    protected $_checksum;

    /**
     * Is a checksum value included?
     *
     * @var bool
     */
    protected $_hasChecksum = true;

    /**
     * Checks the length of a barcode.
     *
     * @param string $value The barcode to check for proper length
     *
     * @return bool
     */
    public function checkLength($value)
    {
        if (!is_string($value)) {
            return false;
        }

        $fixum = strlen((string) $value);
        $found = false;
        $length = $this->getLength();
        if (is_array($length)) {
            foreach ($length as $value) {
                if ($fixum == $value) {
                    $found = true;
                }

                if (-1 == $value) {
                    $found = true;
                }
            }
        } elseif ($fixum == $length) {
            $found = true;
        } elseif (-1 == $length) {
            $found = true;
        } elseif ('even' == $length) {
            $count = $fixum % 2;
            $found = (0 == $count) ? true : false;
        } elseif ('odd' == $length) {
            $count = $fixum % 2;
            $found = (1 == $count) ? true : false;
        }

        return $found;
    }

    /**
     * Checks for allowed characters within the barcode.
     *
     * @param string $value The barcode to check for allowed characters
     *
     * @return bool
     */
    public function checkChars($value)
    {
        if (!is_string($value)) {
            return false;
        }

        $characters = $this->getCharacters();
        if (128 == $characters) {
            for ($x = 0; $x < 128; ++$x) {
                $value = str_replace((string) chr($x), '', $value);
            }
        } else {
            $chars = str_split($characters);
            foreach ($chars as $char) {
                $value = str_replace((string) $char, '', $value);
            }
        }

        if (strlen((string) $value) > 0) {
            return false;
        }

        return true;
    }

    /**
     * Validates the checksum.
     *
     * @param string $value The barcode to check the checksum for
     *
     * @return bool
     */
    public function checksum($value)
    {
        $checksum = $this->getChecksum();
        if (!empty($checksum)) {
            if (method_exists($this, $checksum)) {
                return call_user_func([$this, $checksum], $value);
            }
        }

        return false;
    }

    /**
     * Returns the allowed barcode length.
     *
     * @return string
     */
    public function getLength()
    {
        return $this->_length;
    }

    /**
     * Returns the allowed characters.
     *
     * @return string
     */
    public function getCharacters()
    {
        return $this->_characters;
    }

    /**
     * Returns the checksum function name.
     */
    public function getChecksum()
    {
        return $this->_checksum;
    }

    /**
     * Returns if barcode uses checksum.
     *
     * @return bool
     */
    public function getCheck()
    {
        return $this->_hasChecksum;
    }

    /**
     * Sets the checksum validation.
     *
     * @param bool $check
     *
     * @return Zend_Validate_Barcode_AdapterAbstract
     */
    public function setCheck($check)
    {
        $this->_hasChecksum = (bool) $check;

        return $this;
    }

    /**
     * Validates the checksum (Modulo 10)
     * GTIN implementation factor 3.
     *
     * @param string $value The barcode to validate
     *
     * @return bool
     */
    protected function _gtin($value)
    {
        $barcode = substr((string) $value, 0, -1);
        $sum = 0;
        $length = strlen((string) $barcode) - 1;

        for ($i = 0; $i <= $length; ++$i) {
            if (($i % 2) === 0) {
                $sum += $barcode[$length - $i] * 3;
            } else {
                $sum += $barcode[$length - $i];
            }
        }

        $calc = $sum % 10;
        $checksum = (0 === $calc) ? 0 : (10 - $calc);
        if ($value[$length + 1] != $checksum) {
            return false;
        }

        return true;
    }

    /**
     * Validates the checksum (Modulo 10)
     * IDENTCODE implementation factors 9 and 4.
     *
     * @param string $value The barcode to validate
     *
     * @return bool
     */
    protected function _identcode($value)
    {
        $barcode = substr((string) $value, 0, -1);
        $sum = 0;
        $length = strlen((string) $value) - 2;

        for ($i = 0; $i <= $length; ++$i) {
            if (($i % 2) === 0) {
                $sum += $barcode[$length - $i] * 4;
            } else {
                $sum += $barcode[$length - $i] * 9;
            }
        }

        $calc = $sum % 10;
        $checksum = (0 === $calc) ? 0 : (10 - $calc);
        if ($value[$length + 1] != $checksum) {
            return false;
        }

        return true;
    }

    /**
     * Validates the checksum (Modulo 10)
     * CODE25 implementation factor 3.
     *
     * @param string $value The barcode to validate
     *
     * @return bool
     */
    protected function _code25($value)
    {
        $barcode = substr((string) $value, 0, -1);
        $sum = 0;
        $length = strlen((string) $barcode) - 1;

        for ($i = 0; $i <= $length; ++$i) {
            if (($i % 2) === 0) {
                $sum += $barcode[$i] * 3;
            } else {
                $sum += $barcode[$i];
            }
        }

        $calc = $sum % 10;
        $checksum = (0 === $calc) ? 0 : (10 - $calc);
        if ($value[$length + 1] != $checksum) {
            return false;
        }

        return true;
    }

    /**
     * Validates the checksum ()
     * POSTNET implementation.
     *
     * @param string $value The barcode to validate
     *
     * @return bool
     */
    protected function _postnet($value)
    {
        $checksum = substr((string) $value, -1, 1);
        $values = str_split(substr((string) $value, 0, -1));

        $check = 0;
        foreach ($values as $row) {
            $check += $row;
        }

        $check %= 10;
        $check = 10 - $check;
        if ($check == $checksum) {
            return true;
        }

        return false;
    }
}
