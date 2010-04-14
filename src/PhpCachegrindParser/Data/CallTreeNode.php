<?php

/**
 * This file contains the class PhpCachegrindParser\Data\CallTreeNode.
 *
 * PHP version 5
 *
 * @author Kevin-Simon Kohlmeyer <simon.kohlmeyer@googlemail.com>
 */

namespace PhpCachegrindParser\Data;

/**
 * This class represents a node in the call tree.
 *
 * It contains the name of the function/method, the file it was defined in,
 * it's costs and a CallTreeNode object for each function it called.
 */
class CallTreeNode
{

    private $fl;
    private $fn;

    private $costs;
    private $inclusiveCostsCache;

    private $children = array();

    /**
     * Creates a new CallTreeNode object with the given values
     *
     * @param string $filename The filename.
     * @param string $funcname The function name.
     * @param array  $costs Array with: 'time'    => integer
     *                                  'mem'     => integer
     *                                  'cycles'  => integer
     *                                  'peakmem' => integer
     */
    function __construct($filename, $funcname, $costs)
    {
        $this->fl = $filename;
        $this->fn = $funcname;
        $this->costs = $costs;
    }

    /**
     * Convenience function to get a CallTreeNode from an RawEntry.
     */
    public static function fromRawEntry($entry)
    {
        return new CallTreeNode($entry->getFilename(),
                                $entry->getFuncname(),
                                $entry->getCosts());
    }

    /**
     * Adds a subcall to this node.
     *
     * @param PhpCachegrind\Data\CallTreeNode $child The child node to add.
     */
    public function addChild(CallTreeNode $child)
    {
        $this->children[] = $child;
    }

    /**
     * Returns the costs of this entry.
     *
     * @return array  $costs Array with: 'time'    => integer
     *                                   'mem'     => integer
     *                                   'cycles'  => integer
     *                                   'peakmem' => integer
     */
    public function getCosts() {
        return $this->costs;
    }

    /**
     * Returns the costs of this call plus the inclusive
     * costs of all functions called by this one.
     *
     * @return integer The functions inclusive costs.
     */
    public function getInclusiveCosts()
    {
        if (!$this->inclusiveCostsCache) {
            $inclCosts = $this->costs;

            foreach ($this->children as $child) {
                $childInclCosts = $child->getInclusiveCosts();
                $inclCosts['time']   += $childInclCosts['time'];
                $inclCosts['cycles'] += $childInclCosts['cycles'];
                $inclCosts['mem']         = max($inclCosts['mem'],
                                                $childInclCosts['mem']);
                $inclCosts['peakmem'] = max($inclCosts['peakmem'],
                                                $childInclCosts['peakmem']);
            }
            $this->inclusiveCostsCache = $inclCosts;
        }
        return $this->inclusiveCostsCache;
    }

    /**
     * Returns the name of the file this function is located in.
     *
     * @return string File name.
     */
    public function getFilename() {
        return $this->fl;
    }

    /**
     * Returns the name of this function.
     *
     * @return string Function name.
     */
    public function getFuncname() {
        return $this->fn;
    }

    /**
     * Returns the children of this node.
     *
     * @return array Array with PhpCachegrindParser\Data\CallTreeNode
     *               objects that are called by this function.
     */
    public function getChildren() {
        return $this->children;
    }
}
