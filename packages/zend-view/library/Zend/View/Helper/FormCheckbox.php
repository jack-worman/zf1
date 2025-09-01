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
 * Abstract class for extension.
 */

/**
 * Helper to generate a "checkbox" element.
 *
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_View_Helper_FormCheckbox extends Zend_View_Helper_FormElement
{
    /**
     * Default checked/unchecked options.
     *
     * @var array
     */
    protected static $_defaultCheckedOptions = [
        'checkedValue' => '1',
        'uncheckedValue' => '0',
    ];

    /**
     * Generates a 'checkbox' element.
     *
     * @param string|array $name    If a string, the element name.  If an
     *                              array, all other parameters are ignored, and the array elements
     *                              are extracted in place of added parameters.
     * @param mixed        $value   the element value
     * @param array        $attribs attributes for the element tag
     *
     * @return string the element XHTML
     */
    public function formCheckbox($name, $value = null, $attribs = null, ?array $checkedOptions = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, id, value, attribs, options, listsep, disable

        $checked = false;
        if (isset($attribs['checked']) && $attribs['checked']) {
            $checked = true;
            unset($attribs['checked']);
        } elseif (isset($attribs['checked'])) {
            $checked = false;
            unset($attribs['checked']);
        }

        $checkedOptions = self::determineCheckboxInfo($value, $checked, $checkedOptions);

        // is the element disabled?
        $disabled = '';
        if ($disable) {
            $disabled = ' disabled="disabled"';
        }

        // build the element
        $xhtml = '';
        if ((!$disable && !strstr((string) $name, '[]'))
            && (empty($attribs['disableHidden']) || !$attribs['disableHidden'])
        ) {
            $xhtml = $this->_hidden($name, $checkedOptions['uncheckedValue']);
        }

        if (array_key_exists('disableHidden', $attribs)) {
            unset($attribs['disableHidden']);
        }

        $xhtml .= '<input type="checkbox"'
                .' name="'.$this->view->escape($name).'"'
                .' id="'.$this->view->escape($id).'"'
                .' value="'.$this->view->escape($checkedOptions['checkedValue']).'"'
                .$checkedOptions['checkedString']
                .$disabled
                .$this->_htmlAttribs($attribs)
                .$this->getClosingBracket();

        return $xhtml;
    }

    /**
     * Determine checkbox information.
     *
     * @param string $value
     * @param bool   $checked
     *
     * @return array
     */
    public static function determineCheckboxInfo($value, $checked, ?array $checkedOptions = null)
    {
        // Checked/unchecked values
        $checkedValue = null;
        $uncheckedValue = null;
        if (is_array($checkedOptions)) {
            if (array_key_exists('checkedValue', $checkedOptions)) {
                $checkedValue = (string) $checkedOptions['checkedValue'];
                unset($checkedOptions['checkedValue']);
            }
            if (array_key_exists('uncheckedValue', $checkedOptions)) {
                $uncheckedValue = (string) $checkedOptions['uncheckedValue'];
                unset($checkedOptions['uncheckedValue']);
            }
            if (null === $checkedValue) {
                $checkedValue = (string) array_shift($checkedOptions);
            }
            if (null === $uncheckedValue) {
                $uncheckedValue = (string) array_shift($checkedOptions);
            }
        } elseif (null !== $value) {
            $uncheckedValue = self::$_defaultCheckedOptions['uncheckedValue'];
        } else {
            $checkedValue = self::$_defaultCheckedOptions['checkedValue'];
            $uncheckedValue = self::$_defaultCheckedOptions['uncheckedValue'];
        }

        // is the element checked?
        $checkedString = '';
        if ($checked || ((string) $value === $checkedValue)) {
            $checkedString = ' checked="checked"';
            $checked = true;
        } else {
            $checked = false;
        }

        // Checked value should be value if no checked options provided
        if (null == $checkedValue) {
            $checkedValue = $value;
        }

        return [
            'checked' => $checked,
            'checkedString' => $checkedString,
            'checkedValue' => $checkedValue,
            'uncheckedValue' => $uncheckedValue,
        ];
    }
}
