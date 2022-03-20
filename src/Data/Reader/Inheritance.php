<?php

declare(strict_types=1);

namespace Mailery\Cycle\Mapper\Data\Reader;

use Cycle\ORM\ORMInterface;
use Spiral\Attributes\Internal\NativeAttributeReader;
use Cycle\Annotated\Annotation\Inheritance\DiscriminatorColumn;

class Inheritance
{

    /**
     * @param ORMInterface $orm
     * @param NativeAttributeReader $reader
     */
    public function __construct(
        private ORMInterface $orm,
        private NativeAttributeReader $reader
    ) {}

    /**
     * @param object $entity
     * @return object
     */
    public function inherit(object $entity): object
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
                $entity = $this->orm->getRepository($type)->findByPK($entity->getId());
            }

            return ($entity && method_exists($entity, 'withInheritance'))
                    ? $entity->withInheritance($this)
                    : $entity;
        }
    }

}
