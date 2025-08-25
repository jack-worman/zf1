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
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
final class Zend_Mail_Header_HeaderValue
{
    /**
     * No public constructor.
     */
    private function __construct()
    {
    }

    /**
     * Filter the header value according to RFC 2822.
     *
     * @see    http://www.rfc-base.org/txt/rfc-2822.txt (section 2.2)
     *
     * @param string $value
     *
     * @return string
     */
    public static function filter($value)
    {
        $result = '';
        $tot = strlen((string) $value);

        // Filter for CR and LF characters, leaving CRLF + WSP sequences for
        // Long Header Fields (section 2.2.3 of RFC 2822)
        for ($i = 0; $i < $tot; ++$i) {
            $ord = ord((string) $value[$i]);
            if (($ord < 32 || $ord > 126)
                && 13 !== $ord
            ) {
                continue;
            }

            if (13 === $ord) {
                if ($i + 2 >= $tot) {
                    continue;
                }

                $lf = ord((string) $value[$i + 1]);
                $sp = ord((string) $value[$i + 2]);

                if (10 !== $lf || 32 !== $sp) {
                    continue;
                }

                $result .= "\r\n ";
                $i += 2;
                continue;
            }

            $result .= $value[$i];
        }

        return $result;
    }

    /**
     * Determine if the header value contains any invalid characters.
     *
     * @see    http://www.rfc-base.org/txt/rfc-2822.txt (section 2.2)
     *
     * @param string $value
     *
     * @return bool
     */
    public static function isValid($value)
    {
        $tot = strlen((string) $value);
        for ($i = 0; $i < $tot; ++$i) {
            $ord = ord((string) $value[$i]);
            if (($ord < 32 || $ord > 126)
                && 13 !== $ord
            ) {
                return false;
            }

            if (13 === $ord) {
                if ($i + 2 >= $tot) {
                    return false;
                }

                $lf = ord((string) $value[$i + 1]);
                $sp = ord((string) $value[$i + 2]);

                if (10 !== $lf || 32 !== $sp) {
                    return false;
                }

                $i += 2;
            }
        }

        return true;
    }

    /**
     * Assert that the header value is valid.
     *
     * Raises an exception if invalid.
     *
     * @param string $value
     *
     * @return void
     *
     * @throws Exception\RuntimeException
     */
    public static function assertValid($value)
    {
        if (!self::isValid($value)) {
            // require_once 'Zend/Mail/Exception.php';
            throw new Zend_Mail_Exception('Invalid header value detected');
        }
    }
}
