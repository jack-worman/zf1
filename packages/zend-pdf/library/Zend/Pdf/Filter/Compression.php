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

/** Zend_Pdf_Filter_Interface */
// require_once 'Zend/Pdf/Filter/Interface.php';

/**
 * ASCII85 stream filter.
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_Pdf_Filter_Compression implements Zend_Pdf_Filter_Interface
{
    /**
     * Paeth prediction function.
     *
     * @param int $a
     * @param int $b
     * @param int $c
     *
     * @return int
     */
    private static function _paeth($a, $b, $c)
    {
        // $a - left, $b - above, $c - upper left
        $p = $a + $b - $c;       // initial estimate
        $pa = abs($p - $a);       // distances to a, b, c
        $pb = abs($p - $b);
        $pc = abs($p - $c);

        // return nearest of a,b,c,
        // breaking ties in order a,b,c.
        if ($pa <= $pb && $pa <= $pc) {
            return $a;
        } elseif ($pb <= $pc) {
            return $b;
        } else {
            return $c;
        }
    }

    /**
     * Get Predictor decode param value.
     *
     * @param array $params
     *
     * @return int
     *
     * @throws Zend_Pdf_Exception
     */
    private static function _getPredictorValue(&$params)
    {
        if (isset($params['Predictor'])) {
            $predictor = $params['Predictor'];

            if (1 != $predictor && 2 != $predictor
                && 10 != $predictor && 11 != $predictor && 12 != $predictor
                && 13 != $predictor && 14 != $predictor && 15 != $predictor) {
                // require_once 'Zend/Pdf/Exception.php';
                throw new Zend_Pdf_Exception('Invalid value of \'Predictor\' decode param - '.$predictor.'.');
            }

            return $predictor;
        } else {
            return 1;
        }
    }

    /**
     * Get Colors decode param value.
     *
     * @param array $params
     *
     * @return int
     *
     * @throws Zend_Pdf_Exception
     */
    private static function _getColorsValue(&$params)
    {
        if (isset($params['Colors'])) {
            $colors = $params['Colors'];

            if (1 != $colors && 2 != $colors && 3 != $colors && 4 != $colors) {
                // require_once 'Zend/Pdf/Exception.php';
                throw new Zend_Pdf_Exception('Invalid value of \'Color\' decode param - '.$colors.'.');
            }

            return $colors;
        } else {
            return 1;
        }
    }

    /**
     * Get BitsPerComponent decode param value.
     *
     * @param array $params
     *
     * @return int
     *
     * @throws Zend_Pdf_Exception
     */
    private static function _getBitsPerComponentValue(&$params)
    {
        if (isset($params['BitsPerComponent'])) {
            $bitsPerComponent = $params['BitsPerComponent'];

            if (1 != $bitsPerComponent && 2 != $bitsPerComponent
                && 4 != $bitsPerComponent && 8 != $bitsPerComponent
                && 16 != $bitsPerComponent) {
                // require_once 'Zend/Pdf/Exception.php';
                throw new Zend_Pdf_Exception('Invalid value of \'BitsPerComponent\' decode param - '.$bitsPerComponent.'.');
            }

            return $bitsPerComponent;
        } else {
            return 8;
        }
    }

    /**
     * Get Columns decode param value.
     *
     * @param array $params
     *
     * @return int
     */
    private static function _getColumnsValue(&$params)
    {
        if (isset($params['Columns'])) {
            return $params['Columns'];
        } else {
            return 1;
        }
    }

    /**
     * Convert stream data according to the filter params set before encoding.
     *
     * @param string $data
     * @param array  $params
     *
     * @return string
     *
     * @throws Zend_Pdf_Exception
     */
    protected static function _applyEncodeParams($data, $params)
    {
        $predictor = self::_getPredictorValue($params);
        $colors = self::_getColorsValue($params);
        $bitsPerComponent = self::_getBitsPerComponentValue($params);
        $columns = self::_getColumnsValue($params);

        /* None of prediction */
        if (1 == $predictor) {
            return $data;
        }

        /* TIFF Predictor 2 */
        if (2 == $predictor) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('Not implemented yet');
        }

        /* Optimal PNG prediction */
        if (15 == $predictor) {
            /** Use Paeth prediction as optimal */
            $predictor = 14;
        }

        /* PNG prediction */
        if (10 == $predictor  /* None of prediction */
            || 11 == $predictor  /* Sub prediction */
            || 12 == $predictor  /* Up prediction */
            || 13 == $predictor  /* Average prediction */
            || 14 == $predictor     /* Paeth prediction */
        ) {
            $predictor -= 10;

            if (16 == $bitsPerComponent) {
                // require_once 'Zend/Pdf/Exception.php';
                throw new Zend_Pdf_Exception('PNG Prediction with bit depth greater than 8 not yet supported.');
            }

            $bitsPerSample = $bitsPerComponent * $colors;
            $bytesPerSample = (int) (($bitsPerSample + 7) / 8);           // (int)ceil(...) emulation
            $bytesPerRow = (int) (($bitsPerSample * $columns + 7) / 8);  // (int)ceil(...) emulation
            $rows = strlen((string) $data) / $bytesPerRow;
            $output = '';
            $offset = 0;

            if (!is_integer($rows)) {
                // require_once 'Zend/Pdf/Exception.php';
                throw new Zend_Pdf_Exception('Wrong data length.');
            }

            switch ($predictor) {
                case 0: // None of prediction
                    for ($count = 0; $count < $rows; ++$count) {
                        $output .= chr($predictor);
                        $output .= substr((string) $data, $offset, $bytesPerRow);
                        $offset += $bytesPerRow;
                    }
                    break;

                case 1: // Sub prediction
                    for ($count = 0; $count < $rows; ++$count) {
                        $output .= chr($predictor);

                        $lastSample = array_fill(0, $bytesPerSample, 0);
                        for ($count2 = 0; $count2 < $bytesPerRow; ++$count2) {
                            $newByte = ord((string) $data[$offset++]);
                            // Note. chr() automatically cuts input to 8 bit
                            $output .= chr($newByte - $lastSample[$count2 % $bytesPerSample]);
                            $lastSample[$count2 % $bytesPerSample] = $newByte;
                        }
                    }
                    break;

                case 2: // Up prediction
                    $lastRow = array_fill(0, $bytesPerRow, 0);
                    for ($count = 0; $count < $rows; ++$count) {
                        $output .= chr($predictor);

                        for ($count2 = 0; $count2 < $bytesPerRow; ++$count2) {
                            $newByte = ord((string) $data[$offset++]);
                            // Note. chr() automatically cuts input to 8 bit
                            $output .= chr($newByte - $lastRow[$count2]);
                            $lastRow[$count2] = $newByte;
                        }
                    }
                    break;

                case 3: // Average prediction
                    $lastRow = array_fill(0, $bytesPerRow, 0);
                    for ($count = 0; $count < $rows; ++$count) {
                        $output .= chr($predictor);

                        $lastSample = array_fill(0, $bytesPerSample, 0);
                        for ($count2 = 0; $count2 < $bytesPerRow; ++$count2) {
                            $newByte = ord((string) $data[$offset++]);
                            // Note. chr() automatically cuts input to 8 bit
                            $output .= chr($newByte - floor(($lastSample[$count2 % $bytesPerSample] + $lastRow[$count2]) / 2));
                            $lastSample[$count2 % $bytesPerSample] = $lastRow[$count2] = $newByte;
                        }
                    }
                    break;

                case 4: // Paeth prediction
                    $lastRow = array_fill(0, $bytesPerRow, 0);
                    $currentRow = [];
                    for ($count = 0; $count < $rows; ++$count) {
                        $output .= chr($predictor);

                        $lastSample = array_fill(0, $bytesPerSample, 0);
                        for ($count2 = 0; $count2 < $bytesPerRow; ++$count2) {
                            $newByte = ord((string) $data[$offset++]);
                            // Note. chr() automatically cuts input to 8 bit
                            $output .= chr($newByte - self::_paeth($lastSample[$count2 % $bytesPerSample],
                                $lastRow[$count2],
                                ($count2 - $bytesPerSample < 0) ?
                                     0 : $lastRow[$count2 - $bytesPerSample]));
                            $lastSample[$count2 % $bytesPerSample] = $currentRow[$count2] = $newByte;
                        }
                        $lastRow = $currentRow;
                    }
                    break;
            }

            return $output;
        }

        // require_once 'Zend/Pdf/Exception.php';
        throw new Zend_Pdf_Exception('Unknown prediction algorithm - '.$predictor.'.');
    }

    /**
     * Convert stream data according to the filter params set after decoding.
     *
     * @param string $data
     * @param array  $params
     *
     * @return string
     */
    protected static function _applyDecodeParams($data, $params)
    {
        $predictor = self::_getPredictorValue($params);
        $colors = self::_getColorsValue($params);
        $bitsPerComponent = self::_getBitsPerComponentValue($params);
        $columns = self::_getColumnsValue($params);

        /* None of prediction */
        if (1 == $predictor) {
            return $data;
        }

        /* TIFF Predictor 2 */
        if (2 == $predictor) {
            // require_once 'Zend/Pdf/Exception.php';
            throw new Zend_Pdf_Exception('Not implemented yet');
        }

        /*
         * PNG prediction
         * Prediction code is duplicated on each row.
         * Thus all cases can be brought to one
         */
        if (10 == $predictor  /* None of prediction */
            || 11 == $predictor  /* Sub prediction */
            || 12 == $predictor  /* Up prediction */
            || 13 == $predictor  /* Average prediction */
            || 14 == $predictor  /* Paeth prediction */
            || 15 == $predictor     /* Optimal prediction */) {
            $bitsPerSample = $bitsPerComponent * $colors;
            $bytesPerSample = ceil($bitsPerSample / 8);
            $bytesPerRow = ceil($bitsPerSample * $columns / 8);
            $rows = ceil(strlen((string) $data) / ($bytesPerRow + 1));
            $output = '';
            $offset = 0;

            $lastRow = array_fill(0, $bytesPerRow, 0);
            for ($count = 0; $count < $rows; ++$count) {
                $lastSample = array_fill(0, $bytesPerSample, 0);
                switch (ord((string) $data[$offset++])) {
                    case 0: // None of prediction
                        $output .= substr((string) $data, $offset, $bytesPerRow);
                        for ($count2 = 0; $count2 < $bytesPerRow && $offset < strlen((string) $data); ++$count2) {
                            $lastSample[$count2 % $bytesPerSample] = $lastRow[$count2] = ord((string) $data[$offset++]);
                        }
                        break;

                    case 1: // Sub prediction
                        for ($count2 = 0; $count2 < $bytesPerRow && $offset < strlen((string) $data); ++$count2) {
                            $decodedByte = (ord((string) $data[$offset++]) + $lastSample[$count2 % $bytesPerSample]) & 0xFF;
                            $lastSample[$count2 % $bytesPerSample] = $lastRow[$count2] = $decodedByte;
                            $output .= chr($decodedByte);
                        }
                        break;

                    case 2: // Up prediction
                        for ($count2 = 0; $count2 < $bytesPerRow && $offset < strlen((string) $data); ++$count2) {
                            $decodedByte = (ord((string) $data[$offset++]) + $lastRow[$count2]) & 0xFF;
                            $lastSample[$count2 % $bytesPerSample] = $lastRow[$count2] = $decodedByte;
                            $output .= chr($decodedByte);
                        }
                        break;

                    case 3: // Average prediction
                        for ($count2 = 0; $count2 < $bytesPerRow && $offset < strlen((string) $data); ++$count2) {
                            $decodedByte = (ord((string) $data[$offset++]) +
                                            floor(($lastSample[$count2 % $bytesPerSample] + $lastRow[$count2]) / 2)
                            ) & 0xFF;
                            $lastSample[$count2 % $bytesPerSample] = $lastRow[$count2] = $decodedByte;
                            $output .= chr($decodedByte);
                        }
                        break;

                    case 4: // Paeth prediction
                        $currentRow = [];
                        for ($count2 = 0; $count2 < $bytesPerRow && $offset < strlen((string) $data); ++$count2) {
                            $decodedByte = (ord((string) $data[$offset++]) +
                                            self::_paeth($lastSample[$count2 % $bytesPerSample],
                                                $lastRow[$count2],
                                                ($count2 - $bytesPerSample < 0) ?
                                                     0 : $lastRow[$count2 - $bytesPerSample])
                            ) & 0xFF;
                            $lastSample[$count2 % $bytesPerSample] = $currentRow[$count2] = $decodedByte;
                            $output .= chr($decodedByte);
                        }
                        $lastRow = $currentRow;
                        break;

                    default:
                        // require_once 'Zend/Pdf/Exception.php';
                        throw new Zend_Pdf_Exception('Unknown prediction tag.');
                }
            }

            return $output;
        }

        // require_once 'Zend/Pdf/Exception.php';
        throw new Zend_Pdf_Exception('Unknown prediction algorithm - '.$predictor.'.');
    }
}
