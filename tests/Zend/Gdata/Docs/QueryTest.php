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
 * @version    $Id $
 */

// require_once 'Zend/Gdata/Docs.php';
// require_once 'Zend/Gdata/Docs/Query.php';
// require_once 'Zend/Http/Client.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Docs
 */
#[AllowDynamicProperties]
class Zend_Gdata_Docs_QueryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->docQuery = new Zend_Gdata_Docs_Query();
    }

    public function testTitle()
    {
        $this->assertTrue(null == $this->docQuery->getTitle());
        $this->docQuery->setTitle('test title');
        $this->assertTrue('test title' == $this->docQuery->getTitle());
        $this->assertTrue('?title=test+title' == $this->docQuery->getQueryString());
        $this->docQuery->setTitle(null);
        $this->assertTrue(null == $this->docQuery->getTitle());
    }

    public function testTitleExact()
    {
        $this->assertTrue(null == $this->docQuery->getTitleExact());
        $this->docQuery->setTitleExact('test title');
        $this->assertTrue('test title' == $this->docQuery->getTitleExact());
        $this->assertTrue('?title-exact=test+title' == $this->docQuery->getQueryString());
        $this->docQuery->setTitleExact(null);
        $this->assertTrue(null == $this->docQuery->getTitleExact());
    }

    public function testProjection()
    {
        $this->assertTrue('full' == $this->docQuery->getProjection());
        $this->docQuery->setProjection('abc');
        $this->assertTrue('abc' == $this->docQuery->getProjection());
    }

    public function testVisibility()
    {
        $this->assertTrue('private' == $this->docQuery->getVisibility());
        $this->docQuery->setVisibility('xyz');
        $this->assertTrue('xyz' == $this->docQuery->getVisibility());
    }
}
