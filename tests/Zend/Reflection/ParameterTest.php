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
 * @see Zend_Reflection_Parameter
 */
// require_once 'Zend/Reflection/Parameter.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Reflection
 * @group      Zend_Reflection_Parameter
 */
#[AllowDynamicProperties]
class Zend_Reflection_ParameterTest extends PHPUnit_Framework_TestCase
{
    protected static $_sampleClassFileRequired = false;

    public function setup()
    {
        if (false === self::$_sampleClassFileRequired) {
            $fileToRequire = __DIR__.'/_files/TestSampleClass.php';
            require_once $fileToRequire;
            self::$_sampleClassFileRequired = true;
        }
    }

    public function testDeclaringClassReturn()
    {
        $parameter = new Zend_Reflection_Parameter(['Zend_Reflection_TestSampleClass2', 'getProp2'], 0);
        $this->assertEquals(get_class($parameter->getDeclaringClass()), 'Zend_Reflection_Class');
    }

    public function testClassReturnNoClassGivenReturnsNull()
    {
        $parameter = new Zend_Reflection_Parameter(['Zend_Reflection_TestSampleClass2', 'getProp2'], 'param1');

        $this->assertNull($parameter->getClass());
    }

    public function testClassReturn()
    {
        $parameter = new Zend_Reflection_Parameter(['Zend_Reflection_TestSampleClass2', 'getProp2'], 'param2');
        $this->assertEquals(get_class($parameter->getClass()), 'Zend_Reflection_Class');
    }

    /**
     * @dataProvider paramTypeTestProvider
     */
    public function testTypeReturn($param, $type)
    {
        $parameter = new Zend_Reflection_Parameter(['Zend_Reflection_TestSampleClass5', 'doSomething'], $param);
        $this->assertEquals($parameter->getType(), $type);
    }

    public function paramTypeTestProvider()
    {
        return [
            ['one', 'int'],
            ['two', 'int'],
            ['three', 'string'],
        ];
    }
}
