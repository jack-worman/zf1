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
if (version_compare(PHPUnit_Runner_Version::id(), '4.1', '>=')) {
    include __DIR__.DIRECTORY_SEPARATOR.'Redirect41.php';

    class Zend_Test_PHPUnit_Constraint_Redirect extends Zend_Test_PHPUnit_Constraint_Redirect41
    {
    }
} elseif (version_compare(PHPUnit_Runner_Version::id(), '3.5', '>=')) {
    include __DIR__.DIRECTORY_SEPARATOR.'Redirect37.php';

    class Zend_Test_PHPUnit_Constraint_Redirect extends Zend_Test_PHPUnit_Constraint_Redirect37
    {
    }
} else {
    include __DIR__.DIRECTORY_SEPARATOR.'Redirect34.php';

    class Zend_Test_PHPUnit_Constraint_Redirect extends Zend_Test_PHPUnit_Constraint_Redirect34
    {
    }
}
