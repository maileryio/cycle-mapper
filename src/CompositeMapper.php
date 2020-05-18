<?php

namespace Mailery\Cycle\CompositeMapper;

use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\Command\ContextCarrierInterface;
use Cycle\ORM\Command\CommandInterface;

class CompositeMapper extends Mapper
{

    /**
     * @var ChainedItems
     */
    protected $chainedItems;

    /**
     * @param mixed $entity
     * @param Node $node
     * @param State $state
     * @return ContextCarrierInterface
     */
    public function queueCreate($entity, Node $node, State $state): ContextCarrierInterface
    {
        $cmd = parent::queueCreate($entity, $node, $state);
        return $this->chainedItems->queueCreate($entity, $node, $state, $cmd);
    }

    /**
     * @param mixed $entity
     * @param Node $node
     * @param State $state
     * @return ContextCarrierInterface
     */
    public function queueUpdate($entity, Node $node, State $state): ContextCarrierInterface
    {
        $cmd = parent::queueUpdate($entity, $node, $state);
        return $this->chainedItems->queueUpdate($entity, $node, $state, $cmd);
    }

    /**
     * @param mixed $entity
     * @param Node $node
     * @param State $state
     * @return CommandInterface
     */
    public function queueDelete($entity, Node $node, State $state): CommandInterface
    {
        $cmd = parent::queueDelete($entity, $node, $state);
        return $this->chainedItems->queueDelete($entity, $node, $state, $cmd);
    }

}
