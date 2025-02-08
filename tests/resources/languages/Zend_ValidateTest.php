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
 * @see Zend_Locale
 */
// require_once 'Zend/Locale.php';

/**
 * @category   Zend
 *
 * @group      Zend_Exception
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
#[AllowDynamicProperties]
class resources_languages_Zend_ValidateTest extends PHPUnit\Framework\TestCase
{
    protected $_langDir;
    protected $_languages = [];
    protected $_translations = [];

    public function setUp(): void
    {
        $this->markTestSkipped('skip this for now, maybe extract and fix original resources into a zend-resources package later...');

        return;

        $this->_langDir = dirname(dirname(dirname(__DIR__)))
                        .DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'languages';
        if (!is_readable($this->_langDir)) {
            throw new Exception('Language resource directory "'.$this->_langDir.'" not readable.');
        }

        // Show only a specific translation?
        $langs = 'all';
        if (defined('TESTS_ZEND_RESOURCES_TRANSLATIONS')) {
            $langs = constant('TESTS_ZEND_RESOURCES_TRANSLATIONS');
            if ('en' == $langs || !Zend_Locale::isLocale($langs, true, false)) {
                $langs = 'all';
            }
        }

        // detect languages
        foreach (new DirectoryIterator($this->_langDir) as $entry) {
            if (!$entry->isDir()) {
                continue;
            }

            // skip "." or ".." or ".svn"
            $fname = $entry->getFilename();
            if ('.' == $fname[0]) {
                continue;
            }

            // add all languages for testIsLocale
            if ('all' == $langs || $langs == $fname || 'en' == $fname) {
                $this->_languages[] = $fname;
            }

            // include Zend_Validate translation tables
            $translationFile = $entry->getPathname().DIRECTORY_SEPARATOR.'Zend_Validate.php';
            if (file_exists((string) $translationFile)) {
                $translation = include $translationFile;
                if (!is_array($translation)) {
                    $this->fail("Invalid or empty translation table found for language '{$fname}'");
                }

                if ('all' == $langs || $langs == $fname || 'en' == $fname) {
                    $this->_translations[$fname] = $translation;
                }
            }
        }
    }

    /**
     * Tests if the given language is really a language.
     */
    public function testIsLocale()
    {
        foreach ($this->_languages as $lang) {
            if (!Zend_Locale::isLocale($lang, true, false)) {
                $this->fail("Language directory '{$lang}' not a valid locale");
            }
        }
    }

    /**
     * Tests if all english original keys have the same translations.
     */
    public function testEnglishKeySameAsValue()
    {
        $errors = [];
        $cnt = 0;
        foreach ($this->_translations['en'] as $key => $value) {
            if ($key !== $value) {
                ++$cnt;
                $errors['en '.$cnt] = "The key $key is not identical in the english original";
            }
        }

        if (!empty($errors)) {
            $this->fail(var_export($errors, true));
        }
    }

    /**
     * Tests if all translation keys are also available in the english original.
     */
    public function testTranslationAvailableInEnglish()
    {
        $errors = [];
        $cnt = 0;
        foreach ($this->_translations as $lang => $translation) {
            if ('en' == $lang) {
                continue;
            }

            foreach ($translation as $key => $value) {
                if (!isset($this->_translations['en'][$key])) {
                    ++$cnt;
                    $errors[$lang.' '.$cnt] = 'The key "'.$key."\" isn't available within english translation file";
                }
            }
        }

        if (!empty($errors)) {
            $this->fail(var_export($errors, true));
        }
    }

    /**
     * Tests if the key is translated.
     */
    public function testTranslationDiffersFromEnglish()
    {
        $errors = [];
        $cnt = 0;
        foreach ($this->_translations as $lang => $translation) {
            if ('en' == $lang) {
                continue;
            }

            foreach ($translation as $key => $value) {
                if ($key == $value) {
                    ++$cnt;
                    $errors[$lang.' '.$cnt] = 'The translated message "'.$value.'" is the same the english version';
                }
            }
        }

        if (!empty($errors)) {
            $this->fail(var_export($errors, true));
        }
    }

    /**
     * Tests if all placeholders from the original are also available within the translation.
     */
    public function testPlaceholder()
    {
        $errors = [];
        $cnt = 0;
        foreach ($this->_translations as $lang => $translation) {
            if ('en' == $lang) { // not needed to test - see testEnglishKeySameAsValue
                continue;
            }

            foreach ($translation as $key => $value) {
                if (preg_match_all('/(\%.+\%)/U', $key, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        if (!strpos((string) $value, $match[1])) {
                            ++$cnt;
                            $errors[$lang.' '.$cnt] = 'Missing placeholder "'.$match[1].'" within "'.$value.'"';
                        }
                    }
                }
            }
        }

        if (!empty($errors)) {
            $this->fail(var_export($errors, true));
        }
    }

    /**
     * Tests if all english originals are translated.
     */
    public function testAllTranslated()
    {
        $errors = [];
        $cnt = 0;
        foreach ($this->_translations as $lang => $translation) {
            foreach ($this->_translations['en'] as $key => $value) {
                if ('en' == $lang) {
                    continue;
                }

                if (!isset($translation[$key])) {
                    ++$cnt;
                    $errors[$lang.' '.$cnt] = 'Message "'.$key.'" not translated';
                }
            }
        }

        if (!empty($errors)) {
            $this->fail(var_export($errors, true));
        }
    }
}
