<?php
/**
 * Copyright (c) 2014-present, San Wei Ju Yuan Tech Ltd. <https://www.3vjuyuan.com>
 * This file is part of sphere-framework
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 *
 * For more details:
 * https://www.3vjuyuan.com/licenses.html
 *
 * @author Team Delta <delta@3vjuyuan.com>
 */

namespace ShangCube\Sphere\Utility;

use ShangCube\Sphere\Object\SingletonInterface;
use ShangCube\Sphere\Exception\Runtime\{InvalidParameterException, CycleChainedException};
use ReflectionClass;

class ObjectManager implements SingletonInterface
{
    /**
     * @var ObjectManager
     */
    protected static $objectManagerInstance;

    /**
     * @var array
     */
    protected $singletonInstances = [];

    /**
     * @var
     *
     * @todo use cache in object manager
     */
    protected $cache;

    /**
     * @var array
     */
    protected $currentlyInstantiatingObjects = [];

    /**
     * get singleton instance of object manager
     *
     * @return ObjectManager
     */
    public static function getObjectManagerInstance() {
        if(static::$objectManagerInstance === null) {
            static::$objectManagerInstance = new static();
        }

        return static::$objectManagerInstance;
    }

    /**
     * ObjectManager constructor.
     * Simple set the constructor as protected
     */
    protected function __construct()
    {
    }

    /**
     * @param string $className
     * @param array ...$args
     * @return object
     *
     * @todo Check if the class name is valid class name
     * @todo Converter the class name to the one with fully namespace
     */
    public function getObject(string $className, ... $args)
    {
        if($className === self::class) {
            return $this;
        }

        if(isset($this->singletonInstances[$className])) {
            if(!empty($args)) {
                throw new InvalidParameterException("The singleton object constructor can not have any arguments.", 1504112676);
            }
            return $this->singletonInstances[$className];
        }

        $reflectionClass = new ReflectionClass($className);
        if($isSingleton = $reflectionClass->implementsInterface(SingletonInterface::class)) {
            if(!empty($args)) {
                throw new InvalidParameterException("The singleton object constructor can not have any arguments.", 1504112676);
            }
            $instantiatingHash = md5($className);
        } else {
            $instantiatingHash = md5($className . '::' . implode(',', $args));
        }

        if (isset($this->currentlyInstantiatingObjects[$instantiatingHash])) {
            throw new CycleChainedException('Cycle chained object creation for class: ' . $className, 1504125464);
        }
        $this->currentlyInstantiatingObjects[$instantiatingHash] = true;

        // @todo Should here use newInstanceWithoutConstructor() and dependency injection to initialize the object?
        $instance = $reflectionClass->newInstanceArgs($args);
        if($isSingleton) {
            $this->singletonInstances[$className] = $instance;
        }
        unset($this->currentlyInstantiatingObjects[$instantiatingHash]);

        return $instance;
    }
}
