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
 * Abstract class for extension.
 */

/**
 * Helper to generate a "hidden" element.
 */
class Zend_View_Helper_FormHidden extends Zend_View_Helper_FormElement
{
    /**
     * Generates a 'hidden' element.
     *
     * @param string|array $name    If a string, the element name.  If an
     *                              array, all other parameters are ignored, and the array elements
     *                              are extracted in place of added parameters.
     * @param mixed        $value   the element value
     * @param array        $attribs attributes for the element tag
     *
     * @return string the element XHTML
     */
    public function formHidden($name, $value = null, ?array $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable
        if (isset($id)) {
            if (isset($attribs) && is_array($attribs)) {
                $attribs['id'] = $id;
            } else {
                $attribs = ['id' => $id];
            }
        }

        return $this->_hidden($name, $value, $attribs);
    }
}
