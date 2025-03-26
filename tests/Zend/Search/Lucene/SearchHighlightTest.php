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
 * Zend_Search_Lucene.
 */
// require_once 'Zend/Search/Lucene.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Search_Lucene
 */
#[AllowDynamicProperties]
class Zend_Search_Lucene_SearchHighlightTest extends PHPUnit_Framework_TestCase
{
    /**
     * Wildcard pattern minimum preffix.
     *
     * @var int
     */
    protected $_wildcardMinPrefix;

    /**
     * Fuzzy search default preffix length.
     *
     * @var int
     */
    protected $_defaultPrefixLength;

    public function setUp()
    {
        // require_once 'Zend/Search/Lucene/Search/Query/Wildcard.php';
        $this->_wildcardMinPrefix = Zend_Search_Lucene_Search_Query_Wildcard::getMinPrefixLength();
        Zend_Search_Lucene_Search_Query_Wildcard::setMinPrefixLength(0);

        // require_once 'Zend/Search/Lucene/Search/Query/Fuzzy.php';
        $this->_defaultPrefixLength = Zend_Search_Lucene_Search_Query_Fuzzy::getDefaultPrefixLength();
        Zend_Search_Lucene_Search_Query_Fuzzy::setDefaultPrefixLength(0);
    }

    public function tearDown()
    {
        Zend_Search_Lucene_Search_Query_Wildcard::setMinPrefixLength($this->_wildcardMinPrefix);
        Zend_Search_Lucene_Search_Query_Fuzzy::setDefaultPrefixLength($this->_defaultPrefixLength);
    }

    public function testHtmlFragmentHighlightMatches()
    {
        $query = Zend_Search_Lucene_Search_QueryParser::parse('title:"The Right Way" AND text:go');

        $highlightedHtmlFragment = $query->htmlFragmentHighlightMatches('Text highlighting using Zend_Search_Lucene is the right way to go!');

        $this->assertEquals($highlightedHtmlFragment,
            'Text highlighting using Zend_Search_Lucene is <b style="color:black;background-color:#66ffff">the</b> <b style="color:black;background-color:#66ffff">right</b> <b style="color:black;background-color:#66ffff">way</b> to <b style="color:black;background-color:#ff66ff">go</b>!');
    }

    //    public function testHtmlFragmentHighlightMatchesCyrillic()
    //    {
    //        $query = Zend_Search_Lucene_Search_QueryParser::parse('title:"некоторый текст" AND text:поехали');
    //
    //        $highlightedHtmlFragment = $query->htmlFragmentHighlightMatches('Подсвечиваем некоторый текст с использованием Zend_Search_Lucene. Поехали!');
    //
    //        $this->assertEquals($highlightedHtmlFragment,
    //                            'Text highlighting using Zend_Search_Lucene is <b style="color:black;background-color:#66ffff">the</b> <b style="color:black;background-color:#66ffff">right</b> <b style="color:black;background-color:#66ffff">way</b> to <b style="color:black;background-color:#ff66ff">go</b>!');
    //    }
    //
    //    public function testHtmlFragmentHighlightMatchesCyrillicWindows()
    //    {
    //        $query = Zend_Search_Lucene_Search_QueryParser::parse('title:"Некоторый текст" AND text:поехали');
    //
    //        $highlightedHtmlFragment =
    //                $query->htmlFragmentHighlightMatches(iconv('UTF-8',
    //                                                           'Windows-1251',
    //                                                           'Подсвечиваем некоторый текст с использованием Zend_Search_Lucene. Поехали!'),
    //                                                     'Windows-1251');
    //
    //        $this->assertEquals($highlightedHtmlFragment,
    //                            'Text highlighting using Zend_Search_Lucene is <b style="color:black;background-color:#66ffff">the</b> <b style="color:black;background-color:#66ffff">right</b> <b style="color:black;background-color:#66ffff">way</b> to <b style="color:black;background-color:#ff66ff">go</b>!');
    //    }

    public function testHighlightPhrasePlusTerm()
    {
        $query = Zend_Search_Lucene_Search_QueryParser::parse('title:"The Right Way" AND text:go');

        $html = '<HTML>'
                .'<HEAD><TITLE>Page title</TITLE></HEAD>'
                .'<BODY>'
                .'Text highlighting using Zend_Search_Lucene is the right way to go!'
                .'</BODY>'
              .'</HTML>';

        $highlightedHTML = $query->highlightMatches($html);

        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">the</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">right</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">way</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#ff66ff">go</b>'));
    }

    public function testHighlightMultitermWithProhibitedTerms()
    {
        $query = Zend_Search_Lucene_Search_QueryParser::parse('+text +highlighting -using -right +go');

        $html = '<HTML>'
                .'<HEAD><TITLE>Page title</TITLE></HEAD>'
                .'<BODY>'
                .'Text highlighting using Zend_Search_Lucene is the right way to go!'
                .'</BODY>'
              .'</HTML>';

        $highlightedHTML = $query->highlightMatches($html);

        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">Text</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#ff66ff">highlighting</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, 'using Zend_Search_Lucene is the right way to'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#ffff66">go</b>'));
    }

    public function testHighlightWildcard1()
    {
        $query = Zend_Search_Lucene_Search_QueryParser::parse('te?t');

        $html = '<HTML>'
                .'<HEAD><TITLE>Page title</TITLE></HEAD>'
                .'<BODY>'
                .'Test of text highlighting using wildcard query with question mark. Testing...'
                .'</BODY>'
              .'</HTML>';

        $highlightedHTML = $query->highlightMatches($html);

        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">Test</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">text</b>'));
        // Check that 'Testing' word is not highlighted
        $this->assertTrue(false !== strpos((string) $highlightedHTML, 'mark. Testing...'));
    }

    public function testHighlightWildcard2()
    {
        $query = Zend_Search_Lucene_Search_QueryParser::parse('te?t*');

        $html = '<HTML>'
                .'<HEAD><TITLE>Page title</TITLE></HEAD>'
                .'<BODY>'
                .'Test of text highlighting using wildcard query with question mark. Testing...'
                .'</BODY>'
              .'</HTML>';

        $highlightedHTML = $query->highlightMatches($html);

        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">Test</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">text</b>'));
        // Check that 'Testing' word is also highlighted
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">Testing</b>'));
    }

    public function testHighlightFuzzy1()
    {
        $query = Zend_Search_Lucene_Search_QueryParser::parse('test~');

        $html = '<HTML>'
                .'<HEAD><TITLE>Page title</TITLE></HEAD>'
                .'<BODY>'
                .'Test of text fuzzy search terms highlighting. '
                .'Words: test, text, latest, left, list, next, ...'
                .'</BODY>'
              .'</HTML>';

        $highlightedHTML = $query->highlightMatches($html);

        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">Test</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">test</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">text</b>'));
        // Check that other words are not highlighted
        $this->assertTrue(false !== strpos((string) $highlightedHTML, 'latest, left, list, next, ...'));
    }

    public function testHighlightFuzzy2()
    {
        $query = Zend_Search_Lucene_Search_QueryParser::parse('test~0.4');

        $html = '<HTML>'
                .'<HEAD><TITLE>Page title</TITLE></HEAD>'
                .'<BODY>'
                .'Test of text fuzzy search terms highlighting. '
                .'Words: test, text, latest, left, list, next, ...'
                .'</BODY>'
              .'</HTML>';

        $highlightedHTML = $query->highlightMatches($html);

        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">Test</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">test</b>'));
        // Check that other words are also highlighted
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">text</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">latest</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">left</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">list</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">next</b>'));
    }

    public function testHighlightRangeInclusive()
    {
        $query = Zend_Search_Lucene_Search_QueryParser::parse('[business TO by]');

        $html = '<HTML>'
                .'<HEAD><TITLE>Page title</TITLE></HEAD>'
                .'<BODY>'
                .'Test of text using range query. '
                .'It has to match "business", "by", "buss" and "but" words, but has to skip "bus"'
                .'</BODY>'
              .'</HTML>';

        $highlightedHTML = $query->highlightMatches($html);

        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">business</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">by</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">buss</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">but</b>'));
        // Check that "bus" word is skipped
        $this->assertTrue(false !== strpos((string) $highlightedHTML, 'has to skip "bus"'));
    }

    public function testHighlightRangeNonInclusive()
    {
        $query = Zend_Search_Lucene_Search_QueryParser::parse('{business TO by}');

        $html = '<HTML>'
                .'<HEAD><TITLE>Page title</TITLE></HEAD>'
                .'<BODY>'
                .'Test of text using range query. '
                .'It has to match "buss" and "but" words, but has to skip "business", "by" and "bus"'
                .'</BODY>'
              .'</HTML>';

        $highlightedHTML = $query->highlightMatches($html);

        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">buss</b>'));
        $this->assertTrue(false !== strpos((string) $highlightedHTML, '<b style="color:black;background-color:#66ffff">but</b>'));
        // Check that "bus" word is skipped
        $this->assertTrue(false !== strpos((string) $highlightedHTML, 'has to skip "business", "by" and "bus"'));
    }
}
