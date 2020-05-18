<?php

declare(strict_types=1);

/**
 * Mapper for Cycle ORM
 * @link      https://github.com/maileryio/cycle-mapper
 * @package   Mailery\Cycle\Mapper
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Subscriber\Mapper\ChainedItsem;

use Cycle\ORM\Command\CommandInterface;
use Cycle\ORM\Command\ContextCarrierInterface;
use Cycle\ORM\Command\Database\Update;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Mailery\Cycle\Mapper\ChainItemInterface;

class Timestamped implements ChainItemInterface
{
    /**
     * @var string|null
     */
    private $createdAt;

    /**
     * @var string|null
     */
    private $updatedAt;

    /**
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(string $createdAt = null, string $updatedAt = null)
    {
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function queueCreate(ContextCarrierInterface $cmd, Node $node, State $state): ContextCarrierInterface
    {
        if ($this->createdAt !== null) {
            $state->register($this->createdAt, new \DateTimeImmutable(), true);
            $cmd->register($this->createdAt, new \DateTimeImmutable(), true);
        }

        if ($this->updatedAt !== null) {
            $state->register($this->updatedAt, new \DateTimeImmutable(), true);
            $cmd->register($this->updatedAt, new \DateTimeImmutable(), true);
        }

        return $cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function queueUpdate(ContextCarrierInterface $cmd, Node $node, State $state): ContextCarrierInterface
    {
        if ($this->updatedAt !== null && $cmd instanceof Update) {
            $state->register($this->updatedAt, new \DateTimeImmutable(), true);
            $cmd->registerAppendix($this->updatedAt, new \DateTimeImmutable());
        }

        return $cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function queueDelete(CommandInterface $cmd, Node $node, State $state): CommandInterface
    {
        return $cmd;
    }
}
