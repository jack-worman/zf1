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
 * Zend_Measure_Cooking_Weight.
 */
// require_once 'Zend/Measure/Cooking/Weight.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Measure
 */
#[AllowDynamicProperties]
class Zend_Measure_Cooking_WeightTest extends PHPUnit_Framework_TestCase
{
    /**
     * test for Mass initialisation
     * expected instance.
     */
    public function testCookingWeightInit()
    {
        $value = new Zend_Measure_Cooking_Weight('100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertTrue($value instanceof Zend_Measure_Cooking_Weight, 'Zend_Measure_Cooking_Weight Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testCookingWeightUnknownType()
    {
        try {
            $value = new Zend_Measure_Cooking_Weight('100', 'Cooking_Weight::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testCookingWeightUnknownValue()
    {
        try {
            $value = new Zend_Measure_Cooking_Weight('novalue', Zend_Measure_Cooking_Weight::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected root value.
     */
    public function testCookingWeightUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Cooking_Weight('100', Zend_Measure_Cooking_Weight::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for standard locale
     * expected integer.
     */
    public function testCookingWeightNoLocale()
    {
        $value = new Zend_Measure_Cooking_Weight('100', Zend_Measure_Cooking_Weight::STANDARD);
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Cooking_Weight value expected');
    }

    /**
     * test for positive value
     * expected integer.
     */
    public function testCookingWeightValuePositive()
    {
        $value = new Zend_Measure_Cooking_Weight('100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals(100, $value->getValue(), 'Zend_Measure_Cooking_Weight value expected to be a positive integer');
    }

    /**
     * test for negative value
     * expected integer.
     */
    public function testCookingWeightValueNegative()
    {
        $value = new Zend_Measure_Cooking_Weight('-100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals(-100, $value->getValue(), 'Zend_Measure_Cooking_Weight value expected to be a negative integer');
    }

    /**
     * test for decimal value
     * expected float.
     */
    public function testCookingWeightValueDecimal()
    {
        $value = new Zend_Measure_Cooking_Weight('-100,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals(-100.200, $value->getValue(), 'Zend_Measure_Cooking_Weight value expected to be a decimal value');
    }

    /**
     * test for decimal seperated value
     * expected float.
     */
    public function testCookingWeightValueDecimalSeperated()
    {
        $value = new Zend_Measure_Cooking_Weight('-100.100,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Cooking_Weight Object not returned');
    }

    /**
     * test for string with integrated value
     * expected float.
     */
    public function testCookingWeightValueString()
    {
        $value = new Zend_Measure_Cooking_Weight('-100.100,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals(-100100.200, $value->getValue(), 'Zend_Measure_Cooking_Weight Object not returned');
    }

    /**
     * test for equality
     * expected true.
     */
    public function testCookingWeightEquality()
    {
        $value = new Zend_Measure_Cooking_Weight('-100.100,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $newvalue = new Zend_Measure_Cooking_Weight('-100.100,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertTrue($value->equals($newvalue), 'Zend_Measure_Cooking_Weight Object should be equal');
    }

    /**
     * test for no equality
     * expected false.
     */
    public function testCookingWeightNoEquality()
    {
        $value = new Zend_Measure_Cooking_Weight('-100.100,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $newvalue = new Zend_Measure_Cooking_Weight('-100,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertFalse($value->equals($newvalue), 'Zend_Measure_Cooking_Weight Object should be not equal');
    }

    /**
     * test for set positive value
     * expected integer.
     */
    public function testCookingWeightSetPositive()
    {
        $value = new Zend_Measure_Cooking_Weight('100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Cooking_Weight value expected to be a positive integer');
    }

    /**
     * test for set negative value
     * expected integer.
     */
    public function testCookingWeightSetNegative()
    {
        $value = new Zend_Measure_Cooking_Weight('-100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $value->setValue('-200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals(-200, $value->getValue(), 'Zend_Measure_Cooking_Weight value expected to be a negative integer');
    }

    /**
     * test for set decimal value
     * expected float.
     */
    public function testCookingWeightSetDecimal()
    {
        $value = new Zend_Measure_Cooking_Weight('-100,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $value->setValue('-200,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals(-200.200, $value->getValue(), 'Zend_Measure_Cooking_Weight value expected to be a decimal value');
    }

    /**
     * test for set decimal seperated value
     * expected float.
     */
    public function testCookingWeightSetDecimalSeperated()
    {
        $value = new Zend_Measure_Cooking_Weight('-100.100,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Cooking_Weight Object not returned');
    }

    /**
     * test for set string with integrated value
     * expected float.
     */
    public function testCookingWeightSetString()
    {
        $value = new Zend_Measure_Cooking_Weight('-100.100,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $value->setValue('-200.200,200', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals(-200200.200, $value->getValue(), 'Zend_Measure_Cooking_Weight Object not returned');
    }

    /**
     * test for exception unknown type
     * expected exception.
     */
    public function testCookingWeightSetUnknownType()
    {
        try {
            $value = new Zend_Measure_Cooking_Weight('100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
            $value->setValue('-200.200,200', 'Cooking_Weight::UNKNOWN', 'de');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown value
     * expected exception.
     */
    public function testCookingWeightSetUnknownValue()
    {
        try {
            $value = new Zend_Measure_Cooking_Weight('100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
            $value->setValue('novalue', Zend_Measure_Cooking_Weight::STANDARD, 'de');
            $this->fail('Exception expected because of empty value');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testCookingWeightSetUnknownLocale()
    {
        try {
            $value = new Zend_Measure_Cooking_Weight('100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
            $value->setValue('200', Zend_Measure_Cooking_Weight::STANDARD, 'nolocale');
            $this->fail('Exception expected because of unknown locale');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test for exception unknown locale
     * expected exception.
     */
    public function testCookingWeightSetWithNoLocale()
    {
        $value = new Zend_Measure_Cooking_Weight('100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $value->setValue('200', Zend_Measure_Cooking_Weight::STANDARD);
        $this->assertEquals(200, $value->getValue(), 'Zend_Measure_Cooking_Weight value expected to be a positive integer');
    }

    /**
     * test setting type
     * expected new type.
     */
    public function testCookingWeightSetType()
    {
        $value = new Zend_Measure_Cooking_Weight('-100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $value->setType(Zend_Measure_Cooking_Weight::CUP);
        $this->assertEquals(Zend_Measure_Cooking_Weight::CUP, $value->getType(), 'Zend_Measure_Cooking_Weight type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testCookingWeightSetComputedType1()
    {
        $value = new Zend_Measure_Cooking_Weight('-100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $value->setType(Zend_Measure_Cooking_Weight::CUP);
        $this->assertEquals(Zend_Measure_Cooking_Weight::CUP, $value->getType(), 'Zend_Measure_Cooking_Weight type expected');
    }

    /**
     * test setting computed type
     * expected new type.
     */
    public function testCookingWeightSetComputedType2()
    {
        $value = new Zend_Measure_Cooking_Weight('-100', Zend_Measure_Cooking_Weight::CUP, 'de');
        $value->setType(Zend_Measure_Cooking_Weight::STANDARD);
        $this->assertEquals(Zend_Measure_Cooking_Weight::STANDARD, $value->getType(), 'Zend_Measure_Cooking_Weight type expected');
    }

    /**
     * test setting unknown type
     * expected new type.
     */
    public function testCookingWeightSetTypeFailed()
    {
        try {
            $value = new Zend_Measure_Cooking_Weight('-100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
            $value->setType('Cooking_Weight::UNKNOWN');
            $this->fail('Exception expected because of unknown type');
        } catch (Zend_Measure_Exception $e) {
            // success
        }
    }

    /**
     * test toString
     * expected string.
     */
    public function testCookingWeightToString()
    {
        $value = new Zend_Measure_Cooking_Weight('-100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals('-100 g', $value->toString(), 'Value -100 g expected');
    }

    /**
     * test __toString
     * expected string.
     */
    public function testCookingWeightToString()
    {
        $value = new Zend_Measure_Cooking_Weight('-100', Zend_Measure_Cooking_Weight::STANDARD, 'de');
        $this->assertEquals('-100 g', $value->__toString(), 'Value -100 g expected');
    }

    /**
     * test getConversionList
     * expected array.
     */
    public function testCookingWeightConversionList()
    {
        $value = new Zend_Measure_Cooking_Weight('-100',Zend_Measure_Cooking_Weight::STANDARD,'de');
        $unit = $value->getConversionList();
        $this->assertTrue(is_array($unit), 'Array expected');
    }
}
