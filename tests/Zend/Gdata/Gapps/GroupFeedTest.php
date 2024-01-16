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
 * @version    $Id:$
 */

// require_once 'Zend/Gdata/Gapps.php';
// require_once 'Zend/Gdata/Gapps/GroupFeed.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Gapps
 */
#[AllowDynamicProperties]
class Zend_Gdata_Gapps_GroupFeedTest extends PHPUnit_Framework_TestCase
{
    protected $groupFeed;

    /**
     * Called before each test to setup any fixtures.
     */
    public function setUp()
    {
        $groupFeedText = file_get_contents(
            'Zend/Gdata/Gapps/_files/GroupFeedDataSample1.xml',
            true);
        $this->groupFeed = new Zend_Gdata_Gapps_GroupFeed($groupFeedText);
        $this->emptyGroupFeed = new Zend_Gdata_Gapps_GroupFeed();
    }

    public function testEmptyFeedShouldHaveNoExtensionElements()
    {
        $this->assertTrue(is_array($this->emptyGroupFeed->extensionElements));
        $this->assertTrue(0 == count($this->emptyGroupFeed->extensionElements));
    }

    public function testEmptyFeedShouldHaveNoExtensionAttributes()
    {
        $this->assertTrue(is_array($this->emptyGroupFeed->extensionAttributes));
        $this->assertTrue(0 == count($this->emptyGroupFeed->extensionAttributes));
    }

    public function testSampleFeedShouldHaveNoExtensionElements()
    {
        $this->assertTrue(is_array($this->groupFeed->extensionElements));
        $this->assertTrue(0 == count($this->groupFeed->extensionElements));
    }

    public function testSampleFeedShouldHaveNoExtensionAttributes()
    {
        $this->assertTrue(is_array($this->groupFeed->extensionAttributes));
        $this->assertTrue(0 == count($this->groupFeed->extensionAttributes));
    }

    /**
     * Convert sample feed to XML then back to objects. Ensure that
     * all objects are instances of GroupEntry and object count matches.
     */
    public function testXmlImportAndOutputAreNonDestructive()
    {
        $entryCount = 0;
        foreach ($this->groupFeed as $entry) {
            ++$entryCount;
            $this->assertTrue($entry instanceof Zend_Gdata_Gapps_GroupEntry);
        }
        $this->assertTrue($entryCount > 0);

        /* Grab XML from $this->groupFeed and convert back to objects */
        $newGroupFeed = new Zend_Gdata_Gapps_GroupFeed(
            $this->groupFeed->saveXML());
        $newEntryCount = 0;
        foreach ($newGroupFeed as $entry) {
            ++$newEntryCount;
            $this->assertTrue($entry instanceof Zend_Gdata_Gapps_GroupEntry);
        }
        $this->assertEquals($entryCount, $newEntryCount);
    }

    /**
     * Ensure that there number of group entries equals the number
     * of groups defined in the sample file.
     */
    public function testAllEntriesInFeedAreInstantiated()
    {
        // TODO feeds implementing ArrayAccess would be helpful here
        $entryCount = 0;
        foreach ($this->groupFeed as $entry) {
            ++$entryCount;
        }
        $this->assertEquals(2, $entryCount);
    }
}
