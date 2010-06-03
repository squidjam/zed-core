<?php
/**
 * Copyright 2010 Zikula Foundation.
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPv2.1 (or at your option, any later version).
 * @package EventManager
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class Zikula_Exception extends Exception
{
    protected $debug;

    public function __construct($message, $code=0, $previous=null, $debug=null)
    {
        parent::__construct($message, $code, $previous);
        $this->debug = $debug;
    }

    public function getDebug()
    {
        return $this->debug;
    }
}