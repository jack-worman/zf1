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
class Zend_Gdata_Docs_DocumentListFeedTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->docFeed = new Zend_Gdata_Docs_DocumentListFeed(
            file_get_contents(__DIR__.'/_files/TestDataDocumentListFeedSample.xml'),
            true);
    }

    public function testToAndFromString()
    {
        // There should be 2 entries in the feed.
        $this->assertTrue(2 == count($this->docFeed->entries));
        $this->assertTrue(2 == $this->docFeed->entries->count());
        foreach ($this->docFeed->entries as $entry) {
            $this->assertTrue($entry instanceof Zend_Gdata_Docs_DocumentListEntry);
        }

        $newDocFeed = new Zend_Gdata_Docs_DocumentListFeed();
        $doc = new DOMDocument();
        $doc->loadXML($this->docFeed->saveXML());
        $newDocFeed->transferFromDom($doc->documentElement);

        $this->assertTrue(count($newDocFeed->entries) == count($this->docFeed->entries));
        foreach ($newDocFeed->entries as $entry) {
            $this->assertTrue($entry instanceof Zend_Gdata_Docs_DocumentListEntry);
        }
    }
}
