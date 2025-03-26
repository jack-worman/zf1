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
 * Zend_Measure_Viscosity_Kinematic.
 */
// require_once 'Zend/Measure/Viscosity/Kinematic.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Measure
 */
#[AllowDynamicProperties]
class Zend_Measure_Viscosity_KinematicTest extends PHPUnit_Framework_TestCase
{
    /**
     * test for Mass initialisation
     * expected instance.
     */
    public function testMassInit()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertTrue($value instanceof Zend_Measure_Viscosity_Kinematic, 'Zend_Measure_Viscosity_Kinematic Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testViscosityKinematicUnknownType()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('100', 'Viscosity_Kinematic::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testViscosityKinematicUnknownValue()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('novalue', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected root value.
     */
    public function testViscosityKinematicUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for standard locale
     * expected integer.
     */
    public function testViscosityKinematicNoLocale()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('100', Zend_Measure_Viscosity_Kinematic::STANDARD);
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected');
    }

    /**
     * test for positive value
     * expected integer.
     */
    public function testViscosityKinematicValuePositive()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a positive integer');
    }

    /**
     * test for negative value
     * expected integer.
     */
    public function testViscosityKinematicValueNegative()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a negative integer');
    }

    /**
     * test for decimal value
     * expected float.
     */
    public function testViscosityKinematicValueDecimal()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a decimal value');
    }

    /**
     * test for decimal seperated value
     * expected float.
     */
    public function testViscosityKinematicValueDecimalSeperated()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100.100,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic Object not returned');
    }

    /**
     * test for string with integrated value
     * expected float.
     */
    public function testViscosityKinematicValueString()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100.100,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic Object not returned');
    }

    /**
     * test for equality
     * expected true.
     */
    public function testViscosityKinematicEquality()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100.100,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $newvalue = new Zend_Measure_Viscosity_Kinematic('-100.100,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertTrue($value->equals($newvalue), 'Zend_Measure_Viscosity_Kinematic Object should be equal');
    }

    /**
     * test for no equality
     * expected false.
     */
    public function testViscosityKinematicNoEquality()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100.100,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $newvalue = new Zend_Measure_Viscosity_Kinematic('-100,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertFalse($value->equals($newvalue), 'Zend_Measure_Viscosity_Kinematic Object should be not equal');
    }

    /**
     * test for set positive value
     * expected integer.
     */
    public function testViscosityKinematicSetPositive()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a positive integer');
    }

    /**
     * test for set negative value
     * expected integer.
     */
    public function testViscosityKinematicSetNegative()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $value->setValue('-200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a negative integer');
    }

    /**
     * test for set decimal value
     * expected float.
     */
    public function testViscosityKinematicSetDecimal()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $value->setValue('-200,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a decimal value');
    }

    /**
     * test for set decimal seperated value
     * expected float.
     */
    public function testViscosityKinematicSetDecimalSeperated()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100.100,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic Object not returned');
    }

    /**
     * test for set string with integrated value
     * expected float.
     */
    public function testViscosityKinematicSetString()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100.100,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testViscosityKinematicSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
            $value->setValue('-200.200,200', 'Viscosity_Kinematic::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testViscosityKinematicSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
            $value->setValue('novalue', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testViscosityKinematicSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
            $value->setValue('200', Zend_Measure_Viscosity_Kinematic::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testViscosityKinematicSetWithNoLocale()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Viscosity_Kinematic::STANDARD);
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Viscosity_Kinematic value expected to be a positive integer');
    }

    /**
     * test setting type
     * expected new type.
     */
    public function testViscosityKinematicSetType()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $value->setType(Zend_Measure_Viscosity_Kinematic::LENTOR);
        $this->assertEquals(Zend_Measure_Viscosity_Kinematic::LENTOR, $value->getType(), 'Zend_Measure_Viscosity_Kinematic type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testViscosityKinematicSetComputedType1()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $value->setType(Zend_Measure_Viscosity_Kinematic::LITER_PER_CENTIMETER_DAY);
        $this->assertEquals(Zend_Measure_Viscosity_Kinematic::LITER_PER_CENTIMETER_DAY, $value->getType(), 'Zend_Measure_Viscosity_Kinematic type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testViscosityKinematicSetComputedType2()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100', Zend_Measure_Viscosity_Kinematic::LITER_PER_CENTIMETER_DAY, 'de');
        $value->setType(Zend_Measure_Viscosity_Kinematic::STANDARD);
        $this->assertEquals(Zend_Measure_Viscosity_Kinematic::STANDARD, $value->getType(), 'Zend_Measure_Viscosity_Kinematic type expected');
    }

    /**
     * test setting unknown type
     * expected new type.
     */
    public function testViscosityKinematicSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Viscosity_Kinematic('-100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
            $value->setType('Viscosity_Kinematic::UNKNOWN');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test toString
     * expected string.
     */
    public function testViscosityKinematicToString()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals('-100 m²/s', $value->toString(), 'Value -100 m²/s expected');
    }

    /**
     * test __toString
     * expected string.
     */
    public function testViscosityKinematicToString()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100', Zend_Measure_Viscosity_Kinematic::STANDARD, 'de');
        $this->assertEquals('-100 m²/s', $value->__toString(), 'Value -100 m²/s expected');
    }

    /**
     * test getConversionList
     * expected array.
     */
    public function testViscosityKinematicConversionList()
    {
        $value = new Zend_Measure_Viscosity_Kinematic('-100',Zend_Measure_Viscosity_Kinematic::STANDARD,'de');
        $unit = $value->getConversionList();
        $this->assertTrue(is_array($unit), 'Array expected');
    }
}
