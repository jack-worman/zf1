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
 * Zend_Measure_Flow_Volume.
 */
// require_once 'Zend/Measure/Flow/Volume.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Measure
 */
#[AllowDynamicProperties]
class Zend_Measure_Flow_VolumeTest extends PHPUnit_Framework_TestCase
{
    /**
     * test for Volume initialisation
     * expected instance.
     */
    public function testFlowVolumeInit()
    {
        $value = new Zend_Measure_Flow_Volume('100', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertTrue($value instanceof Zend_Measure_Flow_Volume, 'Zend_Measure_Flow_Volume Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testFlowVolumeUnknownType()
    {
        try {
            $value = new Zend_Measure_Flow_Volume('100', 'Flow_Volume::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testFlowVolumeUnknownValue()
    {
        try {
            $value = new Zend_Measure_Flow_Volume('novalue', Zend_Measure_Flow_Volume::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected root value.
     */
    public function testFlowVolumeUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Flow_Volume('100', Zend_Measure_Flow_Volume::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for standard locale
     * expected integer.
     */
    public function testFlowVolumeNoLocale()
    {
        $value = new Zend_Measure_Flow_Volume('100', Zend_Measure_Flow_Volume::STANDARD);
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Flow_Volume value expected');
    }

    /**
     * test for positive value
     * expected integer.
     */
    public function testFlowVolumeValuePositive()
    {
        $value = new Zend_Measure_Flow_Volume('100', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Flow_Volume value expected to be a positive integer');
    }

    /**
     * test for negative value
     * expected integer.
     */
    public function testFlowVolumeValueNegative()
    {
        $value = new Zend_Measure_Flow_Volume('-100', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Flow_Volume value expected to be a negative integer');
    }

    /**
     * test for decimal value
     * expected float.
     */
    public function testFlowVolumeValueDecimal()
    {
        $value = new Zend_Measure_Flow_Volume('-100,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Flow_Volume value expected to be a decimal value');
    }

    /**
     * test for decimal seperated value
     * expected float.
     */
    public function testFlowVolumeValueDecimalSeperated()
    {
        $value = new Zend_Measure_Flow_Volume('-100.100,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Flow_Volume Object not returned');
    }

    /**
     * test for string with integrated value
     * expected float.
     */
    public function testFlowVolumeValueString()
    {
        $value = new Zend_Measure_Flow_Volume('-100.100,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Flow_Volume Object not returned');
    }

    /**
     * test for equality
     * expected true.
     */
    public function testFlowVolumeEquality()
    {
        $value = new Zend_Measure_Flow_Volume('-100.100,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $newvalue = new Zend_Measure_Flow_Volume('-100.100,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertTrue($value->equals($newvalue), 'Zend_Measure_Flow_Volume Object should be equal');
    }

    /**
     * test for no equality
     * expected false.
     */
    public function testFlowVolumeNoEquality()
    {
        $value = new Zend_Measure_Flow_Volume('-100.100,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $newvalue = new Zend_Measure_Flow_Volume('-100,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertFalse($value->equals($newvalue), 'Zend_Measure_Flow_Volume Object should be not equal');
    }

    /**
     * test for set positive value
     * expected integer.
     */
    public function testFlowVolumeSetPositive()
    {
        $value = new Zend_Measure_Flow_Volume('100', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Flow_Volume value expected to be a positive integer');
    }

    /**
     * test for set negative value
     * expected integer.
     */
    public function testFlowVolumeSetNegative()
    {
        $value = new Zend_Measure_Flow_Volume('-100', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $value->setValue('-200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Flow_Volume value expected to be a negative integer');
    }

    /**
     * test for set decimal value
     * expected float.
     */
    public function testFlowVolumeSetDecimal()
    {
        $value = new Zend_Measure_Flow_Volume('-100,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $value->setValue('-200,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Flow_Volume value expected to be a decimal value');
    }

    /**
     * test for set decimal seperated value
     * expected float.
     */
    public function testFlowVolumeSetDecimalSeperated()
    {
        $value = new Zend_Measure_Flow_Volume('-100.100,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Flow_Volume Object not returned');
    }

    /**
     * test for set string with integrated value
     * expected float.
     */
    public function testFlowVolumeSetString()
    {
        $value = new Zend_Measure_Flow_Volume('-100.100,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Flow_Volume Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testFlowVolumeSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Flow_Volume('100', Zend_Measure_Flow_Volume::STANDARD, 'de');
            $value->setValue('-200.200,200', 'Flow_Volume::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testFlowVolumeSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Flow_Volume('100', Zend_Measure_Flow_Volume::STANDARD, 'de');
            $value->setValue('novalue', Zend_Measure_Flow_Volume::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testFlowVolumeSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Flow_Volume('100', Zend_Measure_Flow_Volume::STANDARD, 'de');
            $value->setValue('200', Zend_Measure_Flow_Volume::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testFlowVolumeSetWithNoLocale()
    {
        $value = new Zend_Measure_Flow_Volume('100', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Flow_Volume::STANDARD);
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Flow_Volume value expected to be a positive integer');
    }

    /**
     * test setting type
     * expected new type.
     */
    public function testFlowVolumeSetType()
    {
        $value = new Zend_Measure_Flow_Volume('-100', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $value->setType(Zend_Measure_Flow_Volume::CUSEC);
        $this->assertEquals(Zend_Measure_Flow_Volume::CUSEC, $value->getType(), 'Zend_Measure_Flow_Volume type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testFlowVolumeSetComputedType1()
    {
        $value = new Zend_Measure_Flow_Volume('-100', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $value->setType(Zend_Measure_Flow_Volume::BARREL_PER_DAY);
        $this->assertEquals(Zend_Measure_Flow_Volume::BARREL_PER_DAY, $value->getType(), 'Zend_Measure_Flow_Volume type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testFlowVolumeSetComputedType2()
    {
        $value = new Zend_Measure_Flow_Volume('-100', Zend_Measure_Flow_Volume::BARREL_PER_DAY, 'de');
        $value->setType(Zend_Measure_Flow_Volume::STANDARD);
        $this->assertEquals(Zend_Measure_Flow_Volume::STANDARD, $value->getType(), 'Zend_Measure_Flow_Volume type expected');
    }

    /**
     * test setting unknown type
     * expected new type.
     */
    public function testFlowVolumeSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Flow_Volume('-100', Zend_Measure_Flow_Volume::STANDARD, 'de');
            $value->setType('Flow_Volume::UNKNOWN');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test toString
     * expected string.
     */
    public function testFlowVolumeToString()
    {
        $value = new Zend_Measure_Flow_Volume('-100', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals('-100 m³/s', $value->toString(), 'Value -100 m³/s expected');
    }

    /**
     * test __toString
     * expected string.
     */
    public function testFlowVolumeToString()
    {
        $value = new Zend_Measure_Flow_Volume('-100', Zend_Measure_Flow_Volume::STANDARD, 'de');
        $this->assertEquals('-100 m³/s', $value->__toString(), 'Value -100 m³/s expected');
    }

    /**
     * test getConversionList
     * expected array.
     */
    public function testFlowVolumeConversionList()
    {
        $value = new Zend_Measure_Flow_Volume('-100',Zend_Measure_Flow_Volume::STANDARD,'de');
        $unit = $value->getConversionList();
        $this->assertTrue(is_array($unit), 'Array expected');
    }
}
