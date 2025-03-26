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

/** Internally used classes */
// require_once 'Zend/Pdf/Element/Name.php';

/** Zend_Pdf_Resource_Font_Simple_Standard */
// require_once 'Zend/Pdf/Resource/Font/Simple/Standard.php';

/**
 * Implementation for the standard PDF font Times-BoldItalic.
 *
 * This class was generated automatically using the font information and metric
 * data contained in the Adobe Font Metric (AFM) files, available here:
 * {@link http://partners.adobe.com/public/developer/en/pdf/Core14_AFMs.zip}
 *
 * The PHP script used to generate this class can be found in the /tools
 * directory of the framework distribution. If you need to make modifications to
 * this class, chances are the same modifications are needed for the rest of the
 * standard fonts. You should modify the script and regenerate the classes
 * instead of changing this class file by hand.
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Pdf_Resource_Font_Simple_Standard_TimesBoldItalic extends Zend_Pdf_Resource_Font_Simple_Standard
{
    /**** Public Interface ****/

    /* Object Lifecycle */

    /**
     * Object constructor.
     */
    public function __construct()
    {
        parent::__construct();

        /* Object properties */

        /* The font names are stored internally as Unicode UTF-16BE-encoded
         * strings. Since this information is static, save unnecessary trips
         * through iconv() and just use pre-encoded hexidecimal strings.
         */
        $this->_fontNames[Zend_Pdf_Font::NAME_COPYRIGHT]['en'] =
          "\x00\x43\x00\x6f\x00\x70\x00\x79\x00\x72\x00\x69\x00\x67\x00\x68\x00"
          ."\x74\x00\x20\x00\x28\x00\x63\x00\x29\x00\x20\x00\x31\x00\x39\x00"
          ."\x38\x00\x35\x00\x2c\x00\x20\x00\x31\x00\x39\x00\x38\x00\x37\x00"
          ."\x2c\x00\x20\x00\x31\x00\x39\x00\x38\x00\x39\x00\x2c\x00\x20\x00"
          ."\x31\x00\x39\x00\x39\x00\x30\x00\x2c\x00\x20\x00\x31\x00\x39\x00"
          ."\x39\x00\x33\x00\x2c\x00\x20\x00\x31\x00\x39\x00\x39\x00\x37\x00"
          ."\x20\x00\x41\x00\x64\x00\x6f\x00\x62\x00\x65\x00\x20\x00\x53\x00"
          ."\x79\x00\x73\x00\x74\x00\x65\x00\x6d\x00\x73\x00\x20\x00\x49\x00"
          ."\x6e\x00\x63\x00\x6f\x00\x72\x00\x70\x00\x6f\x00\x72\x00\x61\x00"
          ."\x74\x00\x65\x00\x64\x00\x2e\x00\x20\x00\x20\x00\x41\x00\x6c\x00"
          ."\x6c\x00\x20\x00\x52\x00\x69\x00\x67\x00\x68\x00\x74\x00\x73\x00"
          ."\x20\x00\x52\x00\x65\x00\x73\x00\x65\x00\x72\x00\x76\x00\x65\x00"
          ."\x64\x00\x2e\x00\x54\x00\x69\x00\x6d\x00\x65\x00\x73\x00\x20\x00"
          ."\x69\x00\x73\x00\x20\x00\x61\x00\x20\x00\x74\x00\x72\x00\x61\x00"
          ."\x64\x00\x65\x00\x6d\x00\x61\x00\x72\x00\x6b\x00\x20\x00\x6f\x00"
          ."\x66\x00\x20\x00\x4c\x00\x69\x00\x6e\x00\x6f\x00\x74\x00\x79\x00"
          ."\x70\x00\x65\x00\x2d\x00\x48\x00\x65\x00\x6c\x00\x6c\x00\x20\x00"
          ."\x41\x00\x47\x00\x20\x00\x61\x00\x6e\x00\x64\x00\x2f\x00\x6f\x00"
          ."\x72\x00\x20\x00\x69\x00\x74\x00\x73\x00\x20\x00\x73\x00\x75\x00"
          ."\x62\x00\x73\x00\x69\x00\x64\x00\x69\x00\x61\x00\x72\x00\x69\x00"
          ."\x65\x00\x73\x00\x2e";
        $this->_fontNames[Zend_Pdf_Font::NAME_FAMILY]['en'] =
          "\x00\x54\x00\x69\x00\x6d\x00\x65\x00\x73";
        $this->_fontNames[Zend_Pdf_Font::NAME_STYLE]['en'] =
          "\x00\x42\x00\x6f\x00\x6c\x00\x64";
        $this->_fontNames[Zend_Pdf_Font::NAME_ID]['en'] =
          "\x00\x34\x00\x33\x00\x30\x00\x36\x00\x36";
        $this->_fontNames[Zend_Pdf_Font::NAME_FULL]['en'] =
          "\x00\x54\x00\x69\x00\x6d\x00\x65\x00\x73\x00\x2d\x00\x42\x00\x6f\x00"
          ."\x6c\x00\x64\x00\x49\x00\x74\x00\x61\x00\x6c\x00\x69\x00\x63\x00"
          ."\x20\x00\x42\x00\x6f\x00\x6c\x00\x64";
        $this->_fontNames[Zend_Pdf_Font::NAME_VERSION]['en'] =
          "\x00\x30\x00\x30\x00\x32\x00\x2e\x00\x30\x00\x30\x00\x30";
        $this->_fontNames[Zend_Pdf_Font::NAME_POSTSCRIPT]['en'] =
          "\x00\x54\x00\x69\x00\x6d\x00\x65\x00\x73\x00\x2d\x00\x42\x00\x6f\x00"
          ."\x6c\x00\x64\x00\x49\x00\x74\x00\x61\x00\x6c\x00\x69\x00\x63";

        $this->_isBold = true;
        $this->_isItalic = true;
        $this->_isMonospaced = false;

        $this->_underlinePosition = -100;
        $this->_underlineThickness = 50;
        $this->_strikePosition = 225;
        $this->_strikeThickness = 50;

        $this->_unitsPerEm = 1000;

        $this->_ascent = 683;
        $this->_descent = -217;
        $this->_lineGap = 300;

        /* The glyph numbers assigned here are synthetic; they do not match the
         * actual glyph numbers used by the font. This is not a big deal though
         * since this data never makes it to the PDF file. It is only used
         * internally for layout calculations.
         */
        $this->_glyphWidths = [
            0x00 => 0x01F4,   0x01 => 0xFA,   0x02 => 0x0185,   0x03 => 0x022B,
            0x04 => 0x01F4,   0x05 => 0x01F4,   0x06 => 0x0341,   0x07 => 0x030A,
            0x08 => 0x014D,   0x09 => 0x014D,   0x0A => 0x014D,   0x0B => 0x01F4,
            0x0C => 0x023A,   0x0D => 0xFA,   0x0E => 0x014D,   0x0F => 0xFA,
            0x10 => 0x0116,   0x11 => 0x01F4,   0x12 => 0x01F4,   0x13 => 0x01F4,
            0x14 => 0x01F4,   0x15 => 0x01F4,   0x16 => 0x01F4,   0x17 => 0x01F4,
            0x18 => 0x01F4,   0x19 => 0x01F4,   0x1A => 0x01F4,   0x1B => 0x014D,
            0x1C => 0x014D,   0x1D => 0x023A,   0x1E => 0x023A,   0x1F => 0x023A,
            0x20 => 0x01F4,   0x21 => 0x0340,   0x22 => 0x029B,   0x23 => 0x029B,
            0x24 => 0x029B,   0x25 => 0x02D2,   0x26 => 0x029B,   0x27 => 0x029B,
            0x28 => 0x02D2,   0x29 => 0x030A,   0x2A => 0x0185,   0x2B => 0x01F4,
            0x2C => 0x029B,   0x2D => 0x0263,   0x2E => 0x0379,   0x2F => 0x02D2,
            0x30 => 0x02D2,   0x31 => 0x0263,   0x32 => 0x02D2,   0x33 => 0x029B,
            0x34 => 0x022C,   0x35 => 0x0263,   0x36 => 0x02D2,   0x37 => 0x029B,
            0x38 => 0x0379,   0x39 => 0x029B,   0x3A => 0x0263,   0x3B => 0x0263,
            0x3C => 0x014D,   0x3D => 0x0116,   0x3E => 0x014D,   0x3F => 0x023A,
            0x40 => 0x01F4,   0x41 => 0x014D,   0x42 => 0x01F4,   0x43 => 0x01F4,
            0x44 => 0x01BC,   0x45 => 0x01F4,   0x46 => 0x01BC,   0x47 => 0x014D,
            0x48 => 0x01F4,   0x49 => 0x022C,   0x4A => 0x0116,   0x4B => 0x0116,
            0x4C => 0x01F4,   0x4D => 0x0116,   0x4E => 0x030A,   0x4F => 0x022C,
            0x50 => 0x01F4,   0x51 => 0x01F4,   0x52 => 0x01F4,   0x53 => 0x0185,
            0x54 => 0x0185,   0x55 => 0x0116,   0x56 => 0x022C,   0x57 => 0x01BC,
            0x58 => 0x029B,   0x59 => 0x01F4,   0x5A => 0x01BC,   0x5B => 0x0185,
            0x5C => 0x015C,   0x5D => 0xDC,   0x5E => 0x015C,   0x5F => 0x023A,
            0x60 => 0x0185,   0x61 => 0x01F4,   0x62 => 0x01F4,   0x63 => 0xA7,
            0x64 => 0x01F4,   0x65 => 0x01F4,   0x66 => 0x01F4,   0x67 => 0x01F4,
            0x68 => 0x0116,   0x69 => 0x01F4,   0x6A => 0x01F4,   0x6B => 0x014D,
            0x6C => 0x014D,   0x6D => 0x022C,   0x6E => 0x022C,   0x6F => 0x01F4,
            0x70 => 0x01F4,   0x71 => 0x01F4,   0x72 => 0xFA,   0x73 => 0x01F4,
            0x74 => 0x015E,   0x75 => 0x014D,   0x76 => 0x01F4,   0x77 => 0x01F4,
            0x78 => 0x01F4,   0x79 => 0x03E8,   0x7A => 0x03E8,   0x7B => 0x01F4,
            0x7C => 0x014D,   0x7D => 0x014D,   0x7E => 0x014D,   0x7F => 0x014D,
            0x80 => 0x014D,   0x81 => 0x014D,   0x82 => 0x014D,   0x83 => 0x014D,
            0x84 => 0x014D,   0x85 => 0x014D,   0x86 => 0x014D,   0x87 => 0x014D,
            0x88 => 0x014D,   0x89 => 0x03E8,   0x8A => 0x03B0,   0x8B => 0x010A,
            0x8C => 0x0263,   0x8D => 0x02D2,   0x8E => 0x03B0,   0x8F => 0x012C,
            0x90 => 0x02D2,   0x91 => 0x0116,   0x92 => 0x0116,   0x93 => 0x01F4,
            0x94 => 0x02D2,   0x95 => 0x01F4,   0x96 => 0x0185,   0x97 => 0x01BC,
            0x98 => 0x01F4,   0x99 => 0x022C,   0x9A => 0x01BC,   0x9B => 0x0263,
            0x9C => 0x023A,   0x9D => 0x0263,   0x9E => 0x029B,   0x9F => 0x01F4,
            0xA0 => 0x02D2,   0xA1 => 0x01BC,   0xA2 => 0x0185,   0xA3 => 0x01BC,
            0xA4 => 0x02D2,   0xA5 => 0x02D2,   0xA6 => 0x01F4,   0xA7 => 0x02D2,
            0xA8 => 0x022C,   0xA9 => 0x029B,   0xAA => 0x02D2,   0xAB => 0xFA,
            0xAC => 0x02EB,   0xAD => 0x029B,   0xAE => 0x01BC,   0xAF => 0x01F4,
            0xB0 => 0x02D2,   0xB1 => 0x0116,   0xB2 => 0x01F4,   0xB3 => 0x0263,
            0xB4 => 0x029B,   0xB5 => 0x01F4,   0xB6 => 0x029B,   0xB7 => 0x0185,
            0xB8 => 0x0185,   0xB9 => 0x0116,   0xBA => 0x01EE,   0xBB => 0x029B,
            0xBC => 0x02D2,   0xBD => 0x022C,   0xBE => 0x01F4,   0xBF => 0x029B,
            0xC0 => 0x0185,   0xC1 => 0x01BC,   0xC2 => 0x0263,   0xC3 => 0x0263,
            0xC4 => 0x02D2,   0xC5 => 0x029B,   0xC6 => 0x022C,   0xC7 => 0x0260,
            0xC8 => 0x02D2,   0xC9 => 0x022C,   0xCA => 0x012C,   0xCB => 0x02D2,
            0xCC => 0x029B,   0xCD => 0x029B,   0xCE => 0x023A,   0xCF => 0x022C,
            0xD0 => 0x0263,   0xD1 => 0x01EE,   0xD2 => 0x01BC,   0xD3 => 0x02D2,
            0xD4 => 0x0116,   0xD5 => 0x029B,   0xD6 => 0x01F4,   0xD7 => 0x01BC,
            0xD8 => 0x01BC,   0xD9 => 0x022C,   0xDA => 0x022C,   0xDB => 0x02D2,
            0xDC => 0x0185,   0xDD => 0x023A,   0xDE => 0xDC,   0xDF => 0x02EB,
            0xE0 => 0x02D2,   0xE1 => 0x0185,   0xE2 => 0x0258,   0xE3 => 0x029B,
            0xE4 => 0x0185,   0xE5 => 0x01F4,   0xE6 => 0x0263,   0xE7 => 0x0263,
            0xE8 => 0x0225,   0xE9 => 0x02D2,   0xEA => 0x029B,   0xEB => 0x0116,
            0xEC => 0x016E,   0xED => 0x01BC,   0xEE => 0x02D2,   0xEF => 0x029B,
            0xF0 => 0x029B,   0xF1 => 0x01BC,   0xF2 => 0x0185,   0xF3 => 0x0116,
            0xF4 => 0x02D2,   0xF5 => 0x01F4,   0xF6 => 0x01F4,   0xF7 => 0x0185,
            0xF8 => 0x0116,   0xF9 => 0x02D2,   0xFA => 0x02D2,   0xFB => 0x0264,
            0xFC => 0x01F4,   0xFD => 0x012C,   0xFE => 0x02D2,   0xFF => 0x0240,
            0x0100 => 0x0116, 0x0101 => 0x01F4, 0x0102 => 0x029B, 0x0103 => 0x01F4,
            0x0104 => 0x02EE, 0x0105 => 0x022C, 0x0106 => 0x017E, 0x0107 => 0x029B,
            0x0108 => 0x0263, 0x0109 => 0x03E8, 0x010A => 0x01BC, 0x010B => 0x0185,
            0x010C => 0x0185, 0x010D => 0x0263, 0x010E => 0x02EE, 0x010F => 0x0225,
            0x0110 => 0x01F4, 0x0111 => 0x022C, 0x0112 => 0x02D2, 0x0113 => 0x029B,
            0x0114 => 0x01BC, 0x0115 => 0x01F4, 0x0116 => 0x02EE, 0x0117 => 0x022C,
            0x0118 => 0x022C, 0x0119 => 0x02D2, 0x011A => 0x0190, 0x011B => 0x01F4,
            0x011C => 0x029B, 0x011D => 0x022C, 0x011E => 0x0225, 0x011F => 0x02D2,
            0x0120 => 0x0185, 0x0121 => 0x02D2, 0x0122 => 0x01F4, 0x0123 => 0x029B,
            0x0124 => 0x0263, 0x0125 => 0x029B, 0x0126 => 0x029B, 0x0127 => 0x029B,
            0x0128 => 0x02D2, 0x0129 => 0x0185, 0x012A => 0x029B, 0x012B => 0x0185,
            0x012C => 0x01F4, 0x012D => 0x025E, 0x012E => 0x0185, 0x012F => 0x022C,
            0x0130 => 0x0116, 0x0131 => 0x025E, 0x0132 => 0x01F4, 0x0133 => 0x022C,
            0x0134 => 0x0225, 0x0135 => 0x01F4, 0x0136 => 0x01F4, 0x0137 => 0x0185,
            0x0138 => 0x022C, 0x0139 => 0x012C, 0x013A => 0x0116, 0x013B => 0x01F4,
        ];

        /* The cmap table is similarly synthesized.
         */
        $cmapData = [
            0x20 => 0x01,   0x21 => 0x02,   0x22 => 0x03,   0x23 => 0x04,
            0x24 => 0x05,   0x25 => 0x06,   0x26 => 0x07, 0x2019 => 0x08,
            0x28 => 0x09,   0x29 => 0x0A,   0x2A => 0x0B,   0x2B => 0x0C,
            0x2C => 0x0D,   0x2D => 0x0E,   0x2E => 0x0F,   0x2F => 0x10,
            0x30 => 0x11,   0x31 => 0x12,   0x32 => 0x13,   0x33 => 0x14,
            0x34 => 0x15,   0x35 => 0x16,   0x36 => 0x17,   0x37 => 0x18,
            0x38 => 0x19,   0x39 => 0x1A,   0x3A => 0x1B,   0x3B => 0x1C,
            0x3C => 0x1D,   0x3D => 0x1E,   0x3E => 0x1F,   0x3F => 0x20,
            0x40 => 0x21,   0x41 => 0x22,   0x42 => 0x23,   0x43 => 0x24,
            0x44 => 0x25,   0x45 => 0x26,   0x46 => 0x27,   0x47 => 0x28,
            0x48 => 0x29,   0x49 => 0x2A,   0x4A => 0x2B,   0x4B => 0x2C,
            0x4C => 0x2D,   0x4D => 0x2E,   0x4E => 0x2F,   0x4F => 0x30,
            0x50 => 0x31,   0x51 => 0x32,   0x52 => 0x33,   0x53 => 0x34,
            0x54 => 0x35,   0x55 => 0x36,   0x56 => 0x37,   0x57 => 0x38,
            0x58 => 0x39,   0x59 => 0x3A,   0x5A => 0x3B,   0x5B => 0x3C,
            0x5C => 0x3D,   0x5D => 0x3E,   0x5E => 0x3F,   0x5F => 0x40,
            0x2018 => 0x41,   0x61 => 0x42,   0x62 => 0x43,   0x63 => 0x44,
            0x64 => 0x45,   0x65 => 0x46,   0x66 => 0x47,   0x67 => 0x48,
            0x68 => 0x49,   0x69 => 0x4A,   0x6A => 0x4B,   0x6B => 0x4C,
            0x6C => 0x4D,   0x6D => 0x4E,   0x6E => 0x4F,   0x6F => 0x50,
            0x70 => 0x51,   0x71 => 0x52,   0x72 => 0x53,   0x73 => 0x54,
            0x74 => 0x55,   0x75 => 0x56,   0x76 => 0x57,   0x77 => 0x58,
            0x78 => 0x59,   0x79 => 0x5A,   0x7A => 0x5B,   0x7B => 0x5C,
            0x7C => 0x5D,   0x7D => 0x5E,   0x7E => 0x5F,   0xA1 => 0x60,
            0xA2 => 0x61,   0xA3 => 0x62, 0x2044 => 0x63,   0xA5 => 0x64,
            0x0192 => 0x65,   0xA7 => 0x66,   0xA4 => 0x67,   0x27 => 0x68,
            0x201C => 0x69,   0xAB => 0x6A, 0x2039 => 0x6B, 0x203A => 0x6C,
            0xFB01 => 0x6D, 0xFB02 => 0x6E, 0x2013 => 0x6F, 0x2020 => 0x70,
            0x2021 => 0x71,   0xB7 => 0x72,   0xB6 => 0x73, 0x2022 => 0x74,
            0x201A => 0x75, 0x201E => 0x76, 0x201D => 0x77,   0xBB => 0x78,
            0x2026 => 0x79, 0x2030 => 0x7A,   0xBF => 0x7B,   0x60 => 0x7C,
            0xB4 => 0x7D, 0x02C6 => 0x7E, 0x02DC => 0x7F,   0xAF => 0x80,
            0x02D8 => 0x81, 0x02D9 => 0x82,   0xA8 => 0x83, 0x02DA => 0x84,
            0xB8 => 0x85, 0x02DD => 0x86, 0x02DB => 0x87, 0x02C7 => 0x88,
            0x2014 => 0x89,   0xC6 => 0x8A,   0xAA => 0x8B, 0x0141 => 0x8C,
            0xD8 => 0x8D, 0x0152 => 0x8E,   0xBA => 0x8F,   0xE6 => 0x90,
            0x0131 => 0x91, 0x0142 => 0x92,   0xF8 => 0x93, 0x0153 => 0x94,
            0xDF => 0x95,   0xCF => 0x96,   0xE9 => 0x97, 0x0103 => 0x98,
            0x0171 => 0x99, 0x011B => 0x9A, 0x0178 => 0x9B,   0xF7 => 0x9C,
            0xDD => 0x9D,   0xC2 => 0x9E,   0xE1 => 0x9F,   0xDB => 0xA0,
            0xFD => 0xA1, 0x0219 => 0xA2,   0xEA => 0xA3, 0x016E => 0xA4,
            0xDC => 0xA5, 0x0105 => 0xA6,   0xDA => 0xA7, 0x0173 => 0xA8,
            0xCB => 0xA9, 0x0110 => 0xAA, 0xF6C3 => 0xAB,   0xA9 => 0xAC,
            0x0112 => 0xAD, 0x010D => 0xAE,   0xE5 => 0xAF, 0x0145 => 0xB0,
            0x013A => 0xB1,   0xE0 => 0xB2, 0x0162 => 0xB3, 0x0106 => 0xB4,
            0xE3 => 0xB5, 0x0116 => 0xB6, 0x0161 => 0xB7, 0x015F => 0xB8,
            0xED => 0xB9, 0x25CA => 0xBA, 0x0158 => 0xBB, 0x0122 => 0xBC,
            0xFB => 0xBD,   0xE2 => 0xBE, 0x0100 => 0xBF, 0x0159 => 0xC0,
            0xE7 => 0xC1, 0x017B => 0xC2,   0xDE => 0xC3, 0x014C => 0xC4,
            0x0154 => 0xC5, 0x015A => 0xC6, 0x010F => 0xC7, 0x016A => 0xC8,
            0x016F => 0xC9,   0xB3 => 0xCA,   0xD2 => 0xCB,   0xC0 => 0xCC,
            0x0102 => 0xCD,   0xD7 => 0xCE,   0xFA => 0xCF, 0x0164 => 0xD0,
            0x2202 => 0xD1,   0xFF => 0xD2, 0x0143 => 0xD3,   0xEE => 0xD4,
            0xCA => 0xD5,   0xE4 => 0xD6,   0xEB => 0xD7, 0x0107 => 0xD8,
            0x0144 => 0xD9, 0x016B => 0xDA, 0x0147 => 0xDB,   0xCD => 0xDC,
            0xB1 => 0xDD,   0xA6 => 0xDE,   0xAE => 0xDF, 0x011E => 0xE0,
            0x0130 => 0xE1, 0x2211 => 0xE2,   0xC8 => 0xE3, 0x0155 => 0xE4,
            0x014D => 0xE5, 0x0179 => 0xE6, 0x017D => 0xE7, 0x2265 => 0xE8,
            0xD0 => 0xE9,   0xC7 => 0xEA, 0x013C => 0xEB, 0x0165 => 0xEC,
            0x0119 => 0xED, 0x0172 => 0xEE,   0xC1 => 0xEF,   0xC4 => 0xF0,
            0xE8 => 0xF1, 0x017A => 0xF2, 0x012F => 0xF3,   0xD3 => 0xF4,
            0xF3 => 0xF5, 0x0101 => 0xF6, 0x015B => 0xF7,   0xEF => 0xF8,
            0xD4 => 0xF9,   0xD9 => 0xFA, 0x2206 => 0xFB,   0xFE => 0xFC,
            0xB2 => 0xFD,   0xD6 => 0xFE,   0xB5 => 0xFF,   0xEC => 0x0100,
            0x0151 => 0x0101, 0x0118 => 0x0102, 0x0111 => 0x0103,   0xBE => 0x0104,
            0x015E => 0x0105, 0x013E => 0x0106, 0x0136 => 0x0107, 0x0139 => 0x0108,
            0x2122 => 0x0109, 0x0117 => 0x010A,   0xCC => 0x010B, 0x012A => 0x010C,
            0x013D => 0x010D,   0xBD => 0x010E, 0x2264 => 0x010F,   0xF4 => 0x0110,
            0xF1 => 0x0111, 0x0170 => 0x0112,   0xC9 => 0x0113, 0x0113 => 0x0114,
            0x011F => 0x0115,   0xBC => 0x0116, 0x0160 => 0x0117, 0x0218 => 0x0118,
            0x0150 => 0x0119,   0xB0 => 0x011A,   0xF2 => 0x011B, 0x010C => 0x011C,
            0xF9 => 0x011D, 0x221A => 0x011E, 0x010E => 0x011F, 0x0157 => 0x0120,
            0xD1 => 0x0121,   0xF5 => 0x0122, 0x0156 => 0x0123, 0x013B => 0x0124,
            0xC3 => 0x0125, 0x0104 => 0x0126,   0xC5 => 0x0127,   0xD5 => 0x0128,
            0x017C => 0x0129, 0x011A => 0x012A, 0x012E => 0x012B, 0x0137 => 0x012C,
            0x2212 => 0x012D,   0xCE => 0x012E, 0x0148 => 0x012F, 0x0163 => 0x0130,
            0xAC => 0x0131,   0xF6 => 0x0132,   0xFC => 0x0133, 0x2260 => 0x0134,
            0x0123 => 0x0135,   0xF0 => 0x0136, 0x017E => 0x0137, 0x0146 => 0x0138,
            0xB9 => 0x0139, 0x012B => 0x013A, 0x20AC => 0x013B];
        // require_once 'Zend/Pdf/Cmap.php';
        $this->_cmap = Zend_Pdf_Cmap::cmapWithTypeData(
            Zend_Pdf_Cmap::TYPE_BYTE_ENCODING_STATIC, $cmapData);

        /* Resource dictionary */

        /* The resource dictionary for the standard fonts is sparse because PDF
         * viewers already have all of the metrics data. We only need to provide
         * the font name and encoding method.
         */
        $this->_resource->BaseFont = new Zend_Pdf_Element_Name('Times-BoldItalic');
    }
}
