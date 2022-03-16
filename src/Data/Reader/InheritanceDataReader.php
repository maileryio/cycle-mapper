<?php

declare(strict_types=1);

namespace Mailery\Cycle\Mapper\Data\Reader;

use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Data\Reader\Filter\FilterInterface;
use Yiisoft\Data\Reader\Filter\FilterProcessorInterface;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Yii\Cycle\Data\Reader\EntityReader;
use Cycle\ORM\Select;
use Spiral\Database\Query\SelectQuery;
use Mailery\Cycle\Mapper\Data\Reader\Inheritance;

class InheritanceDataReader implements DataReaderInterface
{

    /**
     * @var DataReaderInterface
     */
    private DataReaderInterface $dataReader;

    /**
     * @param Inheritance $inheritance
     * @param Select|SelectQuery $query
     */
    public function __construct(
        private Inheritance $inheritance,
        Select|SelectQuery $query
    ) {
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
        return array_map([$this->inheritance, 'inherit'], $this->dataReader->read());
    }

    /**
     * @return mixed
     */
    public function readOne()
    {
        return $this->inheritance->inherit($this->dataReader->readOne());
    }

}
