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
class Zend_Translate_Adapter_Ini extends Zend_Translate_Adapter
{
    private $_data = [];

    /**
     * Load translation data.
     *
     * @param string|array $data
     * @param string       $locale  Locale/Language to add data for, identical with locale identifier,
     *                              see Zend_Locale for more information
     * @param array        $options OPTIONAL Options to use
     *
     * @return array
     *
     * @throws Zend_Translate_Exception Ini file not found
     */
    protected function _loadTranslationData($data, $locale, array $options = [])
    {
        $this->_data = [];
        if (!file_exists((string) $data)) {
            throw new Zend_Translate_Exception("Ini file '".$data."' not found");
        }

        $inidata = parse_ini_file($data, false);
        if (!isset($this->_data[$locale])) {
            $this->_data[$locale] = [];
        }

        $this->_data[$locale] = array_merge($this->_data[$locale], $inidata);

        return $this->_data;
    }

    /**
     * returns the adapters name.
     *
     * @return string
     */
    public function toString()
    {
        return 'Ini';
    }
}
