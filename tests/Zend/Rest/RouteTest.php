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

/* Zend_Rest_Route */
// require_once 'Zend/Rest/Route.php';

/* Zend_Controller_Front */
// require_once 'Zend/Controller/Front.php';

/* Zend_Controller_Request_HttpTestCase */
// require_once 'Zend/Controller/Request/HttpTestCase.php';

// Call Zend_Rest_RouteTest::main() if this source file is executed directly.
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Rest_RouteTest::main');
}

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 * @group      Zend_Rest
 */
#[AllowDynamicProperties]
class Zend_Rest_RouteTest extends PHPUnit_Framework_TestCase
{
    protected $_front;
    protected $_request;
    protected $_dispatcher;

    /**
     * Runs the test methods of this class.
     *
     * @static
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend_Rest_RouteTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function setUp()
    {
        $this->_front = Zend_Controller_Front::getInstance();
        $this->_front->resetInstance();
        $this->_front->setParam('noErrorHandler', true)
        ->setParam('noViewRenderer', true);

        $this->_dispatcher = $this->_front->getDispatcher();

        $this->_dispatcher->setControllerDirectory([
            'default' => __DIR__.DIRECTORY_SEPARATOR.
                '..'.DIRECTORY_SEPARATOR.
                'Controller'.DIRECTORY_SEPARATOR.
                '_files',
            'mod' => __DIR__.DIRECTORY_SEPARATOR.
                '..'.DIRECTORY_SEPARATOR.
                'Controller'.DIRECTORY_SEPARATOR.
                '_files'.DIRECTORY_SEPARATOR.
                'Admin',
        ]);
    }

    public function testGetVersion()
    {
        $route = new Zend_Rest_Route($this->_front);
        $this->assertEquals(2, $route->getVersion());
    }

    public function testGetInstanceFromINIConfig()
    {
        // require_once('Zend/Config/Ini.php');
        $config = new Zend_Config_Ini(__DIR__.'/../Controller/_files/routes.ini', 'testing');
        // require_once('Zend/Controller/Router/Rewrite.php');
        $router = new Zend_Controller_Router_Rewrite();
        $router->addConfig($config, 'routes');
        $route = $router->getRoute('rest');
        $this->assertTrue($route instanceof Zend_Rest_Route);
        $this->assertEquals('object', $route->getDefault('controller'));

        $request = $this->_buildRequest('GET', '/mod/project');
        $values = $this->_invokeRouteMatch($request, [], $route);
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('project', $values['controller']);
        $this->assertEquals('index', $values['action']);

        $request = $this->_buildRequest('POST', '/mod/user');
        $values = $this->_invokeRouteMatch($request, [], $route);
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('post', $values['action']);

        $request = $this->_buildRequest('GET', '/other');
        $values = $this->_invokeRouteMatch($request, [], $route);
        $this->assertFalse($values);
    }

    public function testRESTfulAppDefaults()
    {
        $request = $this->_buildRequest('GET', '/');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('index', $values['controller']);
        $this->assertEquals('index', $values['action']);
    }

    /*
     * @group ZF-7437
     */
    public function testRESTfulAppGETUserDefaults()
    {
        $request = $this->_buildRequest('GET', '/user');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('index', $values['action']);
    }

    public function testRESTfulAppGETUserIndex()
    {
        $request = $this->_buildRequest('GET', '/user/index');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('index', $values['action']);
    }

    public function testRESTfulAppGETUserIndexWithParams()
    {
        $request = $this->_buildRequest('GET', '/user/index/changedSince/123456789/status/active');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('index', $values['action']);
        $this->assertEquals(123456789, $values['changedSince']);
        $this->assertEquals('active', $values['status']);
    }

    public function testRESTfulAppGETUserIndexWithQueryParams()
    {
        $request = $this->_buildRequest('GET', '/user/?changedSince=123456789&status=active');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('index', $values['action']);
        $this->assertEquals(123456789, $values['changedSince']);
        $this->assertEquals('active', $values['status']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulAppGETUserIndexWithParamUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('GET', '/user/index/the%2Bemail%40address/email%2Btest%40example.com');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('index', $values['action']);
        $this->assertEquals('email+test@example.com', $values['the+email@address']);
    }

    public function testRESTfulAppGETProjectByIdentifier()
    {
        $request = $this->_buildRequest('GET', '/project/zendframework');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('project', $values['controller']);
        $this->assertEquals('get', $values['action']);
        $this->assertEquals('zendframework', $values['id']);
    }

    public function testRESTfulAppGETProjectByIdQueryParam()
    {
        $request = $this->_buildRequest('GET', '/project/?id=zendframework');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('project', $values['controller']);
        $this->assertEquals('get', $values['action']);
        $this->assertEquals('zendframework', $values['id']);
    }

    public function testRESTfulAppGETProjectByIdentifierUrlencoded()
    {
        $request = $this->_buildRequest('GET', '/project/zend+framework');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('project', $values['controller']);
        $this->assertEquals('get', $values['action']);
        $this->assertEquals('zend framework', $values['id']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulAppGETProjectByIdentifierUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('GET', '/project/email%2Btest%40example.com');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('project', $values['controller']);
        $this->assertEquals('get', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulAppHEADProjectByIdentifier()
    {
        $request = $this->_buildRequest('HEAD', '/project/lcrouch');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('project', $values['controller']);
        $this->assertEquals('head', $values['action']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulAppHEADProjectByIdentifierUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('HEAD', '/project/email%2Btest%40example.com');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('project', $values['controller']);
        $this->assertEquals('head', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulAppGETProjectEdit()
    {
        $request = $this->_buildRequest('GET', '/project/zendframework/edit');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('project', $values['controller']);
        $this->assertEquals('edit', $values['action']);
        $this->assertEquals('zendframework', $values['id']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulAppGETProjectEditUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('GET', '/project/email%2Btest%40example.com/edit');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('default', $values['module']);
        $this->assertEquals('project', $values['controller']);
        $this->assertEquals('edit', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulAppPUTUserByIdentifier()
    {
        $request = $this->_buildRequest('PUT', '/mod/user/lcrouch');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('put', $values['action']);
        $this->assertEquals('lcrouch', $values['id']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulAppPUTUserByIdentifierUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('PUT', '/mod/user/email%2Btest%40example.com');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('put', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulAppPOSTUser()
    {
        $request = $this->_buildRequest('POST', '/mod/user');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('post', $values['action']);
    }

    public function testRESTfulAppDELETEUserByIdentifier()
    {
        $request = $this->_buildRequest('DELETE', '/mod/user/lcrouch');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('delete', $values['action']);
        $this->assertEquals('lcrouch', $values['id']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulAppDELETEUserByIdentifierUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('DELETE', '/mod/user/email%2Btest%40example.com');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('delete', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulAppPOSTUserWithIdentifierDoesPUT()
    {
        $request = $this->_buildRequest('POST', '/mod/user/lcrouch');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('put', $values['action']);
        $this->assertEquals('lcrouch', $values['id']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulAppPOSTUserWithIdentifierUrlencodedWithPlusSymbolDoesPUT()
    {
        $request = $this->_buildRequest('POST', '/mod/user/email%2Btest%40example.com');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('put', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulAppOverloadPOSTWithMethodParamPUT()
    {
        $request = $this->_buildRequest('POST', '/mod/user');
        $request->setParam('_method', 'PUT');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('put', $values['action']);
    }

    public function testRESTfulAppOverloadPOSTWithHttpHeaderDELETE()
    {
        $request = $this->_buildRequest('POST', '/mod/user/lcrouch');
        $request->setHeader('X-HTTP-Method-Override', 'DELETE');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('delete', $values['action']);
        $this->assertEquals('lcrouch', $values['id']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulAppOverloadPOSTWithHttpHeaderDELETEUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('POST', '/mod/user/email%2Btest%40example.com');
        $request->setHeader('X-HTTP-Method-Override', 'DELETE');
        $values = $this->_invokeRouteMatch($request);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('delete', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulAppRouteChaining()
    {
        $request = $this->_buildRequest('GET', '/api/user/lcrouch');
        $this->_front->setRequest($request);

        $router = $this->_front->getRouter();
        $router->removeDefaultRoutes();

        $nonRESTRoute = new Zend_Controller_Router_Route('api');
        $RESTRoute = new Zend_Rest_Route($this->_front);
        $router->addRoute('api', $nonRESTRoute->chain($RESTRoute));

        $routedRequest = $router->route($request);

        $this->assertEquals('default', $routedRequest->getParam('module'));
        $this->assertEquals('user', $routedRequest->getParam('controller'));
        $this->assertEquals('get', $routedRequest->getParam('action'));
        $this->assertEquals('lcrouch', $routedRequest->getParam('id'));
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulAppRouteChainingUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('GET', '/api/user/email%2Btest%40example.com');
        $this->_front->setRequest($request);

        $router = $this->_front->getRouter();
        $router->removeDefaultRoutes();

        $nonRESTRoute = new Zend_Controller_Router_Route('api');
        $RESTRoute = new Zend_Rest_Route($this->_front);
        $router->addRoute('api', $nonRESTRoute->chain($RESTRoute));

        $routedRequest = $router->route($request);

        $this->assertEquals('default', $routedRequest->getParam('module'));
        $this->assertEquals('user', $routedRequest->getParam('controller'));
        $this->assertEquals('get', $routedRequest->getParam('action'));
        $this->assertEquals('email+test@example.com', $routedRequest->getParam('id'));
    }

    public function testRESTfulModuleGETUserIndex()
    {
        $request = $this->_buildRequest('GET', '/mod/user/index');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('index', $values['action']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulModuleGETUserIndexWithParamUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('GET', '/mod/user/index/the%2Bemail%40address/email%2Btest%40example.com');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('index', $values['action']);
        $this->assertEquals('email+test@example.com', $values['the+email@address']);
    }

    public function testRESTfulModuleGETUser()
    {
        $request = $this->_buildRequest('GET', '/mod/user/1234');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('get', $values['action']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulModuleGETUserUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('GET', '/mod/user/email%2Btest%40example.com');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('get', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulModulePOSTUser()
    {
        $request = $this->_buildRequest('POST', '/mod/user');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('post', $values['action']);
    }

    public function testRESTfulModulePOSTUserInNonRESTModuleReturnsFalse()
    {
        $request = $this->_buildRequest('POST', '/default/user');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertFalse($values);
    }

    public function testRESTfulModulePUTUserByIdentifier()
    {
        $request = $this->_buildRequest('PUT', '/mod/user/lcrouch');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('put', $values['action']);
        $this->assertEquals('lcrouch', $values['id']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulModulePUTUserByIdentifierUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('PUT', '/mod/user/email%2Btest%40example.com');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('put', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulModuleDELETEUserByIdentifier()
    {
        $request = $this->_buildRequest('DELETE', '/mod/user/lcrouch');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('delete', $values['action']);
        $this->assertEquals('lcrouch', $values['id']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulModuleDELETEUserByIdentifierUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('DELETE', '/mod/user/email%2Btest%40example.com');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('delete', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulControllerGETUserIndex()
    {
        $request = $this->_buildRequest('GET', '/mod/user/index');
        $config = ['mod' => ['user']];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('index', $values['action']);
    }

    public function testRESTfulControllerGETDefaultControllerReturnsFalse()
    {
        $request = $this->_buildRequest('GET', '/mod/index/index');
        $config = ['mod' => ['user']];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertFalse($values);
    }

    public function testRESTfulControllerGETOtherIndexReturnsFalse()
    {
        $request = $this->_buildRequest('GET', '/mod/project/index');
        $config = ['mod' => ['user']];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertFalse($values);
    }

    public function testRESTfulControllerGETUser()
    {
        $request = $this->_buildRequest('GET', '/mod/user/1234');
        $config = ['mod' => ['user']];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('get', $values['action']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulControllerGETUserUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('GET', '/mod/user/email%2Btest%40example.com');
        $config = ['mod' => ['user']];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('get', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulControllerPOSTUser()
    {
        $request = $this->_buildRequest('POST', '/mod/user');
        $config = ['mod' => ['user']];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('post', $values['action']);
    }

    public function testRESTfulControllerPOSTUserInNonRESTModuleReturnsFalse()
    {
        $request = $this->_buildRequest('POST', '/default/user');
        $config = ['mod' => ['user']];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertFalse($values);
    }

    public function testPostToNonRESTfulDefaultControllerModuleHasAnotherRESTfulControllerDefaultControllerInURLReturnsFalse()
    {
        $request = $this->_buildRequest('POST', '/mod/index');
        $config = ['mod' => ['user']];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertFalse($values);
    }

    public function testPostToNonRESTfulDefaultControllerModuleHasAnotherRESTfulControllerNoDefaultControllerInURLReturnsFalse()
    {
        $request = $this->_buildRequest('POST', '/mod');
        $config = ['mod' => ['user']];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertFalse($values);
    }

    public function testRESTfulControllerPUTUserByIdentifier()
    {
        $request = $this->_buildRequest('PUT', '/mod/user/lcrouch');
        $config = ['mod' => ['user']];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('put', $values['action']);
        $this->assertEquals('lcrouch', $values['id']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulControllerPUTUserByIdentifierUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('PUT', '/mod/user/email%2Btest%40example.com');
        $config = ['mod' => ['user']];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('put', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testRESTfulControllerDELETEUserByIdentifier()
    {
        $request = $this->_buildRequest('DELETE', '/mod/user/lcrouch');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('delete', $values['action']);
        $this->assertEquals('lcrouch', $values['id']);
    }

    /**
     * @group ZF-10964
     */
    public function testRESTfulControllerDELETEUserByIdentifierUrlencodedWithPlusSymbol()
    {
        $request = $this->_buildRequest('DELETE', '/mod/user/email%2Btest%40example.com');
        $config = ['mod'];
        $values = $this->_invokeRouteMatch($request, $config);

        $this->assertTrue(is_array($values));
        $this->assertTrue(isset($values['module']));
        $this->assertEquals('mod', $values['module']);
        $this->assertEquals('user', $values['controller']);
        $this->assertEquals('delete', $values['action']);
        $this->assertEquals('email+test@example.com', $values['id']);
    }

    public function testAssemblePlainIgnoresAction()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['module' => 'mod', 'controller' => 'user', 'action' => 'get'];
        $url = $route->assemble($params);
        $this->assertEquals('mod/user', $url);
    }

    public function testAssembleIdAfterController()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['module' => 'mod', 'controller' => 'user', 'id' => 'lcrouch'];
        $url = $route->assemble($params);
        $this->assertEquals('mod/user/lcrouch', $url);
    }

    public function testAssembleIndexAfterControllerWithParams()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['module' => 'mod', 'controller' => 'user', 'index' => true, 'foo' => 'bar'];
        $url = $route->assemble($params);
        $this->assertEquals('mod/user/index/foo/bar', $url);
    }

    public function testAssembleEncodeParamValues()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['module' => 'mod', 'controller' => 'user', 'index' => true, 'foo' => 'bar is n!ice'];
        $url = $route->assemble($params);
        $this->assertEquals('mod/user/index/foo/bar+is+n%21ice', $url);
    }

    public function testAssembleDoesNOTEncodeParamValues()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['module' => 'mod', 'controller' => 'user', 'index' => true, 'foo' => 'bar is n!ice'];
        $url = $route->assemble($params, false, false);
        $this->assertEquals('mod/user/index/foo/bar is n!ice', $url);
    }

    /**
     * @group ZF-9823
     */
    public function testAssembleEditWithModuleAppendsActionAfterId()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['module' => 'mod', 'controller' => 'users', 'action' => 'edit', 'id' => 1];
        $url = $route->assemble($params);
        $this->assertEquals('mod/users/1/edit', $url);
    }

    /**
     * @group ZF-9823
     */
    public function testAssembleEditWithoutModuleAppendsActionAfterId()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['controller' => 'users', 'action' => 'edit', 'id' => 1];
        $url = $route->assemble($params);
        $this->assertEquals('users/1/edit', $url);
    }

    /**
     * @group ZF-9823
     */
    public function testAssembleNewWithModuleAppendsAction()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['module' => 'mod', 'controller' => 'users', 'action' => 'new'];
        $url = $route->assemble($params);
        $this->assertEquals('mod/users/new', $url);
    }

    /**
     * @group ZF-9823
     */
    public function testAssembleNewWithoutModuleAppendsAction()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['controller' => 'users', 'action' => 'new'];
        $url = $route->assemble($params);
        $this->assertEquals('users/new', $url);
    }

    /**
     * @group ZF-9823
     */
    public function testAssembleRandomActionWithModuleRemovesAction()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['module' => 'mod', 'controller' => 'users', 'action' => 'newbar'];
        $url = $route->assemble($params);
        $this->assertNotEquals('mod/users/newbar', $url);
    }

    /**
     * @group ZF-9823
     */
    public function testAssembleRandomActionWithoutModuleRemovesAction()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['controller' => 'users', 'action' => 'newbar'];
        $url = $route->assemble($params);
        $this->assertNotEquals('users/newbar', $url);
    }

    /**
     * @group ZF-9823
     */
    public function testAssembleWithModuleHonorsIndexParameterWithResourceIdAndExtraParameters()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['module' => 'mod', 'controller' => 'users', 'id' => 1, 'extra' => 'parameter', 'another' => 'parameter', 'index' => true];
        $url = $route->assemble($params, false, false);
        $this->assertEquals('mod/users/index/1/extra/parameter/another/parameter', $url);
    }

    /**
     * @group ZF-9823
     */
    public function testAssembleWithoutModuleHonorsIndexParameterWithResourceIdAndExtraParameters()
    {
        $route = new Zend_Rest_Route($this->_front, [], []);
        $params = ['controller' => 'users', 'id' => 1, 'extra' => 'parameter', 'another' => 'parameter', 'index' => true];
        $url = $route->assemble($params, false, false);
        $this->assertEquals('users/index/1/extra/parameter/another/parameter', $url);
    }

    /**
     * @group ZF-9115
     */
    public function testRequestGetUserParams()
    {
        $uri = Zend_Uri::factory('http://localhost.com/user/index?a=1&b=2');
        $request = new Zend_Controller_Request_Http($uri);
        $request->setParam('test', 5);
        $config = ['mod' => ['user']];
        $this->_invokeRouteMatch($request, $config);
        $this->assertEquals(['test' => 5], $request->getUserParams());
        $this->assertEquals(['test' => 5, 'a' => 1, 'b' => 2], $request->getParams());
    }

    private function _buildRequest($method, $uri)
    {
        $request = new Zend_Controller_Request_HttpTestCase();
        $request->setMethod($method)->setRequestUri($uri);

        return $request;
    }

    private function _invokeRouteMatch($request, $config = [], $route = null)
    {
        $this->_front->setRequest($request);
        if (null == $route) {
            $route = new Zend_Rest_Route($this->_front, [], $config);
        }
        $values = $route->match($request);

        return $values;
    }
}

// Call Zend_Rest_RouteTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Zend_Rest_RouteTest::main') {
    Zend_Rest_RouteTest::main();
}
