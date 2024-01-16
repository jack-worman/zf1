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
 * @see Zend_Tag_Taggable
 */
// require_once 'Zend/Tag/Taggable.php';

/**
 * @category   Zend
 *
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Tag_ItemList implements Countable, SeekableIterator, ArrayAccess
{
    /**
     * Items in this list.
     *
     * @var array
     */
    protected $_items = [];

    /**
     * Count all items.
     *
     * @return int
     */
    #[ReturnTypeWillChange]
    public function count()
    {
        return count($this->_items);
    }

    /**
     * Spread values in the items relative to their weight.
     *
     * @return void
     *
     * @throws Zend_Tag_Exception When value list is empty
     */
    public function spreadWeightValues(array $values)
    {
        // Don't allow an empty value list
        if (0 === count($values)) {
            // require_once 'Zend/Tag/Exception.php';
            throw new Zend_Tag_Exception('Value list may not be empty');
        }

        // Re-index the array
        $values = array_values($values);

        // If just a single value is supplied simply assign it to to all tags
        if (1 === count($values)) {
            foreach ($this->_items as $item) {
                $item->setParam('weightValue', $values[0]);
            }
        } else {
            // Calculate min- and max-weight
            $minWeight = null;
            $maxWeight = null;

            foreach ($this->_items as $item) {
                if (null === $minWeight && null === $maxWeight) {
                    $minWeight = $item->getWeight();
                    $maxWeight = $item->getWeight();
                } else {
                    $minWeight = min($minWeight, $item->getWeight());
                    $maxWeight = max($maxWeight, $item->getWeight());
                }
            }

            // Calculate the thresholds
            $steps = count($values);
            $delta = ($maxWeight - $minWeight) / ($steps - 1);
            $thresholds = [];

            for ($i = 0; $i < $steps; ++$i) {
                $thresholds[$i] = floor(100 * log(($minWeight + $i * $delta) + 2));
            }

            // Then assign the weight values
            foreach ($this->_items as $item) {
                $threshold = floor(100 * log($item->getWeight() + 2));

                for ($i = 0; $i < $steps; ++$i) {
                    if ($threshold <= $thresholds[$i]) {
                        $item->setParam('weightValue', $values[$i]);
                        break;
                    }
                }
            }
        }
    }

    /**
     * Seek to an absolute positio.
     *
     * @param int $index
     *
     * @return void
     *
     * @throws OutOfBoundsException When the seek position is invalid
     */
    #[ReturnTypeWillChange]
    public function seek($index)
    {
        $this->rewind();
        $position = 0;

        while ($position < $index && $this->valid()) {
            $this->next();
            ++$position;
        }

        if (!$this->valid()) {
            throw new OutOfBoundsException('Invalid seek position');
        }
    }

    /**
     * Return the current element.
     */
    #[ReturnTypeWillChange]
    public function current()
    {
        return current($this->_items);
    }

    /**
     * Move forward to next element.
     */
    #[ReturnTypeWillChange]
    public function next()
    {
        return next($this->_items);
    }

    /**
     * Return the key of the current element.
     *
     * @return int|string|null
     */
    #[ReturnTypeWillChange]
    public function key()
    {
        return key($this->_items);
    }

    /**
     * Check if there is a current element after calls to rewind() or next().
     *
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function valid()
    {
        return false !== $this->current();
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @return void
     */
    #[ReturnTypeWillChange]
    public function rewind()
    {
        reset($this->_items);
    }

    /**
     * Check if an offset exists.
     *
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_items);
    }

    /**
     * Get the value of an offset.
     *
     * @return Zend_Tag_Taggable
     */
    #[ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->_items[$offset];
    }

    /**
     * Append a new item.
     *
     * @param Zend_Tag_Taggable $item
     *
     * @return void
     *
     * @throws OutOfBoundsException When item does not implement Zend_Tag_Taggable
     */
    #[ReturnTypeWillChange]
    public function offsetSet($offset, $item)
    {
        // We need to make that check here, as the method signature must be
        // compatible with ArrayAccess::offsetSet()
        if (!($item instanceof Zend_Tag_Taggable)) {
            // require_once 'Zend/Tag/Exception.php';
            throw new Zend_Tag_Exception('Item must implement Zend_Tag_Taggable');
        }

        if (null === $offset) {
            $this->_items[] = $item;
        } else {
            $this->_items[$offset] = $item;
        }
    }

    /**
     * Unset an item.
     *
     * @return void
     */
    #[ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->_items[$offset]);
    }
}
