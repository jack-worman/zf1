<?php

/**
 * Zend Framework
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
 * @package    Zend
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', \Zend_AllTests::main::class);
}

require_once 'Zend/Acl/AclTest.php';
require_once 'Zend/Cache/AllTests.php';
require_once 'Zend/Db/AllTests.php';
require_once 'Zend/Dom/AllTests.php';
require_once 'Zend/ConfigTest.php';
require_once 'Zend/Config/AllTests.php';
require_once 'Zend/Controller/AllTests.php';
require_once 'Zend/Crypt/AllTests.php';
require_once 'Zend/DateTest.php';
require_once 'Zend/Date/AllTests.php';
require_once 'Zend/DebugTest.php';
require_once 'Zend/EventManager/AllTests.php';
require_once 'Zend/ExceptionTest.php';
require_once 'Zend/File/AllTests.php';
require_once 'Zend/FilterTest.php';
require_once 'Zend/Filter/AllTests.php';
require_once 'Zend/Form/AllTests.php';
require_once 'Zend/HealthCall/HealthCallTest.php';
require_once 'Zend/Http/AllTests.php';
require_once 'Zend/JsonTest.php';
require_once 'Zend/Json/AllTests.php';
require_once 'Zend/Layout/AllTests.php';
require_once 'Zend/Ldap/AllTests.php';
require_once 'Zend/LoaderTest.php';
require_once 'Zend/Loader/AllTests.php';
require_once 'Zend/LocaleTest.php';
require_once 'Zend/Locale/AllTests.php';
require_once 'Zend/Log/AllTests.php';
require_once 'Zend/Mail/AllTests.php';
require_once 'Zend/Measure/AllTests.php';
require_once 'Zend/Memory/AllTests.php';
require_once 'Zend/MimeTest.php';
require_once 'Zend/Mime/AllTests.php';
require_once 'Zend/NavigationTest.php';
require_once 'Zend/Navigation/AllTests.php';
require_once 'Zend/Oauth/AllTests.php';
require_once 'Zend/OpenIdTest.php';
require_once 'Zend/OpenId/AllTests.php';
require_once 'Zend/Paginator/AllTests.php';
require_once 'Zend/PdfTest.php';
require_once 'Zend/Pdf/AllTests.php';
require_once 'Zend/Reflection/AllTests.php';
require_once 'Zend/RegistryTest.php';
require_once 'Zend/Queue/AllTests.php';
require_once 'Zend/Search/Lucene/AllTests.php';
require_once 'Zend/Serializer/AllTests.php';
require_once 'Zend/Server/AllTests.php';
require_once 'Zend/Session/AllTests.php';
require_once 'Zend/Soap/AllTests.php';
require_once 'Zend/Stdlib/AllTests.php';
require_once 'Zend/Tag/AllTests.php';
require_once 'Zend/Test/AllTests.php';
require_once 'Zend/Text/AllTests.php';
require_once 'Zend/TimeSyncTest.php';
require_once 'Zend/TranslateTest.php';
require_once 'Zend/Translate/Adapter/AllTests.php';
require_once 'Zend/UriTest.php';
require_once 'Zend/Uri/AllTests.php';
require_once 'Zend/ValidateTest.php';
require_once 'Zend/Validate/AllTests.php';
require_once 'Zend/VersionTest.php';
require_once 'Zend/ViewTest.php';
require_once 'Zend/View/AllTests.php';
if (PHP_OS != 'AIX') {
    require_once 'Zend/Wildfire/AllTests.php';
}
require_once 'Zend/Xml/AllTests.php';
require_once 'Zend/XmlRpc/AllTests.php';

/**
 * @category   Zend
 * @package    Zend
 * @subpackage UnitTests
 * @group      Zend
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
#[AllowDynamicProperties]
class Zend_AllTests
{
    public static function main()
    {
        // Run buffered tests as a separate suite first
        ob_start();
        PHPUnit_TextUI_TestRunner::run(self::suiteBuffered());
        if (ob_get_level()) {
            ob_end_flush();
        }

        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    /**
     * Buffered test suites
     *
     * These tests require no output be sent prior to running as they rely
     * on internal PHP functions.
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suiteBuffered()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend Framework - Zend - Buffered Test Suites');

        // These tests require no output be sent prior to running as they rely
        // on internal PHP functions
        $suite->addTestSuite(\Zend_OpenIdTest::class);
        $suite->addTest(Zend_OpenId_AllTests::suite());
        $suite->addTest(Zend_Session_AllTests::suite());
        $suite->addTest(Zend_Soap_AllTests::suite());

        return $suite;
    }

    /**
     * Regular suite
     *
     * All tests except those that require output buffering.
     *
     * @return PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend Framework - Zend');

        // Running this early to ensure that the test suite hasn't used too
        // much memory by the time it gets to this test.
        $suite->addTest(Zend_Memory_AllTests::suite());

        // Start remaining tests...
        $suite->addTestSuite(\Zend_Acl_AclTest::class);
        $suite->addTest(Zend_Cache_AllTests::suite());
        $suite->addTestSuite(\Zend_ConfigTest::class);
        $suite->addTest(Zend_Config_AllTests::suite());
        $suite->addTest(Zend_Controller_AllTests::suite());
        $suite->addTest(Zend_Crypt_AllTests::suite());
        $suite->addTestSuite(\Zend_DateTest::class);
        $suite->addTest(Zend_Date_AllTests::suite());
        $suite->addTest(Zend_Db_AllTests::suite());
        $suite->addTestSuite(\Zend_DebugTest::class);
        $suite->addTest(Zend_Dom_AllTests::suite());
        $suite->addTest(Zend_EventManager_AllTests::suite());
        $suite->addTestSuite(\Zend_ExceptionTest::class);
        $suite->addTest(Zend_File_AllTests::suite());
        $suite->addTestSuite(\Zend_FilterTest::class);
        $suite->addTest(Zend_Filter_AllTests::suite());
        $suite->addTest(Zend_Form_AllTests::suite());
        $suite->addTestSuite(\Zend_HealthCall_HealthCallTest::class);
        $suite->addTest(Zend_Http_AllTests::suite());
        $suite->addTestSuite(\Zend_JsonTest::class);
        $suite->addTest(Zend_Json_AllTests::suite());
        $suite->addTest(Zend_Layout_AllTests::suite());
        $suite->addTest(Zend_Ldap_AllTests::suite());
        $suite->addTestSuite(\Zend_LoaderTest::class);
        $suite->addTest(Zend_Loader_AllTests::suite());
        $suite->addTestSuite(\Zend_LocaleTest::class);
        $suite->addTest(Zend_Locale_AllTests::suite());
        $suite->addTest(Zend_Log_AllTests::suite());
        $suite->addTest(Zend_Mail_AllTests::suite());
        $suite->addTest(Zend_Measure_AllTests::suite());
        $suite->addTestSuite(\Zend_MimeTest::class);
        $suite->addTest(Zend_Mime_AllTests::suite());
        $suite->addTestSuite(\Zend_NavigationTest::class);
        $suite->addTest(Zend_Navigation_AllTests::suite());
        $suite->addTest(Zend_Oauth_AllTests::suite());
        $suite->addTest(Zend_Paginator_AllTests::suite());
        $suite->addTestSuite(\Zend_PdfTest::class);
        $suite->addTest(Zend_Pdf_AllTests::suite());
        $suite->addTestSuite(\Zend_RegistryTest::class);
        $suite->addTest(Zend_Reflection_AllTests::suite());
        $suite->addTest(Zend_Queue_AllTests::suite());
        $suite->addTest(Zend_Search_Lucene_AllTests::suite());
        $suite->addTest(Zend_Serializer_AllTests::suite());
        $suite->addTest(Zend_Server_AllTests::suite());
        $suite->addTest(Zend_Stdlib_AllTests::suite());
        $suite->addTest(Zend_Tag_AllTests::suite());
        $suite->addTest(Zend_Test_AllTests::suite());
        $suite->addTest(Zend_Text_AllTests::suite());
        $suite->addTestSuite(\Zend_TimeSyncTest::class);
        $suite->addTestSuite(\Zend_TranslateTest::class);
        $suite->addTest(Zend_Translate_Adapter_AllTests::suite());
        $suite->addTestSuite(\Zend_UriTest::class);
        $suite->addTest(Zend_Uri_AllTests::suite());
        $suite->addTestSuite(\Zend_ValidateTest::class);
        $suite->addTest(Zend_Validate_AllTests::suite());
        $suite->addTestSuite(\Zend_ViewTest::class);
        $suite->addTest(Zend_View_AllTests::suite());
        $suite->addTestSuite(\Zend_VersionTest::class);
        if (PHP_OS != 'AIX') {
            $suite->addTest(Zend_Wildfire_AllTests::suite());
        }
        $suite->addTest(Zend_Xml_AllTests::suite());
        $suite->addTest(Zend_XmlRpc_AllTests::suite());

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_AllTests::main') {
    Zend_AllTests::main();
}
