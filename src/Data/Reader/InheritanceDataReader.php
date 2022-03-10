<?php

declare(strict_types=1);

namespace Mailery\Cycle\Mapper\Data\Reader;

use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Data\Reader\Filter\FilterInterface;
use Yiisoft\Data\Reader\Filter\FilterProcessorInterface;
use Yiisoft\Data\Reader\Sort;
use Cycle\ORM\ORMInterface;
use Yiisoft\Yii\Cycle\Data\Reader\EntityReader;
use Cycle\ORM\Select;
use Spiral\Database\Query\SelectQuery;
use Spiral\Attributes\Internal\NativeAttributeReader;
use Cycle\Annotated\Annotation\Inheritance\DiscriminatorColumn;

class InheritanceDataReader implements DataReaderInterface
{

    /**
     * @var ORMInterface
     */
    private ORMInterface $orm;

    /**
     * @var NativeAttributeReader
     */
    private NativeAttributeReader $reader;

    /**
     * @var DataReaderInterface
     */
    private DataReaderInterface $dataReader;

    /**
     * @param ORMInterface $orm
     * @param Select|SelectQuery $query
     */
    public function __construct(ORMInterface $orm, Select|SelectQuery $query)
    {
        $this->orm = $orm;
        $this->reader = new NativeAttributeReader();
        $this->dataReader = new EntityReader($query);
    }

    /**
     * @param FilterInterface $filter
     * @return self
     */
    public function withFilter(FilterInterface $filter): self
    {
        $new = clone $this;
        $new->dataReader = $this->dataReader->withFilter($filter);

        return $new;
    }

    /**
     * @param FilterProcessorInterface $filterProcessors
     * @return self
     */
    public function withFilterProcessors(FilterProcessorInterface ...$filterProcessors): self
    {
        $new = clone $this;
        $new->dataReader = $this->dataReader->withFilterProcessors($filterProcessors);

        return $new;
    }

    /**
     * @param int $limit
     * @return self
     */
    public function withLimit(int $limit): self
    {
        $new = clone $this;
        $new->dataReader = $this->dataReader->withLimit($limit);

        return $new;
    }

    /**
     * @param int $offset
     * @return self
     */
    public function withOffset(int $offset): self
    {
        $new = clone $this;
        $new->dataReader = $this->dataReader->withOffset($offset);

        return $new;
    }

    /**
     * @param Sort|null $sort
     * @return self
     */
    public function withSort(?Sort $sort): self
    {
        $new = clone $this;
        $new->dataReader = $this->dataReader->withSort($sort);

        return $new;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->dataReader->count();
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return $this->dataReader->getIterator();
    }

    /**
     * @return Sort|null
     */
    public function getSort(): ?Sort
    {
        return $this->dataReader->getSort();
    }

    /**
     * @return iterable
     */
    public function read(): iterable
    {
        return array_map([$this, 'handleEntity'], $this->dataReader->read());
    }

    /**
     * @return mixed
     */
    public function readOne()
    {
        return $this->handleEntity($this->dataReader->readOne());
    }

    /**
     * @param mixed $entity
     * @return mixed
     */
    private function handleEntity($entity)
    {
        $reflection = new \ReflectionClass(get_parent_class($entity));

        foreach ($this->reader->getClassMetadata($reflection) as $attribute) {
            if (!$attribute instanceof DiscriminatorColumn) {
                continue;
            }

            $reflectionProperty = $reflection->getProperty($attribute->getName());
            $reflectionProperty->setAccessible(true);

            $type = $reflectionProperty->getValue($entity);

            if (!$entity instanceof $type) {
                return $this->orm->getRepository($type)->findByPK($entity->getId());
            }

            return $entity;
        }
    }

}
