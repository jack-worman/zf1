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
 * Zend_Measure_Cooking_Volume.
 */
// require_once 'Zend/Measure/Cooking/Volume.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Measure
 */
#[AllowDynamicProperties]
class Zend_Measure_Cooking_VolumeTest extends PHPUnit\Framework\TestCase
{
    /**
     * test for Mass initialisation
     * expected instance.
     */
    public function testMassInit()
    {
        $value = new Zend_Measure_Cooking_Volume('100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertTrue($value instanceof Zend_Measure_Cooking_Volume, 'Zend_Measure_Cooking_Volume Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testCookingVolumeUnknownType()
    {
        try {
            $value = new Zend_Measure_Cooking_Volume('100', 'Cooking_Volume::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testCookingVolumeUnknownValue()
    {
        try {
            $value = new Zend_Measure_Cooking_Volume('novalue', Zend_Measure_Cooking_Volume::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected root value.
     */
    public function testCookingVolumeUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Cooking_Volume('100', Zend_Measure_Cooking_Volume::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for standard locale
     * expected integer.
     */
    public function testCookingVolumeNoLocale()
    {
        $value = new Zend_Measure_Cooking_Volume('100', Zend_Measure_Cooking_Volume::STANDARD);
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Cooking_Volume value expected');
    }

    /**
     * test for positive value
     * expected integer.
     */
    public function testCookingVolumeValuePositive()
    {
        $value = new Zend_Measure_Cooking_Volume('100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Cooking_Volume value expected to be a positive integer');
    }

    /**
     * test for negative value
     * expected integer.
     */
    public function testCookingVolumeValueNegative()
    {
        $value = new Zend_Measure_Cooking_Volume('-100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Cooking_Volume value expected to be a negative integer');
    }

    /**
     * test for decimal value
     * expected float.
     */
    public function testCookingVolumeValueDecimal()
    {
        $value = new Zend_Measure_Cooking_Volume('-100,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Cooking_Volume value expected to be a decimal value');
    }

    /**
     * test for decimal seperated value
     * expected float.
     */
    public function testCookingVolumeValueDecimalSeperated()
    {
        $value = new Zend_Measure_Cooking_Volume('-100.100,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Cooking_Volume Object not returned');
    }

    /**
     * test for string with integrated value
     * expected float.
     */
    public function testCookingVolumeValueString()
    {
        $value = new Zend_Measure_Cooking_Volume('-100.100,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Cooking_Volume Object not returned');
    }

    /**
     * test for equality
     * expected true.
     */
    public function testCookingVolumeEquality()
    {
        $value = new Zend_Measure_Cooking_Volume('-100.100,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $newvalue = new Zend_Measure_Cooking_Volume('-100.100,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertTrue($value->equals($newvalue), 'Zend_Measure_Cooking_Volume Object should be equal');
    }

    /**
     * test for no equality
     * expected false.
     */
    public function testCookingVolumeNoEquality()
    {
        $value = new Zend_Measure_Cooking_Volume('-100.100,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $newvalue = new Zend_Measure_Cooking_Volume('-100,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertFalse($value->equals($newvalue), 'Zend_Measure_Cooking_Volume Object should be not equal');
    }

    /**
     * test for set positive value
     * expected integer.
     */
    public function testCookingVolumeSetPositive()
    {
        $value = new Zend_Measure_Cooking_Volume('100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Cooking_Volume value expected to be a positive integer');
    }

    /**
     * test for set negative value
     * expected integer.
     */
    public function testCookingVolumeSetNegative()
    {
        $value = new Zend_Measure_Cooking_Volume('-100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $value->setValue('-200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Cooking_Volume value expected to be a negative integer');
    }

    /**
     * test for set decimal value
     * expected float.
     */
    public function testCookingVolumeSetDecimal()
    {
        $value = new Zend_Measure_Cooking_Volume('-100,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $value->setValue('-200,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Cooking_Volume value expected to be a decimal value');
    }

    /**
     * test for set decimal seperated value
     * expected float.
     */
    public function testCookingVolumeSetDecimalSeperated()
    {
        $value = new Zend_Measure_Cooking_Volume('-100.100,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Cooking_Volume Object not returned');
    }

    /**
     * test for set string with integrated value
     * expected float.
     */
    public function testCookingVolumeSetString()
    {
        $value = new Zend_Measure_Cooking_Volume('-100.100,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Cooking_Volume Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testCookingVolumeSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Cooking_Volume('100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
            $value->setValue('-200.200,200', 'Cooking_Volume::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testCookingVolumeSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Cooking_Volume('100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
            $value->setValue('novalue', Zend_Measure_Cooking_Volume::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Throwable $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testCookingVolumeSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Cooking_Volume('100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
            $value->setValue('200', Zend_Measure_Cooking_Volume::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testCookingVolumeSetWithNoLocale()
    {
        $value = new Zend_Measure_Cooking_Volume('100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Cooking_Volume::STANDARD);
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Cooking_Volume value expected to be a positive integer');
    }

    /**
     * test setting type
     * expected new type.
     */
    public function testCookingVolumeSetType()
    {
        $value = new Zend_Measure_Cooking_Volume('-100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $value->setType(Zend_Measure_Cooking_Volume::DRAM);
        $this->assertEquals(Zend_Measure_Cooking_Volume::DRAM, $value->getType(), 'Zend_Measure_Cooking_Volume type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testCookingVolumeSetComputedType1()
    {
        $value = new Zend_Measure_Cooking_Volume('-100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $value->setType(Zend_Measure_Cooking_Volume::DRAM);
        $this->assertEquals(Zend_Measure_Cooking_Volume::DRAM, $value->getType(), 'Zend_Measure_Cooking_Volume type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testCookingVolumeSetComputedType2()
    {
        $value = new Zend_Measure_Cooking_Volume('-100', Zend_Measure_Cooking_Volume::DRAM, 'de');
        $value->setType(Zend_Measure_Cooking_Volume::STANDARD);
        $this->assertEquals(Zend_Measure_Cooking_Volume::STANDARD, $value->getType(), 'Zend_Measure_Cooking_Volume type expected');
    }

    /**
     * test setting unknown type
     * expected new type.
     */
    public function testCookingVolumeSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Cooking_Volume('-100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
            $value->setType('Cooking_Volume::UNKNOWN');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test toString
     * expected string.
     */
    public function testCookingVolumeToString()
    {
        $value = new Zend_Measure_Cooking_Volume('-100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals('-100 m³', $value->toString(), 'Value -100 m³ expected');
    }

    /**
     * test __toString
     * expected string.
     */
    public function testCookingVolumeToString()
    {
        $value = new Zend_Measure_Cooking_Volume('-100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $this->assertEquals('-100 m³', $value->__toString(), 'Value -100 m³ expected');
    }

    /**
     * test getConversionList
     * expected array.
     */
    public function testCookingVolumeConversionList()
    {
        $value = new Zend_Measure_Cooking_Volume('-100', Zend_Measure_Cooking_Volume::STANDARD, 'de');
        $unit = $value->getConversionList();
        $this->assertTrue(is_array($unit), 'Array expected');
    }
}
