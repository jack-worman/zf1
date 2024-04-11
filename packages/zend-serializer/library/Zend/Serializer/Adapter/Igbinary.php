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

/* @see Zend_Serializer_Adapter_AdapterAbstract */
// require_once 'Zend/Serializer/Adapter/AdapterAbstract.php';
use MongoDB\BSON\Binary;

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Serializer_Adapter_Igbinary extends Zend_Serializer_Adapter_AdapterAbstract
{
    /**
     * @var string Serialized null value
     */
    private static $_serializedNull;

    /**
     * Constructor.
     *
     * @param array|Zend_Config $opts
     *
     * @return void
     *
     * @throws Zend_Serializer_Exception If igbinary extension is not present
     */
    public function __construct($opts = [])
    {
        if (!extension_loaded('igbinary')) {
            // require_once 'Zend/Serializer/Exception.php';
            throw new Zend_Serializer_Exception('PHP extension "igbinary" is required for this adapter');
        }

        parent::__construct($opts);

        if (null === self::$_serializedNull) {
            self::$_serializedNull = igbinary_serialize(null);
        }
    }

    /**
     * Serialize PHP value to igbinary.
     *
     * @return string
     *
     * @throws Zend_Serializer_Exception on igbinary error
     */
    public function serialize($value, array $opts = [])
    {
        $ret = igbinary_serialize($value);
        if (false === $ret) {
            $lastErr = error_get_last();
            // require_once 'Zend/Serializer/Exception.php';
            throw new Zend_Serializer_Exception($lastErr['message']);
        }

        return $ret;
    }

    /**
     * Deserialize igbinary string to PHP value.
     *
     * @param string|Binary $serialized
     *
     * @throws Zend_Serializer_Exception on igbinary error
     */
    public function unserialize($serialized, array $opts = [])
    {
        $ret = igbinary_unserialize($serialized);
        if (null === $ret && $serialized !== self::$_serializedNull) {
            $lastErr = error_get_last();
            // require_once 'Zend/Serializer/Exception.php';
            throw new Zend_Serializer_Exception($lastErr['message']);
        }

        return $ret;
    }
}
