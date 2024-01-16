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

// require_once 'Zend/Feed/Reader.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Feed
 * @group      Zend_Feed_Reader
 */
#[AllowDynamicProperties]
class Zend_Feed_Reader_Entry_RssTest extends PHPUnit_Framework_TestCase
{
    protected $_feedSamplePath;

    protected $_expectedCats = [];

    protected $_expectedCatsRdf = [];

    protected $_expectedCatsAtom = [];

    public function setup()
    {
        Zend_Feed_Reader::reset();
        if (Zend_Registry::isRegistered('Zend_Locale')) {
            $registry = Zend_Registry::getInstance();
            unset($registry['Zend_Locale']);
        }
        $this->_feedSamplePath = __DIR__.'/_files/Rss';
        $this->_options = Zend_Date::setOptions();
        foreach ($this->_options as $k => $v) {
            if (is_null($v)) {
                unset($this->_options[$k]);
            }
        }
        Zend_Date::setOptions(['format_type' => 'iso']);
        $this->_expectedCats = [
            [
                'term' => 'topic1',
                'scheme' => 'http://example.com/schema1',
                'label' => 'topic1',
            ],
            [
                'term' => 'topic1',
                'scheme' => 'http://example.com/schema2',
                'label' => 'topic1',
            ],
            [
                'term' => 'topic2',
                'scheme' => 'http://example.com/schema1',
                'label' => 'topic2',
            ],
        ];
        $this->_expectedCatsRdf = [
            [
                'term' => 'topic1',
                'scheme' => null,
                'label' => 'topic1',
            ],
            [
                'term' => 'topic2',
                'scheme' => null,
                'label' => 'topic2',
            ],
        ];
        $this->_expectedCatsAtom = [
            [
                'term' => 'topic1',
                'scheme' => 'http://example.com/schema1',
                'label' => 'topic1',
            ],
            [
                'term' => 'topic1',
                'scheme' => 'http://example.com/schema2',
                'label' => 'topic1',
            ],
            [
                'term' => 'cat_dog',
                'scheme' => 'http://example.com/schema1',
                'label' => 'Cat & Dog',
            ],
        ];
    }

    public function teardown()
    {
        Zend_Date::setOptions($this->_options);
    }

    /**
     * Get Id (Unencoded Text).
     */
    public function testGetsIdFromRss20()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss094()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    public function testGetsIdFromRss093()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    public function testGetsIdFromRss092()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    public function testGetsIdFromRss091()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    public function testGetsIdFromRss10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    public function testGetsIdFromRss090()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    // DC 1.0

    public function testGetsIdFromRss20Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc10/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss094Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss093Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss092Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss091Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss10Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss090Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    // DC 1.1

    public function testGetsIdFromRss20Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc11/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss094Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc11/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss093Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc11/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss092Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc11/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss091Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc11/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss10Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc11/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    public function testGetsIdFromRss090Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/dc11/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/1', $entry->getId());
    }

    // Missing Id (but alternates to Title)

    public function testGetsIdFromRss20Title()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/title/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getId());
    }

    public function testGetsIdFromRss094Title()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/title/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getId());
    }

    public function testGetsIdFromRss093Title()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/title/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getId());
    }

    public function testGetsIdFromRss092Title()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/title/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getId());
    }

    public function testGetsIdFromRss091Title()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/title/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getId());
    }

    public function testGetsIdFromRss10Title()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/title/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getId());
    }

    public function testGetsIdFromRss090Title()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/title/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getId());
    }

    // Missing Any Id

    public function testGetsIdFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    public function testGetsIdFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    public function testGetsIdFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    public function testGetsIdFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    public function testGetsIdFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    public function testGetsIdFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    public function testGetsIdFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/id/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getId());
    }

    /**
     * Get Title (Unencoded Text).
     */
    public function testGetsTitleFromRss20()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss094()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss093()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss092()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss091()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss090()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    // DC 1.0

    public function testGetsTitleFromRss20Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc10/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss094Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss093Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss092Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss091Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss10Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss090Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    // DC 1.1

    public function testGetsTitleFromRss20Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc11/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss094Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc11/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss093Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc11/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss092Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc11/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss091Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc11/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss10Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc11/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    public function testGetsTitleFromRss090Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/dc11/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Title', $entry->getTitle());
    }

    // Missing Title

    public function testGetsTitleFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getTitle());
    }

    public function testGetsTitleFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getTitle());
    }

    public function testGetsTitleFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getTitle());
    }

    public function testGetsTitleFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getTitle());
    }

    public function testGetsTitleFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getTitle());
    }

    public function testGetsTitleFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getTitle());
    }

    public function testGetsTitleFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getTitle());
    }

    /**
     * Get Authors (Unencoded Text).
     */
    public function testGetsAuthorsFromRss20()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['email' => 'joe@example.com', 'name' => 'Joe Bloggs'],
            ['email' => 'jane@example.com', 'name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss094()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    public function testGetsAuthorsFromRss093()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    public function testGetsAuthorsFromRss092()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    public function testGetsAuthorsFromRss091()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    public function testGetsAuthorsFromRss10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    public function testGetsAuthorsFromRss090()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    // DC 1.0

    public function testGetsAuthorsFromRss20Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss094Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss093Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss092Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss091Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss10Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss090Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    // DC 1.1

    public function testGetsAuthorsFromRss20Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss094Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss093Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss092Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss091Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss10Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    public function testGetsAuthorsFromRss090Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([
            ['name' => 'Joe Bloggs'], ['name' => 'Jane Bloggs'],
        ], (array) $entry->getAuthors());
        $this->assertEquals(['Joe Bloggs', 'Jane Bloggs'], $entry->getAuthors()->getValues());
    }

    // Missing Author

    public function testGetsAuthorsFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    public function testGetsAuthorsFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    public function testGetsAuthorsFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    public function testGetsAuthorsFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    public function testGetsAuthorsFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    public function testGetsAuthorsFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    public function testGetsAuthorsFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthors());
    }

    /**
     * Get Author (Unencoded Text).
     */
    public function testGetsAuthorFromRss20()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Joe Bloggs', 'email' => 'joe@example.com'], $entry->getAuthor());
    }

    public function testGetsAuthorFromRss094()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    public function testGetsAuthorFromRss093()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    public function testGetsAuthorFromRss092()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    public function testGetsAuthorFromRss091()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    public function testGetsAuthorFromRss10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    public function testGetsAuthorFromRss090()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    // DC 1.0

    public function testGetsAuthorFromRss20Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Joe Bloggs'], $entry->getAuthor());
    }

    public function testGetsAuthorFromRss094Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Jane Bloggs'], $entry->getAuthor(1));
    }

    public function testGetsAuthorFromRss093Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Joe Bloggs'], $entry->getAuthor());
    }

    public function testGetsAuthorFromRss092Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Jane Bloggs'], $entry->getAuthor(1));
    }

    public function testGetsAuthorFromRss091Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Joe Bloggs'], $entry->getAuthor());
    }

    public function testGetsAuthorFromRss10Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Jane Bloggs'], $entry->getAuthor(1));
    }

    public function testGetsAuthorFromRss090Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Joe Bloggs'], $entry->getAuthor());
    }

    // DC 1.1

    public function testGetsAuthorFromRss20Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Jane Bloggs'], $entry->getAuthor(1));
    }

    public function testGetsAuthorFromRss094Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Joe Bloggs'], $entry->getAuthor());
    }

    public function testGetsAuthorFromRss093Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Jane Bloggs'], $entry->getAuthor(1));
    }

    public function testGetsAuthorFromRss092Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Joe Bloggs'], $entry->getAuthor());
    }

    public function testGetsAuthorFromRss091Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Jane Bloggs'], $entry->getAuthor(1));
    }

    public function testGetsAuthorFromRss10Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Joe Bloggs'], $entry->getAuthor());
    }

    public function testGetsAuthorFromRss090Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/dc11/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(['name' => 'Jane Bloggs'], $entry->getAuthor(1));
    }

    // Missing Id

    public function testGetsAuthorFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    public function testGetsAuthorFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    public function testGetsAuthorFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    public function testGetsAuthorFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    public function testGetsAuthorFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    public function testGetsAuthorFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    public function testGetsAuthorFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getAuthor());
    }

    /**
     * Get Description (Unencoded Text).
     */
    public function testGetsDescriptionFromRss20()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss094()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss093()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss092()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss091()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss090()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    // DC 1.0

    public function testGetsDescriptionFromRss20Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc10/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss094Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss093Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss092Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss091Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss10Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss090Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    // DC 1.1

    public function testGetsDescriptionFromRss20Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc11/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss094Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc11/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss093Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc11/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss092Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc11/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss091Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc11/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss10Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc11/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    public function testGetsDescriptionFromRss090Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/dc11/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getDescription());
    }

    // Missing Description

    public function testGetsDescriptionFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDescription());
    }

    public function testGetsDescriptionFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDescription());
    }

    public function testGetsDescriptionFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDescription());
    }

    public function testGetsDescriptionFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDescription());
    }

    public function testGetsDescriptionFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDescription());
    }

    public function testGetsDescriptionFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDescription());
    }

    public function testGetsDescriptionFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDescription());
    }

    /**
     * Get enclosure.
     */
    public function testGetsEnclosureFromRss20()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/enclosure/plain/rss20.xml')
        );
        $entry = $feed->current();

        $expected = new stdClass();
        $expected->url = 'http://www.scripting.com/mp3s/weatherReportSuite.mp3';
        $expected->length = '12216320';
        $expected->type = 'audio/mpeg';

        $this->assertEquals($expected, $entry->getEnclosure());
    }

    public function testGetsEnclosureFromRss10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/enclosure/plain/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getEnclosure());
    }

    /**
     * Get Content (Unencoded Text).
     */
    public function testGetsContentFromRss20()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Content', $entry->getContent());
    }

    public function testGetsContentFromRss094()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Content', $entry->getContent());
    }

    public function testGetsContentFromRss093()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Content', $entry->getContent());
    }

    public function testGetsContentFromRss092()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Content', $entry->getContent());
    }

    public function testGetsContentFromRss091()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Content', $entry->getContent());
    }

    public function testGetsContentFromRss10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Content', $entry->getContent());
    }

    public function testGetsContentFromRss090()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Content', $entry->getContent());
    }

    // Revert to Description if no Content

    public function testGetsContentFromRss20Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/description/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getContent());
    }

    public function testGetsContentFromRss094Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/description/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getContent());
    }

    public function testGetsContentFromRss093Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/description/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getContent());
    }

    public function testGetsContentFromRss092Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/description/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getContent());
    }

    public function testGetsContentFromRss091Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/description/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getContent());
    }

    public function testGetsContentFromRss10Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/description/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getContent());
    }

    public function testGetsContentFromRss090Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/description/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('Entry Description', $entry->getContent());
    }

    // Missing Content and Description

    public function testGetsContentFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getContent());
    }

    public function testGetsContentFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getContent());
    }

    public function testGetsContentFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getContent());
    }

    public function testGetsContentFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getContent());
    }

    public function testGetsContentFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getContent());
    }

    public function testGetsContentFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getContent());
    }

    public function testGetsContentFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/content/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getContent());
    }

    /**
     * Get Link (Unencoded Text).
     */
    public function testGetsLinkFromRss20()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry', $entry->getLink());
    }

    public function testGetsLinkFromRss094()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry', $entry->getLink());
    }

    public function testGetsLinkFromRss093()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry', $entry->getLink());
    }

    public function testGetsLinkFromRss092()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry', $entry->getLink());
    }

    public function testGetsLinkFromRss091()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry', $entry->getLink());
    }

    public function testGetsLinkFromRss10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry', $entry->getLink());
    }

    public function testGetsLinkFromRss090()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry', $entry->getLink());
    }

    // Missing Link

    public function testGetsLinkFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getLink());
    }

    public function testGetsLinkFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getLink());
    }

    public function testGetsLinkFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getLink());
    }

    public function testGetsLinkFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getLink());
    }

    public function testGetsLinkFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getLink());
    }

    public function testGetsLinkFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getLink());
    }

    public function testGetsLinkFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getLink());
    }

    /**
     * Get DateModified (Unencoded Text).
     */
    public function testGetsDateModifiedFromRss20()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/rss20.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    /**
     * @group ZF-8702
     */
    public function testParsesCorrectDateIfMissingOffsetWhenSystemUsesUSLocale()
    {
        $locale = new Zend_Locale('en_US');
        Zend_Registry::set('Zend_Locale', $locale);
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/rss20_en_US.xml')
        );
        $entry = $feed->current();
        $fdate = $entry->getDateModified();
        $edate = new Zend_Date();
        $edate->set('2010-01-04T02:14:00-0600', Zend_Date::ISO_8601);
        Zend_Registry::getInstance()->offsetUnset('Zend_Locale');
        $this->assertTrue($edate->equals($fdate));
    }

    // DC 1.0

    public function testGetsDateModifiedFromRss20Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc10/rss20.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss094Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc10/rss094.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss093Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc10/rss093.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss092Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc10/rss092.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss091Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc10/rss091.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss10Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc10/rss10.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss090Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc10/rss090.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    // DC 1.1

    public function testGetsDateModifiedFromRss20Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc11/rss20.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss094Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc11/rss094.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss093Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc11/rss093.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss092Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc11/rss092.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss091Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc11/rss091.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss10Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc11/rss10.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    public function testGetsDateModifiedFromRss090Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/dc11/rss090.xml')
        );
        $entry = $feed->current();
        $edate = new Zend_Date();
        $edate->set('2009-03-07T08:03:50Z', Zend_Date::ISO_8601);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    // Missing DateModified

    public function testGetsDateModifiedFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDateModified());
    }

    public function testGetsDateModifiedFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDateModified());
    }

    public function testGetsDateModifiedFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDateModified());
    }

    public function testGetsDateModifiedFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDateModified());
    }

    public function testGetsDateModifiedFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDateModified());
    }

    public function testGetsDateModifiedFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDateModified());
    }

    public function testGetsDateModifiedFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getDateModified());
    }

    /**
     * @group ZF-7908
     */
    public function testGetsDateModifiedFromRss20UnrecognisedGmtFormat()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/datemodified/plain/rss20-zf-7908.xml')
        );
        $entry = $feed->current();
        // $this->assertEquals('Sunday 11 January 2009 09 55 59 +0000', $entry->getDateModified()->toString('EEEE dd MMMM YYYY HH mm ss ZZZ'));
        $edate = new Zend_Date();
        $edate->set('Sun, 11 Jan 2009 09:55:59 GMT', Zend_Date::RSS);
        $this->assertTrue($edate->equals($entry->getDateModified()));
    }

    /**
     * Get CommentCount (Unencoded Text).
     */

    // Slash 1.0

    public function testGetsCommentCountFromRss20Slash10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/slash10/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss094Slash10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/slash10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss093Slash10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/slash10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss092Slash10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/slash10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss091Slash10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/slash10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss10Slash10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/slash10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss090Slash10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/slash10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    // Atom Threaded 1.0

    public function testGetsCommentCountFromRss20Thread10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/thread10/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss094Thread10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/thread10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss093Thread10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/thread10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss092Thread10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/thread10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss091Thread10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/thread10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss10Thread10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/thread10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss090Thread10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/thread10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    // Atom 1.0 (Threaded 1.0 atom:link attribute)

    public function testGetsCommentCountFromRss20Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/atom10/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss094Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/atom10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss093Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/atom10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss092Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/atom10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss091Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/atom10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss10Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/atom10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss090Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/atom10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('321', $entry->getCommentCount());
    }

    // Missing Any CommentCount

    public function testGetsCommentCountFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentCount());
    }

    public function testGetsCommentCountFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentcount/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentCount());
    }

    /**
     * Get CommentLink (Unencoded Text).
     */
    public function testGetsCommentLinkFromRss20()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss094()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss093()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss092()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss091()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    // Atom 1.0

    public function testGetsCommentLinkFromRss20Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/atom10/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss094Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/atom10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss093Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/atom10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss092Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/atom10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss091Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/atom10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss10Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/atom10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss090Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/atom10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/comments', $entry->getCommentLink());
    }

    // Missing Any CommentLink

    public function testGetsCommentLinkFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentLink());
    }

    public function testGetsCommentLinkFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentlink/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentLink());
    }

    /**
     * Get CommentFeedLink (Unencoded Text).
     */

    // RSS

    public function testGetsCommentFeedLinkFromRss20WellFormedWeb10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/wellformedweb/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss094WellFormedWeb10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/wellformedweb/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss093WellFormedWeb10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/wellformedweb/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss092WellFormedWeb10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/wellformedweb/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss091WellFormedWeb10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/wellformedweb/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss10WellFormedWeb10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/wellformedweb/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss090WellFormedWeb10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/wellformedweb/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    // Atom 1.0

    public function testGetsCommentFeedLinkFromRss20Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/atom10/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss094Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/atom10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss093Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/atom10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss092Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/atom10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss091Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/atom10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss10Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/atom10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss090Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/atom10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('http://www.example.com/entry/321/feed/rss/', $entry->getCommentFeedLink());
    }

    // Missing Any CommentFeedLink

    public function testGetsCommentFeedLinkFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentFeedLink());
    }

    public function testGetsCommentFeedLinkFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/commentfeedlink/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getCommentFeedLink());
    }

    /**
     * Get category data.
     */

    // RSS 2.0

    public function testGetsCategoriesFromRss20()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCats, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    // DC 1.0

    public function testGetsCategoriesFromRss090Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss091Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss092Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss093Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss094Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss10Dc10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    // DC 1.1

    public function testGetsCategoriesFromRss090Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc11/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss091Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc11/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss092Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc11/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss093Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc11/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss094Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc11/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss10Dc11()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/dc11/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsRdf, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'topic2'], array_values($entry->getCategories()->getValues()));
    }

    // Atom 1.0

    public function testGetsCategoriesFromRss090Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/atom10/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsAtom, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'Cat & Dog'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss091Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/atom10/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsAtom, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'Cat & Dog'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss092Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/atom10/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsAtom, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'Cat & Dog'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss093Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/atom10/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsAtom, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'Cat & Dog'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss094Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/atom10/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsAtom, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'Cat & Dog'], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss10Atom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/atom10/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals($this->_expectedCatsAtom, (array) $entry->getCategories());
        $this->assertEquals(['topic1', 'Cat & Dog'], array_values($entry->getCategories()->getValues()));
    }

    // No Categories In Entry

    public function testGetsCategoriesFromRss20None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/none/rss20.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([], (array) $entry->getCategories());
        $this->assertEquals([], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss090None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/none/rss090.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([], (array) $entry->getCategories());
        $this->assertEquals([], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss091None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/none/rss091.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([], (array) $entry->getCategories());
        $this->assertEquals([], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss092None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/none/rss092.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([], (array) $entry->getCategories());
        $this->assertEquals([], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss093None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/none/rss093.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([], (array) $entry->getCategories());
        $this->assertEquals([], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss094None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/none/rss094.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([], (array) $entry->getCategories());
        $this->assertEquals([], array_values($entry->getCategories()->getValues()));
    }

    public function testGetsCategoriesFromRss10None()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/category/plain/none/rss10.xml')
        );
        $entry = $feed->current();
        $this->assertEquals([], (array) $entry->getCategories());
        $this->assertEquals([], array_values($entry->getCategories()->getValues()));
    }
}
