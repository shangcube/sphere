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

use Doctrine\Common\Persistence\ObjectRepository;
use ShangCube\Sphere\Domain\DomainObject;

interface RepositoryInterface extends ObjectRepository
{
    public function find($id): DomainObject;

    public function getEntityClassName(): string ;

    public function save(DomainObject $entity);

    public function remove(DomainObject $entity);
}
