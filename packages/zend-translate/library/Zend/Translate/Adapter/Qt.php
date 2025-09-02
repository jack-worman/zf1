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
 */
class Zend_Translate_Adapter_Qt extends Zend_Translate_Adapter
{
    // Internal variables
    private $_file = false;
    private $_cleared = [];
    private $_transunit;
    private $_source;
    private $_target;
    private $_scontent;
    private $_tcontent;
    private $_stag = false;
    private $_ttag = true;
    private $_data = [];

    /**
     * Load translation data (QT file reader).
     *
     * @param string $locale   Locale/Language to add data for, identical with locale identifier,
     *                         see Zend_Locale for more information
     * @param string $filename QT file to add, full path must be given for access
     *
     * @return array
     *
     * @throws Zend_Translation_Exception
     */
    protected function _loadTranslationData($filename, $locale, array $options = [])
    {
        $this->_data = [];
        if (!is_readable($filename)) {
            throw new Zend_Translate_Exception('Translation file \''.$filename.'\' is not readable.');
        }

        $this->_target = $locale;

        $encoding = $this->_findEncoding($filename);
        $this->_file = xml_parser_create($encoding);
        xml_set_object($this->_file, $this);
        xml_parser_set_option($this->_file, XML_OPTION_CASE_FOLDING, 0);
        xml_set_element_handler($this->_file, '_startElement', '_endElement');
        xml_set_character_data_handler($this->_file, '_contentElement');

        try {
            Zend_Xml_Security::scanFile($filename);
        } catch (Zend_Xml_Exception $e) {
            throw new Zend_Translate_Exception($e->getMessage());
        }

        if (!xml_parse($this->_file, file_get_contents($filename))) {
            $ex = sprintf('XML error: %s at line %d of file %s',
                xml_error_string(xml_get_error_code($this->_file)),
                xml_get_current_line_number($this->_file),
                $filename);
            xml_parser_free($this->_file);
            throw new Zend_Translate_Exception($ex);
        }

        return $this->_data;
    }

    private function _startElement($file, $name, $attrib)
    {
        switch (strtolower((string) $name)) {
            case 'message':
                $this->_source = null;
                $this->_stag = false;
                $this->_ttag = false;
                $this->_scontent = null;
                $this->_tcontent = null;
                break;
            case 'source':
                $this->_stag = true;
                break;
            case 'translation':
                $this->_ttag = true;
                break;
            default:
                break;
        }
    }

    private function _endElement($file, $name)
    {
        switch (strtolower((string) $name)) {
            case 'source':
                $this->_stag = false;
                break;

            case 'translation':
                if (!empty($this->_scontent) and !empty($this->_tcontent)
                    or (false === isset($this->_data[$this->_target][$this->_scontent]))) {
                    $this->_data[$this->_target][$this->_scontent] = $this->_tcontent;
                }
                $this->_ttag = false;
                break;

            default:
                break;
        }
    }

    private function _contentElement($file, $data)
    {
        if (true === $this->_stag) {
            $this->_scontent .= $data;
        }

        if (true === $this->_ttag) {
            $this->_tcontent .= $data;
        }
    }

    private function _findEncoding($filename)
    {
        $file = file_get_contents($filename, false, null, 0, 100);
        if (false !== strpos((string) $file, 'encoding')) {
            $encoding = substr((string) $file, strpos((string) $file, 'encoding') + 9);
            $encoding = substr((string) $encoding, 1, strpos((string) $encoding, $encoding[0], 1) - 1);

            return $encoding;
        }

        return 'UTF-8';
    }

    /**
     * Returns the adapter name.
     *
     * @return string
     */
    public function toString()
    {
        return 'Qt';
    }
}
