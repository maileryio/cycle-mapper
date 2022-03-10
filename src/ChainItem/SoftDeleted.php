<?php

namespace Mailery\Cycle\Mapper\ChainItem;

use Cycle\ORM\Command\CommandInterface;
use Cycle\ORM\Command\Database\Update;
use Cycle\ORM\Context\ConsumerInterface;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Mailery\Cycle\Mapper\ChainItemInterface;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Schema;

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
     * @var ORMInterface
     */
    private ORMInterface $orm;

    /**
     * @var string|null
     */
    private $deletedAt;

    /**
     * @param ORMInterface $orm
     */
    public function __construct(ORMInterface $orm)
    {
        $this->orm = $orm;
    }

    /**
     * @param string $deletedAt
     * @return \self
     */
    public function withDeletedAt(string $deletedAt): self
    {
        $new = clone $this;
        $new->deletedAt = $deletedAt;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function queueCreate($entity, Node $node, State $state, CommandInterface $cmd): CommandInterface
    {
        return $cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function queueUpdate($entity, Node $node, State $state, CommandInterface $cmd): CommandInterface
    {
        return $cmd;
    }

    /**
     * {@inheritdoc}
     */
    public function queueDelete($entity, Node $node, State $state, CommandInterface $cmd): CommandInterface
    {
        $source = $this->orm->getSource($node->getRole());

        // identify entity as being "deleted"
        $state->setStatus(Node::SCHEDULED_DELETE);
        $state->decClaim();

        $command = new Update(
            $source->getDatabase(),
            $source->getTable(),
            [$this->deletedAt => new \DateTimeImmutable()]
        );

        // forward primaryKey value from entity state
        // this sequence is only required if the entity is created and deleted
        // within one transaction
        $columns = $this->orm->getSchema()->define($node->getRole(), Schema::COLUMNS);
        $primaryKey = $this->orm->getSchema()->define($node->getRole(), Schema::PRIMARY_KEY);
        $primaryColumn = $columns[$primaryKey] ?? $primaryKey;

        $command->waitScope($primaryColumn);
        $state->forward(
            $primaryKey,
            $command,
            $primaryColumn,
            true,
            ConsumerInterface::SCOPE
        );

        return $command;
    }
}
