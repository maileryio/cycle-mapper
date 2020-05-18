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
use Cycle\ORM\Exception\MapperException;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;

interface ChainItemInterface
{
    /**
     * Initiate chain of commands require to store object and it's data into persistent storage.
     *
     * @param ContextCarrierInterface $cmd
     * @param Node   $node
     * @param State  $state
     * @throws MapperException
     * @return ContextCarrierInterface
     */
    public function queueCreate(ContextCarrierInterface $cmd, Node $node, State $state): ContextCarrierInterface;

    /**
     * Initiate chain of commands required to update object in the persistent storage.
     *
     * @param ContextCarrierInterface $cmd
     * @param Node   $node
     * @param State  $state
     * @throws MapperException
     * @return ContextCarrierInterface
     */
    public function queueUpdate(ContextCarrierInterface $cmd, Node $node, State $state): ContextCarrierInterface;

    /**
     * Initiate sequence of of commands required to delete object from the persistent storage.
     *
     * @param CommandInterface $cmd
     * @param Node   $node
     * @param State  $state
     * @throws MapperException
     * @return CommandInterface
     */
    public function queueDelete(CommandInterface $cmd, Node $node, State $state): CommandInterface;
}
