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
class Zend_Validate_Barcode_Issn extends Zend_Validate_Barcode_AdapterAbstract
{
    /**
     * Allowed barcode lengths.
     *
     * @var int
     */
    protected $_length = [8, 13];

    /**
     * Allowed barcode characters.
     *
     * @var string
     */
    protected $_characters = '0123456789X';

    /**
     * Checksum function.
     *
     * @var string
     */
    protected $_checksum = '_gtin';

    /**
     * Allows X on length of 8 chars.
     *
     * @param string $value The barcode to check for allowed characters
     *
     * @return bool
     */
    public function checkChars($value)
    {
        if (8 != strlen((string) $value)) {
            if (false !== strpos((string) $value, 'X')) {
                return false;
            }
        }

        return parent::checkChars($value);
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
        if (8 == strlen((string) $value)) {
            $this->_checksum = '_issn';
        } else {
            $this->_checksum = '_gtin';
        }

        return parent::checksum($value);
    }

    /**
     * Validates the checksum ()
     * ISSN implementation (reversed mod11).
     *
     * @param string $value The barcode to validate
     *
     * @return bool
     */
    protected function _issn($value)
    {
        $checksum = substr((string) $value, -1, 1);
        $values = str_split(substr((string) $value, 0, -1));
        $check = 0;
        $multi = 8;
        foreach ($values as $token) {
            if ('X' == $token) {
                $token = 10;
            }

            $check += ($token * $multi);
            --$multi;
        }

        $check %= 11;
        $check = 11 - $check;
        if ($check == $checksum) {
            return true;
        } elseif ((10 == $check) && ('X' == $checksum)) {
            return true;
        }

        return false;
    }
}
