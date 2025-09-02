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
    include __DIR__.DIRECTORY_SEPARATOR.'DomQuery41.php';

    class Zend_Test_PHPUnit_Constraint_DomQuery extends Zend_Test_PHPUnit_Constraint_DomQuery41
    {
    }
} elseif (version_compare(PHPUnit_Runner_Version::id(), '3.5', '>=')) {
    include __DIR__.DIRECTORY_SEPARATOR.'DomQuery37.php';

    class Zend_Test_PHPUnit_Constraint_DomQuery extends Zend_Test_PHPUnit_Constraint_DomQuery37
    {
    }
} else {
    include __DIR__.DIRECTORY_SEPARATOR.'DomQuery34.php';

    class Zend_Test_PHPUnit_Constraint_DomQuery extends Zend_Test_PHPUnit_Constraint_DomQuery34
    {
    }
}
