<?php

namespace Mailery\Cycle\CompositeMapper;

use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\Command\ContextCarrierInterface;
use Cycle\ORM\Command\CommandInterface;

class ChainedItems
{
    /**
     * @var ItemInterface[]
     */
    private $items;

    /**
     * @param ItemInterface[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param mixed $entity
     * @param Node $node
     * @param State $state
     * @param ContextCarrierInterface $cmd
     * @return ContextCarrierInterface
     */
    public function queueCreate($entity, Node $node, State $state, ContextCarrierInterface $cmd): ContextCarrierInterface
    {
        foreach ($this->items as $item) {
            $cmd = $item->queueCreate($entity, $node, $state, $cmd);
        }
        return $cmd;
    }

    /**
     * @param mixed $entity
     * @param Node $node
     * @param State $state
     * @param ContextCarrierInterface $cmd
     * @return ContextCarrierInterface
     */
    public function queueUpdate($entity, Node $node, State $state, ContextCarrierInterface $cmd): ContextCarrierInterface
    {
        foreach ($this->items as $item) {
            $cmd = $item->queueUpdate($entity, $node, $state, $cmd);
        }
        return $cmd;
    }

    /**
     * @param mixed $entity
     * @param Node $node
     * @param State $state
     * @return CommandInterface
     */
    public function queueDelete($entity, Node $node, State $state): CommandInterface
    {
        foreach ($this->items as $item) {
            $cmd = $item->queueDelete($entity, $node, $state, $cmd);
        }
        return $cmd;
    }
}