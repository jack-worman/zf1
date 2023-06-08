<?php
/**
 * File header here
 *
 * @author Ralph Schindler <ralph.schindler@zend.com>
 */



/**
 * class docblock
 *
 * @package Zend_Reflection_TestClassWithCodeInMethod
 */
#[AllowDynamicProperties]
class Zend_Reflection_TestClassWithCodeInMethod
{

    /**
     * Enter description here...
     *
     * @return bool
     */
    public function someMethod()
    {
        /* test test */
        $foo = 'bar';
    }

}

