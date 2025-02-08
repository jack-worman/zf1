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
 * Zend_Measure_Viscosity_Dynamic.
 */
// require_once 'Zend/Measure/Viscosity/Dynamic.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Measure
 */
#[AllowDynamicProperties]
class Zend_Measure_Viscosity_DynamicTest extends PHPUnit\Framework\TestCase
{
    /**
     * test for Mass initialisation
     * expected instance.
     */
    public function testMassInit()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertTrue($value instanceof Zend_Measure_Viscosity_Dynamic, 'Zend_Measure_Viscosity_Dynamic Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testViscosityDynamicUnknownType()
    {
        try {
            $value = new Zend_Measure_Viscosity_Dynamic('100', 'Viscosity_Dynamic::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testViscosityDynamicUnknownValue()
    {
        try {
            $value = new Zend_Measure_Viscosity_Dynamic('novalue', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected root value.
     */
    public function testViscosityDynamicUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Viscosity_Dynamic('100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for standard locale
     * expected integer.
     */
    public function testViscosityDynamicNoLocale()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('100', Zend_Measure_Viscosity_Dynamic::STANDARD);
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic value expected');
    }

    /**
     * test for positive value
     * expected integer.
     */
    public function testViscosityDynamicValuePositive()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic value expected to be a positive integer');
    }

    /**
     * test for negative value
     * expected integer.
     */
    public function testViscosityDynamicValueNegative()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic value expected to be a negative integer');
    }

    /**
     * test for decimal value
     * expected float.
     */
    public function testViscosityDynamicValueDecimal()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic value expected to be a decimal value');
    }

    /**
     * test for decimal seperated value
     * expected float.
     */
    public function testViscosityDynamicValueDecimalSeperated()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100.100,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic Object not returned');
    }

    /**
     * test for string with integrated value
     * expected float.
     */
    public function testViscosityDynamicValueString()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100.100,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic Object not returned');
    }

    /**
     * test for equality
     * expected true.
     */
    public function testViscosityDynamicEquality()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100.100,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $newvalue = new Zend_Measure_Viscosity_Dynamic('-100.100,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertTrue($value->equals($newvalue), 'Zend_Measure_Viscosity_Dynamic Object should be equal');
    }

    /**
     * test for no equality
     * expected false.
     */
    public function testViscosityDynamicNoEquality()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100.100,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $newvalue = new Zend_Measure_Viscosity_Dynamic('-100,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertFalse($value->equals($newvalue), 'Zend_Measure_Viscosity_Dynamic Object should be not equal');
    }

    /**
     * test for set positive value
     * expected integer.
     */
    public function testViscosityDynamicSetPositive()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic value expected to be a positive integer');
    }

    /**
     * test for set negative value
     * expected integer.
     */
    public function testViscosityDynamicSetNegative()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $value->setValue('-200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic value expected to be a negative integer');
    }

    /**
     * test for set decimal value
     * expected float.
     */
    public function testViscosityDynamicSetDecimal()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $value->setValue('-200,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic value expected to be a decimal value');
    }

    /**
     * test for set decimal seperated value
     * expected float.
     */
    public function testViscosityDynamicSetDecimalSeperated()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100.100,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic Object not returned');
    }

    /**
     * test for set string with integrated value
     * expected float.
     */
    public function testViscosityDynamicSetString()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100.100,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testViscosityDynamicSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Viscosity_Dynamic('100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
            $value->setValue('-200.200,200', 'Viscosity_Dynamic::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testViscosityDynamicSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Viscosity_Dynamic('100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
            $value->setValue('novalue', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testViscosityDynamicSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Viscosity_Dynamic('100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
            $value->setValue('200', Zend_Measure_Viscosity_Dynamic::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testViscosityDynamicSetWithNoLocale()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Viscosity_Dynamic::STANDARD);
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Viscosity_Dynamic value expected to be a positive integer');
    }

    /**
     * test setting type
     * expected new type.
     */
    public function testViscosityDynamicSetType()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $value->setType(Zend_Measure_Viscosity_Dynamic::POISE);
        $this->assertEquals(Zend_Measure_Viscosity_Dynamic::POISE, $value->getType(), 'Zend_Measure_Viscosity_Dynamic type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testViscosityDynamicSetComputedType1()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $value->setType(Zend_Measure_Viscosity_Dynamic::KILOGRAM_PER_METER_HOUR);
        $this->assertEquals(Zend_Measure_Viscosity_Dynamic::KILOGRAM_PER_METER_HOUR, $value->getType(), 'Zend_Measure_Viscosity_Dynamic type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testViscosityDynamicSetComputedType2()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100', Zend_Measure_Viscosity_Dynamic::KILOGRAM_PER_METER_HOUR, 'de');
        $value->setType(Zend_Measure_Viscosity_Dynamic::STANDARD);
        $this->assertEquals(Zend_Measure_Viscosity_Dynamic::STANDARD, $value->getType(), 'Zend_Measure_Viscosity_Dynamic type expected');
    }

    /**
     * test setting unknown type
     * expected new type.
     */
    public function testViscosityDynamicSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Viscosity_Dynamic('-100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
            $value->setType('Viscosity_Dynamic::UNKNOWN');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test toString
     * expected string.
     */
    public function testViscosityDynamicToString()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals('-100 kg/ms', $value->toString(), 'Value -100 kg/ms expected');
    }

    /**
     * test __toString
     * expected string.
     */
    public function testViscosityDynamicToString()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $this->assertEquals('-100 kg/ms', $value->__toString(), 'Value -100 kg/ms expected');
    }

    /**
     * test getConversionList
     * expected array.
     */
    public function testViscosityDynamicConversionList()
    {
        $value = new Zend_Measure_Viscosity_Dynamic('-100', Zend_Measure_Viscosity_Dynamic::STANDARD, 'de');
        $unit = $value->getConversionList();
        $this->assertTrue(is_array($unit), 'Array expected');
    }
}
