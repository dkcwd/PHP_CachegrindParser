<?php

/**
 * This file contains the class CachegrindParser\Data\CallTreeNode.
 *
 * PHP version 5
 *
 * @author Kevin-Simon Kohlmeyer <simon.kohlmeyer@googlemail.com>
 */

namespace CachegrindParser\Data;

/**
 * This class represents a call tree.
 *
 * It stores the tree itself and the information given in the
 * cg file's summary line
 */
class CallTree
{
    private $rootNode;
    private $summary;

    /**
     * Creates a new CallTreeNode object with the given data.
     *
     * @param CachegrindParser\Data\CallTreeNode $rootNode The root node
     * @param array $summary An array containing: 'time'    => integer
     *                                            'cycles'  => integer
     *                                            'mem'     => integer 
     *                                            'peakmem' => integer
     */
    function __construct(CallTreeNode $rootNode, $summary)
    {
        $this->rootNode = $rootNode;
        $this->summary  = $summary;
    }

    /**
     * Gets the root node.
     *
     * @return CachegrindParser\Data\CallTreeNode The calltree's root node.
     */
    public function getRoot()
    {
        return $this->rootNode;
    }

    /**
     * Gets the summary.
     *
     * @return array An array containing: 'time'    => integer
     *                                    'cycles'  => integer
     *                                    'mem'     => integer 
     *                                    'peakmem' => integer
     */
    public function getSummary()
    {
        return $this->summary;
    }
}