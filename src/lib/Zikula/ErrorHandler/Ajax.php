<?php
/**
 * Copyright 2010 Zikula Foundation.
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula
 * @subpackage Zikula_Core
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Ajax class.
 */
class Zikula_ErrorHandler_Ajax extends Zikula_ErrorHandler_Base
{
    /**
     * ErrorHandler for ajax front controller.
     *
     * @param integer $errno      Number of the error.
     * @param string  $errstr     Error message.
     * @param string  $errfile    Filename where the error occurred.
     * @param integer $errline    Line of the error.
     * @param string  $errcontext Context of the error.
     *
     * @return void
     */
    public function handler($errno, $errstr, $errfile, $errline, $errcontext)
    {return true; // temporary suppress
        $this->eventManager->notify($this->event->setArgs(array('errno' => $errno, 'errstr' => $errstr, 'errfile' => $errfile, 'errline' => $errline, 'errcontext' => $errcontext)));
        if ($this->errordisplay === 2) {
            // allow PHP to return error
            return false;
        }
        return true;
    }
}