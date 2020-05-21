<?php

namespace Mailery\Cycle\Mapper\ChainItem;

use Cycle\ORM\Command\CommandInterface;
use Cycle\ORM\Command\Database\Update;
use Cycle\ORM\Context\ConsumerInterface;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\Command\ContextCarrierInterface;
use Mailery\Cycle\Mapper\ChainItemInterface;

/**
 * You can use the annotated entities extension to automatically declare the needed columns from inside your mapper
 * @see https://github.com/cycle/docs/blob/master/advanced/soft-deletes.md
 *
 * @Cycle\Annotated\Annotation\Table(
 *      columns={"deleted_at": @Cycle\Annotated\Annotation\Column(type="datetime", nullable=true)}
 * )
 */
class SoftDeleted implements ChainItemInterface
{
    /**
     * @var string|null
     */
    private $deletedAt;

    /**
     * @param string|null $deletedAt
     */
    public function __construct(string $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function queueCreate($entity, Node $node, State $state, ContextCarrierInterface $cmd): ContextCarrierInterface
    {
        return $cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function queueUpdate($entity, Node $node, State $state, ContextCarrierInterface $cmd): ContextCarrierInterface
    {
        return $cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function queueDelete($entity, Node $node, State $state, CommandInterface $cmd): CommandInterface
    {
        // identify entity as being "deleted"
        $state->setStatus(Node::SCHEDULED_DELETE);
        $state->decClaim();

        $command = new Update(
            $this->source->getDatabase(),
            $this->source->getTable(),
            [$this->deletedAt => new \DateTimeImmutable()]
        );

        // forward primaryKey value from entity state
        // this sequence is only required if the entity is created and deleted
        // within one transaction
        $command->waitScope($this->primaryColumn);
        $state->forward(
            $this->primaryKey,
            $command,
            $this->primaryColumn,
            true,
            ConsumerInterface::SCOPE
        );

        return $command;
    }
}
