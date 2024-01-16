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
 * Zend_Measure_Flow_Mass.
 */
// require_once 'Zend/Measure/Flow/Mass.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Measure
 */
#[AllowDynamicProperties]
class Zend_Measure_Flow_MassTest extends PHPUnit_Framework_TestCase
{
    /**
     * test for Mass initialisation
     * expected instance.
     */
    public function testMassInit()
    {
        $value = new Zend_Measure_Flow_Mass('100', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertTrue($value instanceof Zend_Measure_Flow_Mass, 'Zend_Measure_Flow_Mass Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testFlowMassUnknownType()
    {
        try {
            $value = new Zend_Measure_Flow_Mass('100', 'Flow_Mass::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testFlowMassUnknownValue()
    {
        try {
            $value = new Zend_Measure_Flow_Mass('novalue', Zend_Measure_Flow_Mass::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected root value.
     */
    public function testFlowMassUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Flow_Mass('100', Zend_Measure_Flow_Mass::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for standard locale
     * expected integer.
     */
    public function testFlowMassNoLocale()
    {
        $value = new Zend_Measure_Flow_Mass('100', Zend_Measure_Flow_Mass::STANDARD);
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Flow_Mass value expected');
    }

    /**
     * test for positive value
     * expected integer.
     */
    public function testFlowMassValuePositive()
    {
        $value = new Zend_Measure_Flow_Mass('100', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Flow_Mass value expected to be a positive integer');
    }

    /**
     * test for negative value
     * expected integer.
     */
    public function testFlowMassValueNegative()
    {
        $value = new Zend_Measure_Flow_Mass('-100', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Flow_Mass value expected to be a negative integer');
    }

    /**
     * test for decimal value
     * expected float.
     */
    public function testFlowMassValueDecimal()
    {
        $value = new Zend_Measure_Flow_Mass('-100,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Flow_Mass value expected to be a decimal value');
    }

    /**
     * test for decimal seperated value
     * expected float.
     */
    public function testFlowMassValueDecimalSeperated()
    {
        $value = new Zend_Measure_Flow_Mass('-100.100,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Flow_Mass Object not returned');
    }

    /**
     * test for string with integrated value
     * expected float.
     */
    public function testFlowMassValueString()
    {
        $value = new Zend_Measure_Flow_Mass('-100.100,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Flow_Mass Object not returned');
    }

    /**
     * test for equality
     * expected true.
     */
    public function testFlowMassEquality()
    {
        $value = new Zend_Measure_Flow_Mass('-100.100,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $newvalue = new Zend_Measure_Flow_Mass('-100.100,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertTrue($value->equals($newvalue), 'Zend_Measure_Flow_Mass Object should be equal');
    }

    /**
     * test for no equality
     * expected false.
     */
    public function testFlowMassNoEquality()
    {
        $value = new Zend_Measure_Flow_Mass('-100.100,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $newvalue = new Zend_Measure_Flow_Mass('-100,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertFalse($value->equals($newvalue), 'Zend_Measure_Flow_Mass Object should be not equal');
    }

    /**
     * test for set positive value
     * expected integer.
     */
    public function testFlowMassSetPositive()
    {
        $value = new Zend_Measure_Flow_Mass('100', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Flow_Mass value expected to be a positive integer');
    }

    /**
     * test for set negative value
     * expected integer.
     */
    public function testFlowMassSetNegative()
    {
        $value = new Zend_Measure_Flow_Mass('-100', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $value->setValue('-200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Flow_Mass value expected to be a negative integer');
    }

    /**
     * test for set decimal value
     * expected float.
     */
    public function testFlowMassSetDecimal()
    {
        $value = new Zend_Measure_Flow_Mass('-100,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $value->setValue('-200,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Flow_Mass value expected to be a decimal value');
    }

    /**
     * test for set decimal seperated value
     * expected float.
     */
    public function testFlowMassSetDecimalSeperated()
    {
        $value = new Zend_Measure_Flow_Mass('-100.100,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Flow_Mass Object not returned');
    }

    /**
     * test for set string with integrated value
     * expected float.
     */
    public function testFlowMassSetString()
    {
        $value = new Zend_Measure_Flow_Mass('-100.100,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Flow_Mass Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testFlowMassSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Flow_Mass('100', Zend_Measure_Flow_Mass::STANDARD, 'de');
            $value->setValue('-200.200,200', 'Flow_Mass::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testFlowMassSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Flow_Mass('100', Zend_Measure_Flow_Mass::STANDARD, 'de');
            $value->setValue('novalue', Zend_Measure_Flow_Mass::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testFlowMassSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Flow_Mass('100', Zend_Measure_Flow_Mass::STANDARD, 'de');
            $value->setValue('200', Zend_Measure_Flow_Mass::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testFlowMassSetWithNoLocale()
    {
        $value = new Zend_Measure_Flow_Mass('100', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Flow_Mass::STANDARD);
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Flow_Mass value expected to be a positive integer');
    }

    /**
     * test setting type
     * expected new type.
     */
    public function testFlowMassSetType()
    {
        $value = new Zend_Measure_Flow_Mass('-100', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $value->setType(Zend_Measure_Flow_Mass::GRAM_PER_DAY);
        $this->assertEquals(Zend_Measure_Flow_Mass::GRAM_PER_DAY, $value->getType(), 'Zend_Measure_Flow_Mass type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testFlowMassSetComputedType1()
    {
        $value = new Zend_Measure_Flow_Mass('-100', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $value->setType(Zend_Measure_Flow_Mass::GRAM_PER_DAY);
        $this->assertEquals(Zend_Measure_Flow_Mass::GRAM_PER_DAY, $value->getType(), 'Zend_Measure_Flow_Mass type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testFlowMassSetComputedType2()
    {
        $value = new Zend_Measure_Flow_Mass('-100', Zend_Measure_Flow_Mass::GRAM_PER_DAY, 'de');
        $value->setType(Zend_Measure_Flow_Mass::STANDARD);
        $this->assertEquals(Zend_Measure_Flow_Mass::STANDARD, $value->getType(), 'Zend_Measure_Flow_Mass type expected');
    }

    /**
     * test setting unknown type
     * expected new type.
     */
    public function testFlowMassSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Flow_Mass('-100', Zend_Measure_Flow_Mass::STANDARD, 'de');
            $value->setType('Flow_Mass::UNKNOWN');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test toString
     * expected string.
     */
    public function testFlowMassToString()
    {
        $value = new Zend_Measure_Flow_Mass('-100', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals('-100 kg/s', $value->toString(), 'Value -100 kg/s expected');
    }

    /**
     * test __toString
     * expected string.
     */
    public function testFlowMassToString()
    {
        $value = new Zend_Measure_Flow_Mass('-100', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $this->assertEquals('-100 kg/s', $value->__toString(), 'Value -100 kg/s expected');
    }

    /**
     * test getConversionList
     * expected array.
     */
    public function testFlowMassConversionList()
    {
        $value = new Zend_Measure_Flow_Mass('-100', Zend_Measure_Flow_Mass::STANDARD, 'de');
        $unit = $value->getConversionList();
        $this->assertTrue(is_array($unit), 'Array expected');
    }
}
