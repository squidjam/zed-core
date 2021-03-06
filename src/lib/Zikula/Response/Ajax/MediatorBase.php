<?php
/**
 * Copyright Zikula Foundation 2010 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula
 * @subpackage Response
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Ajax class.
 */
abstract class Zikula_Response_Ajax_MediatorBase extends Zikula_Response_Ajax_Base
{
    /**
     * CSRF Token.
     * 
     * @var string
     */
    protected $csrfToken;

    /**
     * Flag to create a new nonce.
     * 
     * @var boolean
     */
    protected $newCsrfToken = true;

    /**
     * Reponse status messages.
     *
     * @var array
     */
    protected $messages;

    /**
     * Options array.
     * 
     * @var array
     */
    protected $options;
    
    /**
     * Convert class to string.
     *
     * @return string
     */
    public function __toString()
    {
        $payload = json_encode($this->generatePayload());
        header($this->createHttpResponseHeader());
        header('Content-type: application/json');
        return $payload;
    }

    /**
     * Generates payload.
     *
     * @return array
     */
    protected function generatePayload()
    {
        return array(
                'core' => $this->generateCoreData(),
                'data' => $this->payload,
        );
    }

    /**
     * Generate system level payload.
     * 
     * @return array
     */
    protected function generateCoreData()
    {
        $core = array();

        if ($this->options) {
            foreach ($this->options as $key => $value) {
                $core[$key] = $value;
            }
        }

        if ($this->csrfToken) {
            $core['authid'] = $this->csrfToken;
        }
        $logUtilMessages = (array)LogUtil::getStatusMessages();
        $core['statusmsg'] = array_merge($this->messages,$logUtilMessages);
        
        return $core;
    }

    /**
     * Add options.
     *
     * @param string $key   Option key.
     * @param mixed  $value Value.
     *
     * @return void
     */
    public function addOptions($key, $value)
    {
        $this->options[$key] = $value;
    }

}
