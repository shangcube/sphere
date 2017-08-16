<?php
/**
 * Copyright (c) 2014-present, San Wei Ju Yuan Tech Ltd. <https://www.3vjuyuan.com>
 * All rights reserved.
 *
 * This file is part of swim-reformer, licensed under the MIT license (MIT) found
 * in the LICENSE file in the root directory of this source tree.
 *
 * For more details:
 * https://www.3vjuyuan.com/licenses/mit.html
 *
 * @author Team Delta <delta@3vjuyuan.com>
 */

namespace ShangCube\Sphere\Persistence;

use ShangCube\Sphere\Domain\DomainObject;

/**
 * Class AbstractRepository
 * @package ShangCube\Sphere\Persistence
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var string
     */
    protected $entityClassName;

    /**
     * AbstractRepository constructor.
     * @param string $entityClassName
     * @deprecated The constructor will be remove by using dependency injection.
     *
     * Do not use new to create repository object, but using ObjectManager::get(), which supports the
     * dependency injection later
     */
    public function __construct(string $entityClassName)
    {
        $this->entityClassName = $entityClassName;
        $this->persistenceManager = new PersistenceManager($entityClassName);
    }

    public function findAll(array $orderBy = null)
    {
        return $this->persistenceManager->findAll($orderBy);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->persistenceManager->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->persistenceManager->findOneBy($criteria, $orderBy);
    }

    public function find($id): DomainObject
    {
        return $this->persistenceManager->find($this->entityClassName, $id);
    }

    /**
     * @return string
     */
    final public function getClassName()
    {
        return $this->getEntityClassName();
    }

    /**
     * @return string
     */
    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    public function save(DomainObject $entity)
    {
        $this->persistenceManager->persist($entity);
    }

    public function remove(DomainObject $entity)
    {
        $this->persistenceManager->remove($entity);
    }

    public function flush() {
        $this->persistenceManager->flush();
    }
}
