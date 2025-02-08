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
 * @see Zend_Reflection_Property
 */
// require_once 'Zend/Reflection/Property.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Reflection
 * @group      Zend_Reflection_Property
 */
#[AllowDynamicProperties]
class Zend_Reflection_PropertyTest extends PHPUnit\Framework\TestCase
{
    protected static $_sampleClassFileRequired = false;

    public function setUp(): void
    {
        if (false === self::$_sampleClassFileRequired) {
            $fileToRequire = __DIR__.'/_files/TestSampleClass.php';
            require_once $fileToRequire;
            self::$_sampleClassFileRequired = true;
        }
    }

    public function testDeclaringClassReturn()
    {
        $property = new Zend_Reflection_Property('Zend_Reflection_TestSampleClass2', '_prop1');
        $this->assertEquals(get_class($property->getDeclaringClass()), 'Zend_Reflection_Class');
    }
}
