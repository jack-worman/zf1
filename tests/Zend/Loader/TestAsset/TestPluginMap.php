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

namespace ZendTest\Loader\TestAsset;

/**
 * @group      Loader
 */
#[\AllowDynamicProperties]
class ZendTest_Loader_TestAsset_TestPluginMap implements \IteratorAggregate
{
    /**
     * Plugin map.
     *
     * @var array
     */
    public $map = [
        'map' => __CLASS__,
        'test' => 'Zend_Loader_PluginClassLoaderTest',
        'loader' => 'Zend_Loader_PluginClassLoader',
    ];

    /**
     * Return iterator.
     *
     * @return \Traversable
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->map);
    }
}
