<?php
/**
 * Copyright 2010 Zikula Foundation
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula_Common
 * @subpackage ServiceManager
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

namespace Zikula\Common\ServiceManager;

/**
 * Service definition configuration.
 *
 * This is used in advanced definition configuration to specify that a parameter
 * is a service managed by the ServiceManager.
 */
class Service
{
    /**
     * Service identifier.
     *
     * @var string
     */
    protected $id;

    /**
     * Instance of Definition class.
     *
     * @var object
     */
    protected $definition;

    /**
     * Determine if service should be single instance of multiple.
     *
     * @var boolean
     */
    protected $shared;

    /**
     * Service storage.
     *
     * @var object
     */
    protected $service;

    /**
     * Constructor.
     *
     * Setup the identifier of this service.
     *
     * @param string     $id         Unique identifier.
     * @param Definition $definition Optional instance of Definition class.
     * @param boolean    $shared     True if the service will be single instance only.
     */
    public function __construct($id, Definition $definition = null, $shared = true)
    {
        $this->id = $id;
        $this->definition = $definition;
        $this->shared = $shared;
    }

    /**
     * Getter for id property.
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter for definition property.
     *
     * @return object Definition stored in property.
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Reports if this service has an attached definition.
     *
     * @return boolean True if Definition instance is stored.
     */
    public function hasDefinition()
    {
        return (bool)$this->definition;
    }

    /**
     * Reports if this a shared service.
     *
     * @return boolean Returns true if the service is a single instance (shared) service.
     */
    public function isShared()
    {
        return $this->shared;
    }

    /**
     * Getter for the service property.
     *
     * @return object|null The object or null if none exists.
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Setter for service property.
     *
     * @param object $service Instanciated object.
     *
     * @return void
     */
    public function setService($service)
    {
        $this->service = $service;
        $this->definition = null;
    }

    /**
     * Returns true is service exists (meaning an instanciated shared class).
     *
     * @return boolean
     */
    public function hasService()
    {
        return (bool)$this->service;
    }
}