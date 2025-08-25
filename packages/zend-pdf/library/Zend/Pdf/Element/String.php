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

/** Zend_Pdf_Element */
// require_once 'Zend/Pdf/Element.php';

/**
 * PDF file 'string' element implementation.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Pdf_Element_String extends Zend_Pdf_Element
{
    /**
     * Object value.
     *
     * @var string
     */
    public $value;

    /**
     * Object constructor.
     *
     * @param string $val
     */
    public function __construct($val)
    {
        $this->value = (string) $val;
    }

    /**
     * Return type of the element.
     *
     * @return int
     */
    public function getType()
    {
        return Zend_Pdf_Element::TYPE_STRING;
    }

    /**
     * Return object as string.
     *
     * @param Zend_Pdf_Factory $factory
     *
     * @return string
     */
    public function toString($factory = null)
    {
        return '('.self::escape((string) $this->value).')';
    }

    /**
     * Escape string according to the PDF rules.
     *
     * @param string $str
     *
     * @return string
     */
    public static function escape($str)
    {
        $outEntries = [];

        foreach (str_split($str, 128) as $chunk) {
            // Collect sequence of unescaped characters
            $offset = strcspn($chunk, "\n\r\t\x08\x0C()\\");
            $chunkOut = substr((string) $chunk, 0, $offset);

            while ($offset < strlen((string) $chunk)) {
                $nextCode = ord((string) $chunk[$offset++]);
                switch ($nextCode) {
                    // "\n" - line feed (LF)
                    case 10:
                        $chunkOut .= '\\n';
                        break;

                        // "\r" - carriage return (CR)
                    case 13:
                        $chunkOut .= '\\r';
                        break;

                        // "\t" - horizontal tab (HT)
                    case 9:
                        $chunkOut .= '\\t';
                        break;

                        // "\b" - backspace (BS)
                    case 8:
                        $chunkOut .= '\\b';
                        break;

                        // "\f" - form feed (FF)
                    case 12:
                        $chunkOut .= '\\f';
                        break;

                        // '(' - left paranthesis
                    case 40:
                        $chunkOut .= '\\(';
                        break;

                        // ')' - right paranthesis
                    case 41:
                        $chunkOut .= '\\)';
                        break;

                        // '\' - backslash
                    case 92:
                        $chunkOut .= '\\\\';
                        break;

                    default:
                        // This code is never executed extually
                        //
                        // Don't use non-ASCII characters escaping
                        // if ($nextCode >= 32 && $nextCode <= 126 ) {
                        //     // Visible ASCII symbol
                        //     $chunkEntries[] = chr($nextCode);
                        // } else {
                        //     $chunkEntries[] = sprintf('\\%03o', $nextCode);
                        // }

                        break;
                }

                // Collect sequence of unescaped characters
                $start = $offset;
                $offset += strcspn($chunk, "\n\r\t\x08\x0C()\\", $offset);
                $chunkOut .= substr((string) $chunk, $start, $offset - $start);
            }

            $outEntries[] = $chunkOut;
        }

        return implode("\\\n", $outEntries);
    }

    /**
     * Unescape string according to the PDF rules.
     *
     * @param string $str
     *
     * @return string
     */
    public static function unescape($str)
    {
        $outEntries = [];

        $offset = 0;
        while ($offset < strlen((string) $str)) {
            // Searche for the next escaped character/sequence
            $escapeCharOffset = strpos((string) $str, '\\', $offset);
            if (false === $escapeCharOffset || $escapeCharOffset == strlen((string) $str) - 1) {
                // There are no escaped characters or '\' char has came at the end of string
                $outEntries[] = substr((string) $str, $offset);
                break;
            } else {
                // Collect unescaped characters sequence
                $outEntries[] = substr((string) $str, $offset, $escapeCharOffset - $offset);
                // Go to the escaped character
                $offset = $escapeCharOffset + 1;

                switch ($str[$offset]) {
                    // '\\n' - line feed (LF)
                    case 'n':
                        $outEntries[] = "\n";
                        break;

                        // '\\r' - carriage return (CR)
                    case 'r':
                        $outEntries[] = "\r";
                        break;

                        // '\\t' - horizontal tab (HT)
                    case 't':
                        $outEntries[] = "\t";
                        break;

                        // '\\b' - backspace (BS)
                    case 'b':
                        $outEntries[] = "\x08";
                        break;

                        // '\\f' - form feed (FF)
                    case 'f':
                        $outEntries[] = "\x0C";
                        break;

                        // '\\(' - left paranthesis
                    case '(':
                        $outEntries[] = '(';
                        break;

                        // '\\)' - right paranthesis
                    case ')':
                        $outEntries[] = ')';
                        break;

                        // '\\\\' - backslash
                    case '\\':
                        $outEntries[] = '\\';
                        break;

                        // "\\\n" or "\\\n\r"
                    case "\n":
                        // skip new line symbol
                        if ("\r" == $str[$offset + 1]) {
                            ++$offset;
                        }
                        break;

                    default:
                        if (false !== strpos((string) '0123456789', $str[$offset])) {
                            // Character in octal representation
                            // '\\xxx'
                            $nextCode = '0'.$str[$offset];

                            if (false !== strpos((string) '0123456789', $str[$offset + 1])) {
                                $nextCode .= $str[++$offset];

                                if (false !== strpos((string) '0123456789', $str[$offset + 1])) {
                                    $nextCode .= $str[++$offset];
                                }
                            }

                            $outEntries[] = chr(octdec($nextCode));
                        } else {
                            $outEntries[] = $str[$offset];
                        }
                        break;
                }

                ++$offset;
            }
        }

        return implode($outEntries);
    }
}
