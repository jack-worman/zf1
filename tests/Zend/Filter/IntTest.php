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
 * @see Zend_Filter_Int
 */
// require_once 'Zend/Filter/Int.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Filter
 */
#[AllowDynamicProperties]
class Zend_Filter_IntTest extends PHPUnit\Framework\TestCase
{
    /**
     * Zend_Filter_Int object.
     *
     * @var Zend_Filter_Int
     */
    protected $_filter;

    /**
     * Creates a new Zend_Filter_Int object for each test method.
     */
    public function setUp(): void
    {
        $this->_filter = new Zend_Filter_Int();
    }

    /**
     * Ensures that the filter follows expected behavior.
     *
     * @return void
     */
    public function testBasic()
    {
        $valuesExpected = [
            'string' => 0,
            '1' => 1,
            '-1' => -1,
            '1.1' => 1,
            '-1.1' => -1,
            '0.9' => 0,
            '-0.9' => 0,
        ];
        foreach ($valuesExpected as $input => $output) {
            $this->assertEquals($output, $this->_filter->filter($input));
        }
    }
}
