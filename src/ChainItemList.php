<?php

declare(strict_types=1);

/**
 * Mapper for Cycle ORM
 * @link      https://github.com/maileryio/cycle-mapper
 * @package   Mailery\Cycle\Mapper
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Cycle\Mapper;

use Cycle\ORM\Command\CommandInterface;
use Cycle\ORM\Command\ContextCarrierInterface;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Doctrine\Common\Collections\ArrayCollection;

class ChainItemList extends ArrayCollection
{
    /**
     * @param object $entity
     * @param Node $node
     * @param State $state
     * @param ContextCarrierInterface $cmd
     * @return ContextCarrierInterface
     */
    public function queueCreate($entity, Node $node, State $state, ContextCarrierInterface $cmd): ContextCarrierInterface
    {
        foreach ($this->getIterator() as $item) {
            $cmd = $item->queueCreate($entity, $node, $state, $cmd);
        }

        return $cmd;
    }

    /**
     * @param object $entity
     * @param Node $node
     * @param State $state
     * @param ContextCarrierInterface $cmd
     * @return ContextCarrierInterface
     */
    public function queueUpdate($entity, Node $node, State $state, ContextCarrierInterface $cmd): ContextCarrierInterface
    {
        foreach ($this->getIterator() as $item) {
            $cmd = $item->queueUpdate($entity, $node, $state, $cmd);
        }

        return $cmd;
    }

    /**
     * @param object $entity
     * @param Node $node
     * @param State $state
     * @param CommandInterface $cmd
     * @return CommandInterface
     */
    public function queueDelete($entity, Node $node, State $state, CommandInterface $cmd): CommandInterface
    {
        foreach ($this->getIterator() as $item) {
            $cmd = $item->queueDelete($entity, $node, $state, $cmd);
        }

        return $cmd;
    }
}
