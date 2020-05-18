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

class ChainItemList
{
    /**
     * @var ChainItemInterface[]
     */
    private $items;

    /**
     * @param ChainItemInterface[] $items
     */
    public function __construct(array $items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    /**
     * @param ChainItemInterface $item
     * @return self
     */
    public function addItem(ChainItemInterface $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param ContextCarrierInterface $cmd
     * @param Node $node
     * @param State $state
     * @return ContextCarrierInterface
     */
    public function queueCreate(ContextCarrierInterface $cmd, Node $node, State $state): ContextCarrierInterface
    {
        foreach ($this->items as $item) {
            $cmd = $item->queueCreate($cmd, $node, $state);
        }

        return $cmd;
    }

    /**
     * @param ContextCarrierInterface $cmd
     * @param Node $node
     * @param State $state
     * @return ContextCarrierInterface
     */
    public function queueUpdate(ContextCarrierInterface $cmd, Node $node, State $state): ContextCarrierInterface
    {
        foreach ($this->items as $item) {
            $cmd = $item->queueUpdate($cmd, $node, $state);
        }

        return $cmd;
    }

    /**
     * @param CommandInterface $cmd
     * @param Node $node
     * @param State $state
     * @return CommandInterface
     */
    public function queueDelete(CommandInterface $cmd, Node $node, State $state): CommandInterface
    {
        foreach ($this->items as $item) {
            $cmd = $item->queueDelete($cmd, $node, $state);
        }

        return $cmd;
    }
}
