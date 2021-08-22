<?php

declare(strict_types=1);

/**
 * Mapper for Cycle ORM
 * @link      https://github.com/maileryio/cycle-mapper
 * @package   Mailery\Cycle\Mapper
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

namespace Mailery\Cycle\Mapper\ChainItem;

use Cycle\ORM\Command\CommandInterface;
use Cycle\ORM\Command\ContextCarrierInterface;
use Cycle\ORM\Command\Database\Update;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Mailery\Cycle\Mapper\ChainItemInterface;

/**
 * You can use the annotated entities extension to automatically declare the needed columns from inside your mapper
 * @see https://github.com/cycle/docs/blob/master/advanced/timestamp.md#automatically-define-columns
 *
 * @Cycle\Annotated\Annotation\Table(
 *      columns={"created_at": @Cycle\Annotated\Annotation\Column(type="datetime"), "updated_at": @Column(type="datetime")}
 * )
 */
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
     * @param string $createdAt
     * @return \self
     */
    public function withCreatedAt(string $createdAt): self
    {
        $new = clone $this;
        $new->createdAt = $createdAt;

        return $new;
    }

    /**
     * @param string $updatedAt
     * @return \self
     */
    public function withUpdatedAt(string $updatedAt): self
    {
        $new = clone $this;
        $new->updatedAt = $updatedAt;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function queueCreate($entity, Node $node, State $state, ContextCarrierInterface $cmd): ContextCarrierInterface
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
    public function queueUpdate($entity, Node $node, State $state, ContextCarrierInterface $cmd): ContextCarrierInterface
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
    public function queueDelete($entity, Node $node, State $state, CommandInterface $cmd): CommandInterface
    {
        return $cmd;
    }
}
