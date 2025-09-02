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

/**
 * Helper to generate a set of checkbox button elements.
 */
class Zend_View_Helper_FormMultiCheckbox extends Zend_View_Helper_FormRadio
{
    /**
     * Input type to use.
     *
     * @var string
     */
    protected $_inputType = 'checkbox';

    /**
     * Whether or not this element represents an array collection by default.
     *
     * @var bool
     */
    protected $_isArray = true;

    /**
     * Generates a set of checkbox button elements.
     *
     * @param string|array $name    If a string, the element name.  If an
     *                              array, all other parameters are ignored, and the array elements
     *                              are extracted in place of added parameters.
     * @param mixed        $value   the checkbox value to mark as 'checked'
     * @param array        $options an array of key-value pairs where the array
     *                              key is the checkbox value, and the array value is the radio text
     * @param array|string $attribs attributes added to each radio
     *
     * @return string the radio buttons XHTML
     */
    public function formMultiCheckbox($name, $value = null, $attribs = null,
        $options = null, $listsep = "<br />\n")
    {
        return $this->formRadio($name, $value, $attribs, $options, $listsep);
    }
}
