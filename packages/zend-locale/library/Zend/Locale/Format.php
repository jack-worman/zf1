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
 *
 * @version    $Id$
 *
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * include needed classes.
 */
// require_once 'Zend/Locale/Data.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Locale_Format
{
    public const STANDARD = 'auto';

    private static $_options = ['date_format' => null,
        'number_format' => null,
        'format_type' => 'iso',
        'fix_date' => false,
        'locale' => null,
        'cache' => null,
        'disableCache' => null,
        'precision' => null];

    /**
     * Sets class wide options, if no option was given, the actual set options will be returned
     * The 'precision' option of a value is used to truncate or stretch extra digits. -1 means not to touch the extra digits.
     * The 'locale' option helps when parsing numbers and dates using separators and month names.
     * The date format 'format_type' option selects between CLDR/ISO date format specifier tokens and PHP's date() tokens.
     * The 'fix_date' option enables or disables heuristics that attempt to correct invalid dates.
     * The 'number_format' option can be used to specify a default number format string
     * The 'date_format' option can be used to specify a default date format string, but beware of using getDate(),
     * checkDateFormat() and getTime() after using setOptions() with a 'format'.  To use these four methods
     * with the default date format for a locale, use array('date_format' => null, 'locale' => $locale) for their options.
     *
     * @param array $options Array of options, keyed by option name: format_type = 'iso' | 'php', fix_date = true | false,
     *                       locale = Zend_Locale | locale string, precision = whole number between -1 and 30
     *
     * @return array if no option was given
     *
     * @throws Zend_Locale_Exception
     */
    public static function setOptions(array $options = [])
    {
        self::$_options = self::_checkOptions($options) + self::$_options;

        return self::$_options;
    }

    /**
     * Internal function for checking the options array of proper input values
     * See {@link setOptions()} for details.
     *
     * @param array $options Array of options, keyed by option name: format_type = 'iso' | 'php', fix_date = true | false,
     *                       locale = Zend_Locale | locale string, precision = whole number between -1 and 30
     *
     * @return array if no option was given
     *
     * @throws Zend_Locale_Exception
     */
    private static function _checkOptions(array $options = [])
    {
        if (0 == count($options)) {
            return self::$_options;
        }
        foreach ($options as $name => $value) {
            $name = strtolower((string) $name);
            if ('locale' !== $name) {
                if ('string' === gettype($value)) {
                    $value = strtolower((string) $value);
                }
            }

            switch ($name) {
                case 'number_format' :
                    if (Zend_Locale_Format::STANDARD == $value) {
                        $locale = self::$_options['locale'];
                        if (isset($options['locale'])) {
                            $locale = $options['locale'];
                        }
                        $options['number_format'] = Zend_Locale_Data::getContent($locale, 'decimalnumber');
                    } elseif (('string' !== gettype($value)) and (null !== $value)) {
                        // require_once 'Zend/Locale/Exception.php';
                        $stringValue = (string) (is_array($value) ? implode(' ', $value) : $value);
                        throw new Zend_Locale_Exception("Unknown number format type '".gettype($value)."'. Format '$stringValue' must be a valid number format string.");
                    }
                    break;

                case 'date_format' :
                    if (Zend_Locale_Format::STANDARD == $value) {
                        $locale = self::$_options['locale'];
                        if (isset($options['locale'])) {
                            $locale = $options['locale'];
                        }
                        $options['date_format'] = Zend_Locale_Format::getDateFormat($locale);
                    } elseif (('string' !== gettype($value)) and (null !== $value)) {
                        // require_once 'Zend/Locale/Exception.php';
                        $stringValue = (string) (is_array($value) ? implode(' ', $value) : $value);
                        throw new Zend_Locale_Exception("Unknown dateformat type '".gettype($value)."'. Format '$stringValue' must be a valid ISO or PHP date format string.");
                    } else {
                        if (((true === isset($options['format_type'])) and ('php' == $options['format_type']))
                            or ((false === isset($options['format_type'])) and ('php' == self::$_options['format_type']))) {
                            $options['date_format'] = Zend_Locale_Format::convertPhpToIsoFormat($value);
                        }
                    }
                    break;

                case 'format_type' :
                    if (('php' != $value) && ('iso' != $value)) {
                        // require_once 'Zend/Locale/Exception.php';
                        throw new Zend_Locale_Exception("Unknown date format type '$value'. Only 'iso' and 'php'".' are supported.');
                    }
                    break;

                case 'fix_date' :
                    if ((true !== $value) && (false !== $value)) {
                        // require_once 'Zend/Locale/Exception.php';
                        throw new Zend_Locale_Exception('Enabling correction of dates must be either true or false'."(fix_date='$value').");
                    }
                    break;

                case 'locale' :
                    $options['locale'] = Zend_Locale::findLocale($value);
                    break;

                case 'cache' :
                    if ($value instanceof Zend_Cache_Core) {
                        Zend_Locale_Data::setCache($value);
                    }
                    break;

                case 'disablecache' :
                    if (null !== $value) {
                        Zend_Locale_Data::disableCache($value);
                    }
                    break;

                case 'precision' :
                    if (null === $value) {
                        $value = -1;
                    }

                    if (($value < -1) || ($value > 30)) {
                        // require_once 'Zend/Locale/Exception.php';
                        throw new Zend_Locale_Exception("'$value' precision is not a whole number less than 30.");
                    }
                    break;

                default:
                    // require_once 'Zend/Locale/Exception.php';
                    throw new Zend_Locale_Exception("Unknown option: '$name' = '$value'");
                    break;
            }
        }

        return $options;
    }

    /**
     * Changes the numbers/digits within a given string from one script to another
     * 'Decimal' represented the standard numbers 0-9, if a script does not exist
     * an exception will be thrown.
     *
     * Examples for conversion from Arabic to Latin numerals:
     *   convertNumerals('١١٠ Tests', 'Arab'); -> returns '100 Tests'
     * Example for conversion from Latin to Arabic numerals:
     *   convertNumerals('100 Tests', 'Latn', 'Arab'); -> returns '١١٠ Tests'
     *
     * @param string $input String to convert
     * @param string $from  script to parse, see {@link Zend_Locale::getScriptList()} for details
     * @param string $to    OPTIONAL Script to convert to
     *
     * @return string Returns the converted input
     *
     * @throws Zend_Locale_Exception
     */
    public static function convertNumerals($input, $from, $to = null)
    {
        if (!self::_getUniCodeSupport()) {
            trigger_error('Sorry, your PCRE extension does not support UTF8 which is needed for the I18N core', E_USER_NOTICE);
        }

        $from = strtolower((string) $from);
        $source = Zend_Locale_Data::getContent('en', 'numberingsystem', $from);
        if (empty($source)) {
            // require_once 'Zend/Locale/Exception.php';
            throw new Zend_Locale_Exception("Unknown script '$from'. Use 'Latn' for digits 0,1,2,3,4,5,6,7,8,9.");
        }

        if (null !== $to) {
            $to = strtolower((string) $to);
            $target = Zend_Locale_Data::getContent('en', 'numberingsystem', $to);
            if (empty($target)) {
                // require_once 'Zend/Locale/Exception.php';
                throw new Zend_Locale_Exception("Unknown script '$to'. Use 'Latn' for digits 0,1,2,3,4,5,6,7,8,9.");
            }
        } else {
            $target = '0123456789';
        }

        for ($x = 0; $x < 10; ++$x) {
            $asource[$x] = '/'.iconv_substr((string) $source, $x, 1, 'UTF-8').'/u';
            $atarget[$x] = iconv_substr((string) $target, $x, 1, 'UTF-8');
        }

        return preg_replace($asource, $atarget, $input);
    }

    /**
     * Returns the normalized number from a localized one
     * Parsing depends on given locale (grouping and decimal).
     *
     * Examples for input:
     * '2345.4356,1234' = 23455456.1234
     * '+23,3452.123' = 233452.123
     * '12343 ' = 12343
     * '-9456' = -9456
     * '0' = 0
     *
     * @param string $input   Input string to parse for numbers
     * @param array  $options Options: locale, precision. See {@link setOptions()} for details.
     *
     * @return string Returns the extracted number
     *
     * @throws Zend_Locale_Exception
     */
    public static function getNumber($input, array $options = [])
    {
        $options = self::_checkOptions($options) + self::$_options;
        if (!is_string($input)) {
            return $input;
        }

        if (!self::isNumber($input, $options)) {
            // require_once 'Zend/Locale/Exception.php';
            throw new Zend_Locale_Exception('No localized value in '.$input.' found, or the given number does not match the localized format');
        }

        // Get correct signs for this locale
        $symbols = Zend_Locale_Data::getList($options['locale'], 'symbols');
        // Change locale input to be default number
        if (($input[0] == $symbols['minus']) && ('-' != $input[0])) {
            $input = '-'.substr((string) $input, 1);
        }

        $input = str_replace((string) $symbols['group'], '', $input);
        if (false !== strpos((string) $input, $symbols['decimal'])) {
            if ('.' != $symbols['decimal']) {
                $input = str_replace((string) $symbols['decimal'], '.', $input);
            }

            $pre = substr((string) $input, strpos((string) $input, '.') + 1);
            if (null === $options['precision']) {
                $options['precision'] = strlen((string) $pre);
            }

            if (strlen((string) $pre) >= $options['precision']) {
                $input = substr((string) $input, 0, strlen((string) $input) - strlen((string) $pre) + $options['precision']);
            }

            if ((0 == $options['precision']) && ('.' == $input[strlen((string) $input) - 1])) {
                $input = substr((string) $input, 0, -1);
            }
        }

        return $input;
    }

    /**
     * Returns a locale formatted number depending on the given options.
     * The separation and fraction sign is used from the set locale.
     * ##0.#  -> 12345.12345 -> 12345.12345
     * ##0.00 -> 12345.12345 -> 12345.12
     * ##,##0.00 -> 12345.12345 -> 12,345.12.
     *
     * @param string $value   Localized number string
     * @param array  $options Options: number_format, locale, precision. See {@link setOptions()} for details.
     *
     * @return string locale formatted number
     *
     * @throws Zend_Locale_Exception
     */
    public static function toNumber($value, array $options = [])
    {
        // load class within method for speed
        // require_once 'Zend/Locale/Math.php';

        $value = Zend_Locale_Math::floatalize($value);
        $options = self::_checkOptions($options) + self::$_options;
        $options['locale'] = (string) $options['locale'];

        // Get correct signs for this locale
        $symbols = Zend_Locale_Data::getList($options['locale'], 'symbols');
        $oenc = self::_getEncoding();
        self::_setEncoding('UTF-8');

        // Get format
        $format = $options['number_format'];
        if (null === $format) {
            $format = Zend_Locale_Data::getContent($options['locale'], 'decimalnumber');
            $format = self::_seperateFormat($format, $value, $options['precision']);

            if (null !== $options['precision']) {
                $value = Zend_Locale_Math::round($value, $options['precision']);
            }
        } else {
            // seperate negative format pattern when available
            $format = self::_seperateFormat($format, $value, $options['precision']);
            if (strpos((string) $format, '.')) {
                if (is_numeric($options['precision'])) {
                    $value = Zend_Locale_Math::round($value, $options['precision']);
                } else {
                    if ('###' == substr((string) $format, iconv_strpos($format, '.') + 1, 3)) {
                        $options['precision'] = null;
                    } else {
                        $options['precision'] = iconv_strlen(iconv_substr((string) $format, iconv_strpos($format, '.') + 1,
                            iconv_strrpos($format, '0') - iconv_strpos($format, '.')));
                        $format = iconv_substr((string) $format, 0, iconv_strpos($format, '.') + 1).'###'
                                .iconv_substr((string) $format, iconv_strrpos($format, '0') + 1);
                    }
                }
            } else {
                $value = Zend_Locale_Math::round($value, 0);
                $options['precision'] = 0;
            }
        }

        if (false === iconv_strpos($format, '0')) {
            self::_setEncoding($oenc);
            // require_once 'Zend/Locale/Exception.php';
            throw new Zend_Locale_Exception('Wrong format... missing 0');
        }

        // get number parts
        $pos = iconv_strpos($value, '.');
        if (false !== $pos) {
            if (null === $options['precision']) {
                $precstr = iconv_substr((string) $value, $pos + 1);
            } else {
                $precstr = iconv_substr((string) $value, $pos + 1, $options['precision']);
                if (iconv_strlen($precstr) < $options['precision']) {
                    $precstr .= str_pad('0', $options['precision'] - iconv_strlen($precstr), '0');
                }
            }
        } else {
            if ($options['precision'] > 0) {
                $precstr = str_pad('0', $options['precision'], '0');
            }
        }

        if (null === $options['precision']) {
            if (isset($precstr)) {
                $options['precision'] = iconv_strlen($precstr);
            } else {
                $options['precision'] = 0;
            }
        }

        // get fraction and format lengths
        if (false !== strpos((string) $value, '.')) {
            $number = substr((string) (string) $value, 0, strpos((string) $value, '.'));
        } else {
            $number = $value;
        }

        $prec = call_user_func(Zend_Locale_Math::$sub, $value, $number, $options['precision']);
        $prec = Zend_Locale_Math::floatalize($prec);
        if (false !== iconv_strpos($prec, '-')) {
            $prec = iconv_substr((string) $prec, 1);
        }

        if ((0 == $prec) and ($options['precision'] > 0)) {
            $prec = '0.0';
        }

        if (($options['precision'] + 2) > iconv_strlen($prec)) {
            $prec = str_pad((string) $prec, $options['precision'] + 2, '0', STR_PAD_RIGHT);
        }

        if (false !== iconv_strpos($number, '-')) {
            $number = iconv_substr((string) $number, 1);
        }
        $group = iconv_strrpos($format, ',');
        $group2 = iconv_strpos($format, ',');
        $point = iconv_strpos($format, '0');
        // Add fraction
        $rest = '';
        if (iconv_strpos($format, '.')) {
            $rest = iconv_substr((string) $format, iconv_strpos($format, '.') + 1);
            $length = iconv_strlen($rest);
            for ($x = 0; $x < $length; ++$x) {
                if (('0' == $rest[0]) || ('#' == $rest[0])) {
                    $rest = iconv_substr((string) $rest, 1);
                }
            }
            $format = iconv_substr((string) $format, 0, iconv_strlen($format) - iconv_strlen($rest));
        }

        if ('0' == $options['precision']) {
            if (0 != iconv_strrpos($format, '-')) {
                $format = iconv_substr((string) $format, 0, $point)
                        .iconv_substr((string) $format, iconv_strrpos($format, '#') + 2);
            } else {
                $format = iconv_substr((string) $format, 0, $point);
            }
        } else {
            $format = iconv_substr((string) $format, 0, $point).$symbols['decimal']
                               .iconv_substr((string) $prec, 2);
        }

        $format .= $rest;
        // Add separation
        if (0 == $group) {
            // no separation
            $format = $number.iconv_substr((string) $format, $point);
        } elseif ($group == $group2) {
            // only 1 separation
            $separation = ($point - $group);
            for ($x = iconv_strlen($number); $x > $separation; $x -= $separation) {
                if ('' !== iconv_substr((string) $number, 0, $x - $separation)) {
                    $number = iconv_substr((string) $number, 0, $x - $separation).$symbols['group']
                            .iconv_substr((string) $number, $x - $separation);
                }
            }
            $format = iconv_substr((string) $format, 0, iconv_strpos($format, '#')).$number.iconv_substr((string) $format, $point);
        } else {
            // 2 separations
            if (iconv_strlen($number) > ($point - $group)) {
                $separation = ($point - $group);
                $number = iconv_substr((string) $number, 0, iconv_strlen($number) - $separation).$symbols['group']
                        .iconv_substr((string) $number, iconv_strlen($number) - $separation);

                if ((iconv_strlen($number) - 1) > ($point - $group + 1)) {
                    $separation2 = ($group - $group2 - 1);
                    for ($x = iconv_strlen($number) - $separation2 - 2; $x > $separation2; $x -= $separation2) {
                        $number = iconv_substr((string) $number, 0, $x - $separation2).$symbols['group']
                                .iconv_substr((string) $number, $x - $separation2);
                    }
                }
            }
            $format = iconv_substr((string) $format, 0, iconv_strpos($format, '#')).$number.iconv_substr((string) $format, $point);
        }
        // set negative sign
        if (call_user_func(Zend_Locale_Math::$comp, $value, 0, $options['precision']) < 0) {
            if (false === iconv_strpos($format, '-')) {
                $format = $symbols['minus'].$format;
            } else {
                $format = str_replace((string) '-', $symbols['minus'], $format);
            }
        }

        self::_setEncoding($oenc);

        return (string) $format;
    }

    /**
     * @param string $format
     * @param string $value
     * @param int    $precision
     *
     * @return string
     */
    private static function _seperateFormat($format, $value, $precision)
    {
        if (false !== iconv_strpos($format, ';')) {
            if (call_user_func(Zend_Locale_Math::$comp, $value, 0, $precision) < 0) {
                $tmpformat = iconv_substr((string) $format, iconv_strpos($format, ';') + 1);
                if ('(' == $tmpformat[0]) {
                    $format = iconv_substr((string) $format, 0, iconv_strpos($format, ';'));
                } else {
                    $format = $tmpformat;
                }
            } else {
                $format = iconv_substr((string) $format, 0, iconv_strpos($format, ';'));
            }
        }

        return $format;
    }

    /**
     * Checks if the input contains a normalized or localized number.
     *
     * @param string $input   Localized number string
     * @param array  $options Options: locale. See {@link setOptions()} for details.
     *
     * @return bool Returns true if a number was found
     *
     * @throws Zend_Locale_Exception
     */
    public static function isNumber($input, array $options = [])
    {
        $input = (string) $input;
        if (!self::_getUniCodeSupport()) {
            trigger_error('Sorry, your PCRE extension does not support UTF8 which is needed for the I18N core', E_USER_NOTICE);
        }

        $options = self::_checkOptions($options) + self::$_options;

        // Get correct signs for this locale
        $symbols = Zend_Locale_Data::getList($options['locale'], 'symbols');

        $regexs = self::_getRegexForType('decimalnumber', $options);
        $regexs = array_merge($regexs, self::_getRegexForType('scientificnumber', $options));
        if (!empty($input) && ($input[0] == $symbols['decimal'])) {
            $input = 0 .$input;
        }
        foreach ($regexs as $regex) {
            preg_match($regex, $input, $found);
            if (isset($found[0])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Internal method to convert cldr number syntax into regex.
     *
     * @param string $type
     * @param array  $options Options: locale. See {@link setOptions()} for details.
     *
     * @return array
     *
     * @throws Zend_Locale_Exception
     */
    private static function _getRegexForType($type, $options)
    {
        $decimal = Zend_Locale_Data::getContent($options['locale'], $type);
        $decimal = preg_replace('/[^#0,;\.\-Ee]/u', '', $decimal);
        $patterns = explode(';', $decimal);

        if (1 === count($patterns)) {
            $patterns[1] = '-'.$patterns[0];
        }

        $symbols = Zend_Locale_Data::getList($options['locale'], 'symbols');

        foreach ($patterns as $pkey => $pattern) {
            $regex[$pkey] = '/^';
            $end = null;
            if (false !== strpos((string) $pattern, '.')) {
                $end = substr((string) $pattern, strpos((string) $pattern, '.') + 1);
                $pattern = substr((string) $pattern, 0, -strlen((string) $end) - 1);
            }

            if (false !== strpos((string) $pattern, ',')) {
                $parts = explode(',', $pattern);
                foreach ($parts as $key => $part) {
                    switch ($part) {
                        case '#':
                        case '-#':
                            if ('-' === $part[0]) {
                                $regex[$pkey] .= '['.$symbols['minus'].'-]{0,1}';
                            } else {
                                $regex[$pkey] .= '['.$symbols['plus'].'+]{0,1}';
                            }

                            if ('##0' == $parts[$key + 1]) {
                                $regex[$pkey] .= '[0-9]{1,3}';
                            } elseif ('##' == $parts[$key + 1]) {
                                $regex[$pkey] .= '[0-9]{1,2}';
                            } else {
                                throw new Zend_Locale_Exception('Unsupported token for numberformat (Pos 1):"'.$pattern.'"');
                            }
                            break;
                        case '##':
                            if ('##0' == $parts[$key + 1]) {
                                $regex[$pkey] .= '(\\'.$symbols['group'].'{0,1}[0-9]{2})*';
                            } else {
                                throw new Zend_Locale_Exception('Unsupported token for numberformat (Pos 2):"'.$pattern.'"');
                            }
                            break;
                        case '##0':
                            if ('##' == $parts[$key - 1]) {
                                $regex[$pkey] .= '[0-9]';
                            } elseif (('#' == $parts[$key - 1]) || ('-#' == $parts[$key - 1])) {
                                $regex[$pkey] .= '(\\'.$symbols['group'].'{0,1}[0-9]{3})*';
                            } else {
                                throw new Zend_Locale_Exception('Unsupported token for numberformat (Pos 3):"'.$pattern.'"');
                            }
                            break;
                        case '#0':
                            if (0 == $key) {
                                $regex[$pkey] .= '[0-9]*';
                            } else {
                                throw new Zend_Locale_Exception('Unsupported token for numberformat (Pos 4):"'.$pattern.'"');
                            }
                            break;
                    }
                }
            }

            if (false !== strpos((string) $pattern, 'E')) {
                if (('#E0' == $pattern) || ('#E00' == $pattern)) {
                    $regex[$pkey] .= '['.$symbols['plus'].'+]{0,1}[0-9]{1,}(\\'.$symbols['decimal'].'[0-9]{1,})*[eE]['.$symbols['plus'].'+]{0,1}[0-9]{1,}';
                } elseif (('-#E0' == $pattern) || ('-#E00' == $pattern)) {
                    $regex[$pkey] .= '['.$symbols['minus'].'-]{0,1}[0-9]{1,}(\\'.$symbols['decimal'].'[0-9]{1,})*[eE]['.$symbols['minus'].'-]{0,1}[0-9]{1,}';
                } else {
                    throw new Zend_Locale_Exception('Unsupported token for numberformat (Pos 5):"'.$pattern.'"');
                }
            }

            if (!empty($end)) {
                if ('###' == $end) {
                    $regex[$pkey] .= '(\\'.$symbols['decimal'].'{1}[0-9]{1,}){0,1}';
                } elseif ('###-' == $end) {
                    $regex[$pkey] .= '(\\'.$symbols['decimal'].'{1}[0-9]{1,}){0,1}['.$symbols['minus'].'-]';
                } else {
                    throw new Zend_Locale_Exception('Unsupported token for numberformat (Pos 6):"'.$pattern.'"');
                }
            }

            $regex[$pkey] .= '$/u';
        }

        return $regex;
    }

    /**
     * Alias for getNumber.
     *
     * @param string $input   Number to localize
     * @param array  $options Options: locale, precision. See {@link setOptions()} for details.
     *
     * @return float
     *
     * @throws Zend_Locale_Exception
     */
    public static function getFloat($input, array $options = [])
    {
        return (float) self::getNumber($input, $options);
    }

    /**
     * Returns a locale formatted integer number
     * Alias for toNumber().
     *
     * @param string $value   Number to normalize
     * @param array  $options Options: locale, precision. See {@link setOptions()} for details.
     *
     * @return string Locale formatted number
     *
     * @throws Zend_Locale_Exception
     */
    public static function toFloat($value, array $options = [])
    {
        $options['number_format'] = Zend_Locale_Format::STANDARD;

        return self::toNumber($value, $options);
    }

    /**
     * Returns if a float was found
     * Alias for isNumber().
     *
     * @param string $value   Localized number string
     * @param array  $options Options: locale. See {@link setOptions()} for details.
     *
     * @return bool Returns true if a number was found
     *
     * @throws Zend_Locale_Exception
     */
    public static function isFloat($value, array $options = [])
    {
        return self::isNumber($value, $options);
    }

    /**
     * Returns the first found integer from an string
     * Parsing depends on given locale (grouping and decimal).
     *
     * Examples for input:
     * '  2345.4356,1234' = 23455456
     * '+23,3452.123' = 233452
     * ' 12343 ' = 12343
     * '-9456km' = -9456
     * '0' = 0
     * '(-){0,1}(\d+(\.){0,1})*(\,){0,1})\d+'
     *
     * @param string $input   Input string to parse for numbers
     * @param array  $options Options: locale. See {@link setOptions()} for details.
     *
     * @return int Returns the extracted number
     *
     * @throws Zend_Locale_Exception
     */
    public static function getInteger($input, array $options = [])
    {
        $options['precision'] = 0;

        return (int) self::getFloat($input, $options);
    }

    /**
     * Returns a localized number.
     *
     * @param string $value   Number to normalize
     * @param array  $options Options: locale. See {@link setOptions()} for details.
     *
     * @return string Locale formatted number
     *
     * @throws Zend_Locale_Exception
     */
    public static function toInteger($value, array $options = [])
    {
        $options['precision'] = 0;
        $options['number_format'] = Zend_Locale_Format::STANDARD;

        return self::toNumber($value, $options);
    }

    /**
     * Returns if a integer was found.
     *
     * @param string $value   Localized number string
     * @param array  $options Options: locale. See {@link setOptions()} for details.
     *
     * @return bool Returns true if a integer was found
     *
     * @throws Zend_Locale_Exception
     */
    public static function isInteger($value, array $options = [])
    {
        if (!self::isNumber($value, $options)) {
            return false;
        }

        if (self::getInteger($value, $options) == self::getFloat($value, $options)) {
            return true;
        }

        return false;
    }

    /**
     * Converts a format string from PHP's date format to ISO format
     * Remember that Zend Date always returns localized string, so a month name which returns the english
     * month in php's date() will return the translated month name with this function... use 'en' as locale
     * if you are in need of the original english names.
     *
     * The conversion has the following restrictions:
     * 'a', 'A' - Meridiem is not explicit upper/lowercase, you have to upper/lowercase the translated value yourself
     *
     * @param string $format Format string in PHP's date format
     *
     * @return string Format string in ISO format
     */
    public static function convertPhpToIsoFormat($format)
    {
        if (null === $format) {
            return null;
        }

        $convert = [
            'd' => 'dd', 'D' => 'EE', 'j' => 'd', 'l' => 'EEEE',
            'N' => 'eee', 'S' => 'SS', 'w' => 'e', 'z' => 'D',
            'W' => 'ww', 'F' => 'MMMM', 'm' => 'MM', 'M' => 'MMM',
            'n' => 'M', 't' => 'ddd', 'L' => 'l', 'o' => 'YYYY',
            'Y' => 'yyyy', 'y' => 'yy', 'a' => 'a', 'A' => 'a',
            'B' => 'B', 'g' => 'h', 'G' => 'H', 'h' => 'hh',
            'H' => 'HH', 'i' => 'mm', 's' => 'ss', 'e' => 'zzzz',
            'I' => 'I', 'O' => 'Z', 'P' => 'ZZZZ', 'T' => 'z',
            'Z' => 'X', 'c' => 'yyyy-MM-ddTHH:mm:ssZZZZ', 'r' => 'r',
            'U' => 'U',
        ];
        $escaped = false;
        $inEscapedString = false;
        $converted = [];
        foreach (str_split($format) as $char) {
            if (!$escaped && '\\' == $char) {
                // Next char will be escaped: let's remember it
                $escaped = true;
            } elseif ($escaped) {
                if (!$inEscapedString) {
                    // First escaped string: start the quoted chunk
                    $converted[] = "'";
                    $inEscapedString = true;
                }
                // Since the previous char was a \ and we are in the quoted
                // chunk, let's simply add $char as it is
                $converted[] = $char;
                $escaped = false;
            } elseif ("'" == $char) {
                // Single quotes need to be escaped like this
                $converted[] = "''";
            } else {
                if ($inEscapedString) {
                    // Close the single-quoted chunk
                    $converted[] = "'";
                    $inEscapedString = false;
                }
                // Convert the unescaped char if needed
                if (isset($convert[$char])) {
                    $converted[] = $convert[$char];
                } else {
                    $converted[] = $char;
                }
            }
        }

        return implode($converted);
    }

    /**
     * Parse date and split in named array fields.
     *
     * @param string $date    Date string to parse
     * @param array  $options Options: format_type, fix_date, locale, date_format. See {@link setOptions()} for details.
     *
     * @return array Possible array members: day, month, year, hour, minute, second, fixed, format
     *
     * @throws Zend_Locale_Exception
     */
    private static function _parseDate($date, $options)
    {
        if (!self::_getUniCodeSupport()) {
            trigger_error('Sorry, your PCRE extension does not support UTF8 which is needed for the I18N core', E_USER_NOTICE);
        }

        $options = self::_checkOptions($options) + self::$_options;
        $test = ['h', 'H', 'm', 's', 'y', 'Y', 'M', 'd', 'D', 'E', 'S', 'l', 'B', 'I',
            'X', 'r', 'U', 'G', 'w', 'e', 'a', 'A', 'Z', 'z', 'v'];

        $format = $options['date_format'];
        $number = $date; // working copy
        $result['date_format'] = $format; // save the format used to normalize $number (convenience)
        $result['locale'] = $options['locale']; // save the locale used to normalize $number (convenience)

        $oenc = self::_getEncoding();
        self::_setEncoding('UTF-8');
        $day = iconv_strpos($format, 'd');
        $month = iconv_strpos($format, 'M');
        $year = iconv_strpos($format, 'y');
        $hour = iconv_strpos($format, 'H');
        $min = iconv_strpos($format, 'm');
        $sec = iconv_strpos($format, 's');
        $am = null;
        if (false === $hour) {
            $hour = iconv_strpos($format, 'h');
        }
        if (false === $year) {
            $year = iconv_strpos($format, 'Y');
        }
        if (false === $day) {
            $day = iconv_strpos($format, 'E');
            if (false === $day) {
                $day = iconv_strpos($format, 'D');
            }
        }

        if (false !== $day) {
            $parse[$day] = 'd';
            if (!empty($options['locale']) && ('root' !== $options['locale'])
                && (!is_object($options['locale']) || ('root' !== (string) $options['locale']))) {
                // erase day string
                $daylist = Zend_Locale_Data::getList($options['locale'], 'day');
                foreach ($daylist as $key => $name) {
                    if (false !== iconv_strpos($number, $name)) {
                        $number = str_replace((string) $name, 'EEEE', $number);
                        break;
                    }
                }
            }
        }
        $position = false;

        if (false !== $month) {
            $parse[$month] = 'M';
            if (!empty($options['locale']) && ('root' !== $options['locale'])
                && (!is_object($options['locale']) || ('root' !== (string) $options['locale']))) {
                // prepare to convert month name to their numeric equivalents, if requested,
                // and we have a $options['locale']
                $position = self::_replaceMonth($number, Zend_Locale_Data::getList($options['locale'],
                    'month'));
                if (false === $position) {
                    $position = self::_replaceMonth($number, Zend_Locale_Data::getList($options['locale'],
                        'month', ['gregorian', 'format', 'abbreviated']));
                }
            }
        }
        if (false !== $year) {
            $parse[$year] = 'y';
        }
        if (false !== $hour) {
            $parse[$hour] = 'H';
        }
        if (false !== $min) {
            $parse[$min] = 'm';
        }
        if (false !== $sec) {
            $parse[$sec] = 's';
        }

        if (empty($parse)) {
            self::_setEncoding($oenc);
            // require_once 'Zend/Locale/Exception.php';
            throw new Zend_Locale_Exception("Unknown date format, neither date nor time in '".$format."' found");
        }
        ksort($parse);

        // get daytime
        if (false !== iconv_strpos($format, 'a')) {
            if (false !== iconv_strpos(strtoupper((string) $number), strtoupper((string) Zend_Locale_Data::getContent($options['locale'], 'am')))) {
                $am = true;
            } elseif (false !== iconv_strpos(strtoupper((string) $number), strtoupper((string) Zend_Locale_Data::getContent($options['locale'], 'pm')))) {
                $am = false;
            }
        }

        // split number parts
        $split = false;
        preg_match_all('/\d+/u', $number, $splitted);

        if (0 === count($splitted[0])) {
            self::_setEncoding($oenc);
            // require_once 'Zend/Locale/Exception.php';
            throw new Zend_Locale_Exception("No date part in '$date' found.");
        }
        if (1 === count($splitted[0])) {
            $split = 0;
        }
        $cnt = 0;
        foreach ($parse as $key => $value) {
            switch ($value) {
                case 'd':
                    if (false === $split) {
                        if (count($splitted[0]) > $cnt) {
                            $result['day'] = $splitted[0][$cnt];
                        }
                    } else {
                        // iconv_substr changes since 7.0.11
                        if (iconv_strlen($splitted[0][0]) <= $split) {
                            $result['day'] = false;
                        } else {
                            $result['day'] = iconv_substr((string) $splitted[0][0], $split, 2);
                        }
                        $split += 2;
                    }
                    ++$cnt;
                    break;
                case 'M':
                    if (false === $split) {
                        if (count($splitted[0]) > $cnt) {
                            $result['month'] = $splitted[0][$cnt];
                        }
                    } else {
                        // iconv_substr changes since 7.0.11
                        if (iconv_strlen($splitted[0][0]) <= $split) {
                            $result['month'] = false;
                        } else {
                            $result['month'] = iconv_substr((string) $splitted[0][0], $split, 2);
                        }
                        $split += 2;
                    }
                    ++$cnt;
                    break;
                case 'y':
                    $length = 2;
                    if (('yyyy' == iconv_substr((string) $format, $year, 4))
                     || ('YYYY' == iconv_substr((string) $format, $year, 4))) {
                        $length = 4;
                    }

                    if (false === $split) {
                        if (count($splitted[0]) > $cnt) {
                            $result['year'] = $splitted[0][$cnt];
                        }
                    } else {
                        // iconv_substr changes since 7.0.11
                        if (iconv_strlen($splitted[0][0]) <= $split) {
                            $result['year'] = false;
                        } else {
                            $result['year'] = iconv_substr((string) $splitted[0][0], $split, $length);
                        }
                        $split += $length;
                    }

                    ++$cnt;
                    break;
                case 'H':
                    if (false === $split) {
                        if (count($splitted[0]) > $cnt) {
                            $result['hour'] = $splitted[0][$cnt];
                        }
                    } else {
                        // iconv_substr changes since 7.0.11
                        if (iconv_strlen($splitted[0][0]) <= $split) {
                            $result['hour'] = false;
                        } else {
                            $result['hour'] = iconv_substr((string) $splitted[0][0], $split, 2);
                        }
                        $split += 2;
                    }
                    ++$cnt;
                    break;
                case 'm':
                    if (false === $split) {
                        if (count($splitted[0]) > $cnt) {
                            $result['minute'] = $splitted[0][$cnt];
                        }
                    } else {
                        // iconv_substr changes since 7.0.11 */
                        if (iconv_strlen($splitted[0][0]) <= $split) {
                            $result['minute'] = false;
                        } else {
                            $result['minute'] = iconv_substr((string) $splitted[0][0], $split, 2);
                        }
                        $split += 2;
                    }
                    ++$cnt;
                    break;
                case 's':
                    if (false === $split) {
                        if (count($splitted[0]) > $cnt) {
                            $result['second'] = $splitted[0][$cnt];
                        }
                    } else {
                        // iconv_substr changes since 7.0.11
                        if (iconv_strlen($splitted[0][0]) <= $split) {
                            $result['second'] = false;
                        } else {
                            $result['second'] = iconv_substr((string) $splitted[0][0], $split, 2);
                        }
                        $split += 2;
                    }
                    ++$cnt;
                    break;
            }
        }

        // AM/PM correction
        if (false !== $hour) {
            if ((true === $am) && (12 == $result['hour'])) {
                $result['hour'] = 0;
            } elseif ((false === $am) && (12 != $result['hour'])) {
                $result['hour'] += 12;
            }
        }

        if (true === $options['fix_date']) {
            $result['fixed'] = 0; // nothing has been "fixed" by swapping date parts around (yet)
        }

        if (false !== $day) {
            // fix false month
            if (isset($result['day']) && isset($result['month'])) {
                if ((false !== $position) && ((false === iconv_strpos($date, $result['day']))
                                               || (isset($result['year']) && (false === iconv_strpos($date, $result['year']))))) {
                    if (true !== $options['fix_date']) {
                        self::_setEncoding($oenc);
                        // require_once 'Zend/Locale/Exception.php';
                        throw new Zend_Locale_Exception("Unable to parse date '$date' using '".$format."' (false month, $position, $month)");
                    }
                    $temp = $result['day'];
                    $result['day'] = $result['month'];
                    $result['month'] = $temp;
                    $result['fixed'] = 1;
                }
            }

            // fix switched values d <> y
            if (isset($result['day'], $result['year']) && $result['day'] > 31) {
                if (true !== $options['fix_date']) {
                    self::_setEncoding($oenc);
                    // require_once 'Zend/Locale/Exception.php';
                    throw new Zend_Locale_Exception("Unable to parse date '$date' using '".$format."' (d <> y)");
                }
                $temp = $result['year'];
                $result['year'] = $result['day'];
                $result['day'] = $temp;
                $result['fixed'] = 2;
            }

            // fix switched values M <> y
            if (isset($result['month'], $result['year'])) {
                if ($result['month'] > 31) {
                    if (true !== $options['fix_date']) {
                        self::_setEncoding($oenc);
                        // require_once 'Zend/Locale/Exception.php';
                        throw new Zend_Locale_Exception("Unable to parse date '$date' using '".$format."' (M <> y)");
                    }
                    $temp = $result['year'];
                    $result['year'] = $result['month'];
                    $result['month'] = $temp;
                    $result['fixed'] = 3;
                }
            }

            // fix switched values M <> d
            if (isset($result['month']) and isset($result['day'])) {
                if ($result['month'] > 12) {
                    if (true !== $options['fix_date'] || $result['month'] > 31) {
                        self::_setEncoding($oenc);
                        // require_once 'Zend/Locale/Exception.php';
                        throw new Zend_Locale_Exception("Unable to parse date '$date' using '".$format."' (M <> d)");
                    }
                    $temp = $result['day'];
                    $result['day'] = $result['month'];
                    $result['month'] = $temp;
                    $result['fixed'] = 4;
                }
            }
        }

        if (isset($result['year'])) {
            if (((2 === iconv_strlen($result['year'])) && ($result['year'] < 10))
                || (((false !== iconv_strpos($format, 'yy')) && (false === iconv_strpos($format, 'yyyy')))
                || ((false !== iconv_strpos($format, 'YY')) && (false === iconv_strpos($format, 'YYYY'))))) {
                if (($result['year'] >= 0) && ($result['year'] < 100)) {
                    if ($result['year'] < 70) {
                        $result['year'] = (int) $result['year'] + 100;
                    }

                    $result['year'] = (int) $result['year'] + 1900;
                }
            }
        }

        self::_setEncoding($oenc);

        return $result;
    }

    /**
     * Search $number for a month name found in $monthlist, and replace if found.
     *
     * @param string $number    Date string (modified)
     * @param array  $monthlist List of month names
     *
     * @return int|false Position of replaced string (false if nothing replaced)
     */
    protected static function _replaceMonth(&$number, $monthlist)
    {
        // If $locale was invalid, $monthlist will default to a "root" identity
        // mapping for each month number from 1 to 12.
        // If no $locale was given, or $locale was invalid, do not use this identity mapping to normalize.
        // Otherwise, translate locale aware month names in $number to their numeric equivalents.
        if ($monthlist && 1 != $monthlist[1]) {
            foreach ($monthlist as $key => $name) {
                if (($position = iconv_strpos($number, $name, 0, 'UTF-8')) !== false) {
                    $number = str_ireplace($name, $key, $number);

                    return $position;
                }
            }
        }

        return false;
    }

    /**
     * Returns the default date format for $locale.
     *
     * @param string|Zend_Locale $locale OPTIONAL Locale of $number, possibly in string form (e.g. 'de_AT')
     *
     * @return string format
     *
     * @throws Zend_Locale_Exception throws an exception when locale data is broken
     */
    public static function getDateFormat($locale = null)
    {
        $format = Zend_Locale_Data::getContent($locale, 'date');
        if (empty($format)) {
            // require_once 'Zend/Locale/Exception.php';
            throw new Zend_Locale_Exception("failed to receive data from locale $locale");
        }

        return $format;
    }

    /**
     * Returns an array with the normalized date from an locale date
     * a input of 10.01.2006 without a $locale would return:
     * array ('day' => 10, 'month' => 1, 'year' => 2006)
     * The 'locale' option is only used to convert human readable day
     * and month names to their numeric equivalents.
     * The 'format' option allows specification of self-defined date formats,
     * when not using the default format for the 'locale'.
     *
     * @param string $date    Date string
     * @param array  $options Options: format_type, fix_date, locale, date_format. See {@link setOptions()} for details.
     *
     * @return array Possible array members: day, month, year, hour, minute, second, fixed, format
     *
     * @throws Zend_Locale_Exception
     */
    public static function getDate($date, array $options = [])
    {
        $options = self::_checkOptions($options) + self::$_options;
        if (empty($options['date_format'])) {
            $options['format_type'] = 'iso';
            $options['date_format'] = self::getDateFormat($options['locale']);
        }

        return self::_parseDate($date, $options);
    }

    /**
     * Returns if the given datestring contains all date parts from the given format.
     * If no format is given, the default date format from the locale is used
     * If you want to check if the date is a proper date you should use Zend_Date::isDate().
     *
     * @param string $date    Date string
     * @param array  $options Options: format_type, fix_date, locale, date_format. See {@link setOptions()} for details.
     *
     * @return bool
     *
     * @throws Zend_Locale_Exception
     */
    public static function checkDateFormat($date, array $options = [])
    {
        try {
            $date = self::getDate($date, $options);
        } catch (Throwable $e) {
            return false;
        }

        if (empty($options['date_format'])) {
            $options['format_type'] = 'iso';
            $options['date_format'] = self::getDateFormat(isset($options['locale']) ? $options['locale'] : null);
        }
        $options = self::_checkOptions($options) + self::$_options;

        // day expected but not parsed
        if ((false !== iconv_strpos($options['date_format'], 'd', 0, 'UTF-8')) and (!isset($date['day']) or ('' === $date['day']))) {
            return false;
        }

        // month expected but not parsed
        if ((false !== iconv_strpos($options['date_format'], 'M', 0, 'UTF-8')) and (!isset($date['month']) or ('' === $date['month']))) {
            return false;
        }

        // year expected but not parsed
        if (((false !== iconv_strpos($options['date_format'], 'Y', 0, 'UTF-8'))
             or (false !== iconv_strpos($options['date_format'], 'y', 0, 'UTF-8'))) and (!isset($date['year']) or ('' === $date['year']))) {
            return false;
        }

        // second expected but not parsed
        if ((false !== iconv_strpos($options['date_format'], 's', 0, 'UTF-8')) and (!isset($date['second']) or ('' === $date['second']))) {
            return false;
        }

        // minute expected but not parsed
        if ((false !== iconv_strpos($options['date_format'], 'm', 0, 'UTF-8')) and (!isset($date['minute']) or ('' === $date['minute']))) {
            return false;
        }

        // hour expected but not parsed
        if (((false !== iconv_strpos($options['date_format'], 'H', 0, 'UTF-8'))
             or (false !== iconv_strpos($options['date_format'], 'h', 0, 'UTF-8'))) and (!isset($date['hour']) or ('' === $date['hour']))) {
            return false;
        }

        return true;
    }

    /**
     * Returns the default time format for $locale.
     *
     * @param string|Zend_Locale $locale OPTIONAL Locale of $number, possibly in string form (e.g. 'de_AT')
     *
     * @return string format
     *
     * @throws Zend_Locale_Exception
     */
    public static function getTimeFormat($locale = null)
    {
        $format = Zend_Locale_Data::getContent($locale, 'time');
        if (empty($format)) {
            // require_once 'Zend/Locale/Exception.php';
            throw new Zend_Locale_Exception("failed to receive data from locale $locale");
        }

        return $format;
    }

    /**
     * Returns an array with 'hour', 'minute', and 'second' elements extracted from $time
     * according to the order described in $format.  For a format of 'H:i:s', and
     * an input of 11:20:55, getTime() would return:
     * array ('hour' => 11, 'minute' => 20, 'second' => 55)
     * The optional $locale parameter may be used to help extract times from strings
     * containing both a time and a day or month name.
     *
     * @param string $time    Time string
     * @param array  $options Options: format_type, fix_date, locale, date_format. See {@link setOptions()} for details.
     *
     * @return array Possible array members: day, month, year, hour, minute, second, fixed, format
     *
     * @throws Zend_Locale_Exception
     */
    public static function getTime($time, array $options = [])
    {
        $options = self::_checkOptions($options) + self::$_options;
        if (empty($options['date_format'])) {
            $options['format_type'] = 'iso';
            $options['date_format'] = self::getTimeFormat($options['locale']);
        }

        return self::_parseDate($time, $options);
    }

    /**
     * Returns the default datetime format for $locale.
     *
     * @param string|Zend_Locale $locale OPTIONAL Locale of $number, possibly in string form (e.g. 'de_AT')
     *
     * @return string format
     *
     * @throws Zend_Locale_Exception
     */
    public static function getDateTimeFormat($locale = null)
    {
        $format = Zend_Locale_Data::getContent($locale, 'datetime');
        if (empty($format)) {
            // require_once 'Zend/Locale/Exception.php';
            throw new Zend_Locale_Exception("failed to receive data from locale $locale");
        }

        return $format;
    }

    /**
     * Returns an array with 'year', 'month', 'day', 'hour', 'minute', and 'second' elements
     * extracted from $datetime according to the order described in $format.  For a format of 'd.M.y H:i:s',
     * and an input of 10.05.1985 11:20:55, getDateTime() would return:
     * array ('year' => 1985, 'month' => 5, 'day' => 10, 'hour' => 11, 'minute' => 20, 'second' => 55)
     * The optional $locale parameter may be used to help extract times from strings
     * containing both a time and a day or month name.
     *
     * @param string $datetime DateTime string
     * @param array  $options  Options: format_type, fix_date, locale, date_format. See {@link setOptions()} for details.
     *
     * @return array Possible array members: day, month, year, hour, minute, second, fixed, format
     *
     * @throws Zend_Locale_Exception
     */
    public static function getDateTime($datetime, array $options = [])
    {
        $options = self::_checkOptions($options) + self::$_options;
        if (empty($options['date_format'])) {
            $options['format_type'] = 'iso';
            $options['date_format'] = self::getDateTimeFormat($options['locale']);
        }

        return self::_parseDate($datetime, $options);
    }

    /**
     * Internal method to detect of Unicode supports UTF8
     * which should be enabled within vanilla php installations.
     *
     * @return bool
     */
    protected static function _getUniCodeSupport()
    {
        return (@preg_match('/\pL/u', 'a')) ? true : false;
    }

    /**
     * Internal method to retrieve the current encoding via the ini setting
     * default_charset for PHP >= 5.6 or iconv_get_encoding otherwise.
     *
     * @return string
     */
    protected static function _getEncoding()
    {
        $oenc = PHP_VERSION_ID < 50600
            ? iconv_get_encoding('internal_encoding')
            : ini_get('default_charset');

        return $oenc;
    }

    /**
     * Internal method to set the encoding via the ini setting
     * default_charset for PHP >= 5.6 or iconv_set_encoding otherwise.
     *
     * @param string $encoding
     *
     * @return void
     */
    protected static function _setEncoding($encoding)
    {
        if (PHP_VERSION_ID < 50600) {
            iconv_set_encoding('internal_encoding', $encoding);
        } else {
            ini_set('default_charset', $encoding);
        }
    }
}
