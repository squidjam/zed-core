<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class Errors_Controller_User extends Zikula_Controller
{
    /**
     * Display an error
     * This function displays a generic error form
     * The template used is based on the error type passed
     * @param string $args['type'] error type ''404' or 'module'
     * @param string $args['message'] custom error message
     * @return string HTML string
     */
    public function main($args)
    {
        $type = FormUtil::getPassedValue('errtype', isset($args['type']) ? $args['type'] : LogUtil::getErrorType(), 'GET');

        // perform any error specific tasks
        $protocol = System::serverGetVar('SERVER_PROTOCOL');
        switch ($type) {
            case 301:
                header("{$protocol} 301 Moved Permanently");
                break;
            case 403:
                header("{$protocol} 403 Access Denied");
                break;
            case 404:
                header("{$protocol} 404 Not Found");
                break;
            case 500:
                header("{$protocol} 500 Internal Server Error");
            default:
        }

        // load the stylesheet
        PageUtil::addVar('stylesheet', 'system/Errors/style/style.css');

        $this->view->setCaching(false);

        // assign the document info
        $this->view->assign('reportlevel', System::getVar('reportlevel'))
                   ->assign('currenturi', System::getCurrentUri())
                   ->assign('localreferer', System::localReferer())
                   ->assign('sitename', System::getVar('sitename'))
                   ->assign('reportlevel', System::getVar('reportlevel'))
                   ->assign('funtext', System::getVar('funtext'));

        // assign the list of registered errors
        $this->view->assign('messages', LogUtil::getErrorMessages());

        // return the template output
        if ($this->view->template_exists($template = "errors_user_{$type}.tpl")) {
            return $this->view->fetch($template);
        } else {
            return $this->view->fetch('errors_user_main.tpl');
        }
    }

    /**
     * display a system error
     */
    public function system($args)
    {
        $this->view->setCaching(false);
        $this->view->assign($args);
        return $this->view->fetch('errors_user_system.tpl');
    }
}