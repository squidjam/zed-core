<?php
/**
 * Copyright Zikula Foundation 2010 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license MIT
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Form handler for create and edit.
 */
class ExampleDoctrine_Handler_Edit extends Form_Handler
{
    /**
     * User id.
     *
     * When set this handler is in edit mode.
     *
     * @var integer
     */
    private $_id;

    /**
     * Setup form.
     *
     * @param Form_View $view Current Form_View instance.
     *
     * @return boolean
     */
    public function initialize(Form_View $view)
    {
        // load and assign registred categories
        $registryCategories  = CategoryRegistryUtil::getRegisteredModuleCategories('ExampleDoctrine', 'exampledoctrine_users');
        $categories = array();
        foreach ($registryCategories as $property => $cid) {
            $categories[$property] = (int)$cid;
        }

        $view->assign('registries', $categories);

        $id = FormUtil::getPassedValue('id', null, "GET", FILTER_SANITIZE_NUMBER_INT);
        if ($id) {
            // load user with id
            $user = Doctrine_Core::getTable('ExampleDoctrine_Model_User')->find($id);

            if ($user) {
                // switch to edit mode
                $this->_id = $id;
                // assign current values to form fields
                $view->assign($user->toArray());
            } else {
                return LogUtil::registerError($this->__f('User with id %s not found', $id));
            }
        }

        return true;
    }

    /**
     * Handle form submission.
     *
     * @param Form_View $view  Current Form_View instance.
     * @param array     &$args Args.
     *
     * @return boolean
     */
    public function handleCommand(Form_View $view, &$args)
    {
        // check for valid form
        if (!$view->isValid()) {
            return false;
        }

        // load form values
        $data = $view->getValues();

        // switch between edit and create mode
        if ($this->_id) {
            $user = Doctrine_Core::getTable('ExampleDoctrine_Model_User')->find($this->_id);
        } else {
            $user = new ExampleDoctrine_Model_User();
        }

        $user->merge($data);
        $user->save();

        return $view->redirect(pnModURL('ExampleDoctrine', 'user','view'));
    }
}

