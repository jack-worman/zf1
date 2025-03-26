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

/** Zend_Pdf_Filter_Compression */
// require_once 'Zend/Pdf/Filter/Compression.php';

/**
 * LZW stream filter.
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Pdf_Filter_Compression_Lzw extends Zend_Pdf_Filter_Compression
{
    /**
     * Get EarlyChange decode param value.
     *
     * @param array $params
     *
     * @return int
     *
     * @throws Zend_Pdf_Exception
     */
    private static function _getEarlyChangeValue($params)
    {
        if (isset($params['EarlyChange'])) {
            $earlyChange = $params['EarlyChange'];

            if (0 != $earlyChange && 1 != $earlyChange) {
                // require_once 'Zend/Pdf/Exception.php';
                throw new Zend_Pdf_Exception('Invalid value of \'EarlyChange\' decode param - '.$earlyChange.'.');
            }

            return $earlyChange;
        } else {
            return 1;
        }
    }

    /**
     * Encode data.
     *
     * @param string $data
     * @param array  $params
     *
     * @return string
     *
     * @throws Zend_Pdf_Exception
     */
    public static function encode($data, $params = null)
    {
        if (null != $params) {
            $data = self::_applyEncodeParams($data, $params);
        }

        // require_once 'Zend/Pdf/Exception.php';
        throw new Zend_Pdf_Exception('Not implemented yet');
    }

    /**
     * Decode data.
     *
     * @param string $data
     * @param array  $params
     *
     * @return string
     *
     * @throws Zend_Pdf_Exception
     */
    public static function decode($data, $params = null)
    {
        // require_once 'Zend/Pdf/Exception.php';
        throw new Zend_Pdf_Exception('Not implemented yet');
        if (null !== $params) {
            return self::_applyDecodeParams($data, $params);
        } else {
            return $data;
        }
    }
}
