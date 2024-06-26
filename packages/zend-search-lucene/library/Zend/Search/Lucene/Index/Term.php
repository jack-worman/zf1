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
 * A Term represents a word from text.  This is the unit of search.  It is
 * composed of two elements, the text of the word, as a string, and the name of
 * the field that the text occured in, an interned string.
 *
 * Note that terms may represent more than words from text fields, but also
 * things like dates, email addresses, urls, etc.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Search_Lucene_Index_Term
{
    /**
     * Field name or field number (depending from context).
     */
    public $field;

    /**
     * Term value.
     *
     * @var string
     */
    public $text;

    /**
     * Object constructor.
     */
    public function __construct($text, $field = null)
    {
        $this->field = (null === $field) ? Zend_Search_Lucene::getDefaultSearchField() : $field;
        $this->text = (string) $text;
    }

    /**
     * Returns term key.
     *
     * @return string
     */
    #[ReturnTypeWillChange]
    public function key()
    {
        return $this->field.chr(0).$this->text;
    }

    /**
     * Get term prefix.
     *
     * @param string $str
     * @param int    $length
     *
     * @return string
     */
    public static function getPrefix($str, $length)
    {
        $prefixBytes = 0;
        $prefixChars = 0;
        while ($prefixBytes < strlen((string) $str) && $prefixChars < $length) {
            $charBytes = 1;
            if ((ord((string) $str[$prefixBytes]) & 0xC0) == 0xC0) {
                ++$charBytes;
                if (ord((string) $str[$prefixBytes]) & 0x20) {
                    ++$charBytes;
                    if (ord((string) $str[$prefixBytes]) & 0x10) {
                        ++$charBytes;
                    }
                }
            }

            if ($prefixBytes + $charBytes > strlen((string) $str)) {
                // wrong character
                break;
            }

            ++$prefixChars;
            $prefixBytes += $charBytes;
        }

        return substr((string) $str, 0, $prefixBytes);
    }

    /**
     * Get UTF-8 string length.
     *
     * @param string $str
     *
     * @return int
     */
    public static function getLength($str)
    {
        $bytes = 0;
        $chars = 0;
        while ($bytes < strlen((string) $str)) {
            $charBytes = 1;
            if ((ord((string) $str[$bytes]) & 0xC0) == 0xC0) {
                ++$charBytes;
                if (ord((string) $str[$bytes]) & 0x20) {
                    ++$charBytes;
                    if (ord((string) $str[$bytes]) & 0x10) {
                        ++$charBytes;
                    }
                }
            }

            if ($bytes + $charBytes > strlen((string) $str)) {
                // wrong character
                break;
            }

            ++$chars;
            $bytes += $charBytes;
        }

        return $chars;
    }
}
