<?php

namespace Mailery\Cycle\CompositeMapper;

use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\Command\ContextCarrierInterface;
use Cycle\ORM\Command\CommandInterface;

interface ItemInterface
{

    /**
     * @param mixed $entity
     * @param Node $node
     * @param State $state
     * @param ContextCarrierInterface $cmd
     * @return ContextCarrierInterface
     */
    public function queueCreate($entity, Node $node, State $state, ContextCarrierInterface $cmd): ContextCarrierInterface;

    /**
     * @param mixed $entity
     * @param Node $node
     * @param State $state
     * @param ContextCarrierInterface $cmd
     * @return ContextCarrierInterface
     */
    public function queueUpdate($entity, Node $node, State $state, ContextCarrierInterface $cmd): ContextCarrierInterface;

    /**
     * @param mixed $entity
     * @param Node $node
     * @param State $state
     * @param CommandInterface $cmd
     * @return CommandInterface
     */
    public function queueDelete($entity, Node $node, State $state, CommandInterface $cmd): CommandInterface;

}