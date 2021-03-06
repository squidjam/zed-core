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


class Categories_Controller_Admin extends Zikula_Controller
{

    /**
     * main admin function
     */
    public function main()
    {
        // Security check will be done in view()
        return $this->view();
    }

    /**
     * view categories
     */
    public function view()
    {
        $root_id = FormUtil::getPassedValue ('dr', 1);

        if (!SecurityUtil::checkPermission('Categories::category', "ID::$root_id", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        if (!SecurityUtil::checkPermission('Categories::category', '::', ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }

        // disable attribution for performance
        $GLOBALS['dbtables']['categories_category_db_extra_enable_attribution'] = false;
        $dbtables    = DBUtil::getTables();
        $columnArray = array ('id', 'name', 'display_name', 'parent_id', 'path', 'ipath', 'status');
        $cats        = CategoryUtil::getSubCategories ($root_id, true, true, true, true, true, '', '', null, 'sort_value', $columnArray);
        $menuTxt     = CategoryUtil::getCategoryTreeJS ($cats, true, true);
        $GLOBALS['dbtables']['categories_category_db_extra_enable_attribution'] = true;

        $this->view->setCaching(false);

        $this->view->assign('menuTxt', $menuTxt);
        return $this->view->fetch('categories_admin_view.tpl');
    }

    /**
     * display configure module page
     */
    public function config ()
    {
        if (!SecurityUtil::checkPermission('Categories::', "::", ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $this->view->setCaching(false);

        return $this->view->fetch('categories_admin_config.tpl');
    }

    /**
     * edit category
     */
    public function edit ()
    {
        $cid      = FormUtil::getPassedValue ('cid', 0);
        $root_id  = FormUtil::getPassedValue ('dr', 1);
        $mode     = FormUtil::getPassedValue ('mode', 'new');
        $allCats  = '';
        $editCat  = '';

        $languages = ZLanguage::getInstalledLanguages();

        // indicates that we're editing
        if ($mode == 'edit')
        {
            if (!SecurityUtil::checkPermission('Categories::category', "::", ACCESS_ADMIN)) {
                return LogUtil::registerPermissionError();
            }

            if (!$cid) {
                return LogUtil::registerError($this->__('Error! Cannot determine valid \'cid\' for edit mode in \'Categories_admin_edit\'.'));
            }

            $category = new Categories_DBObject_Category();
            $editCat  = $category->select ($cid);
            if ($editCat == false) {
                return LogUtil::registerError($this->__('Sorry! No such item found.'), 404);
            }
        }
        else
        {
            // new category creation
            if (!SecurityUtil::checkPermission('Categories::category', '::', ACCESS_ADD)) {
                return LogUtil::registerPermissionError();
            }

            // since we inherit the domain settings from the parent, we get
            // the inherited (and merged) object from session
            if (isset($_SESSION['newCategory']) && $_SESSION['newCategory']) {
                $editCat = $_SESSION['newCategory'];
                unset ($_SESSION['newCategory']);
                $category = new Categories_DBObject_Category(); // need this for validation info
            }
            // if we're back from validation get the object from input
            elseif (FormUtil::getValidationErrors()) {
                $category = new Categories_DBObject_Category('V'); // need this for validation info
                $editCat  = $category->get ();
            }
            // someone just pressen 'new' -> populate defaults
            else {
                $category = new Categories_DBObject_Category(); // need this for validation info
                $editCat['sort_value'] = '0';
            }
        }

        $reloadOnCatChange = ($mode != 'edit');
        $allCats  = CategoryUtil::getSubCategories ($root_id, true, true, true, false, true);

        // now remove the categories which are below $editCat ...
        // you should not be able to set these as a parent category as it creates a circular hierarchy (see bug #4992)
        if (isset($editCat['ipath'])) {
            $cSlashEdit = StringUtil::countInstances ($editCat['ipath'], '/');
            foreach ($allCats as $k=>$v) {
                $cSlashCat = StringUtil::countInstances ($v['ipath'], '/');
                if ($cSlashCat >= $cSlashEdit && strpos ($v['ipath'], $editCat['ipath']) !== false) {
                    unset ($allCats[$k]);
                }
            }
        }

        $selector = CategoryUtil::getSelector_Categories ($allCats, 'id', (isset($editCat['parent_id']) ? $editCat['parent_id'] : 0), 'category[parent_id]', isset($defaultValue) ? $defaultValue : null, null, $reloadOnCatChange);

        $attributes = isset($editCat['__ATTRIBUTES__']) ? $editCat['__ATTRIBUTES__'] : array();

        $this->view->setCaching(false);

        $this->view->assign('mode', $mode)
                   ->assign('category', $editCat)
                   ->assign('attributes', $attributes)
                   ->assign('languages', $languages)
                   ->assign('categorySelector', $selector)
                   ->assign('validation', $category->_objValidation);

        if ($mode == 'edit') {
            $this->view->assign('haveSubcategories', CategoryUtil::haveDirectSubcategories ($cid))
                       ->assign('haveLeafSubcategories', CategoryUtil::haveDirectSubcategories ($cid, false, true));
        }

        return $this->view->fetch('categories_admin_edit.tpl');
    }

    public function editregistry ()
    {
        if (!SecurityUtil::checkPermission('Categories::', "::", ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $root_id  = FormUtil::getPassedValue ('dr', 1);
        $id       = FormUtil::getPassedValue ('id', 0);
        $ot       = FormUtil::getPassedValue ('ot', 'registry');

        $class = "Categories_DBObject_".ucwords($ot);
        $arrayClass = "Categories_DBObject_".ucwords($ot).'Array';

        $obj  = new $class ();
        $data = $obj->getDataFromInput ();
        if (!$data) {
            $data = $obj->getFailedValidationData ();
            if (!$data) {
                $data = array();
            }
        }

        $where    = '';
        $sort     = 'crg_modname, crg_property';
        $objArray = new $arrayClass ();
        $dataA    = $objArray->get($where, $sort);

        $this->view->setCaching(false);

        $this->view->assign('objectArray', $dataA)
                   ->assign('newobj', $data)
                   ->assign('root_id', $root_id)
                   ->assign('id', $id)
                   ->assign('validation', $obj->_objValidation);

        return $this->view->fetch('categories_admin_registry_edit.tpl');
    }

    /**
     * display new category form
     */
    public function newcat ()
    {
        $_POST['mode'] = 'new';
        return $this->edit ();
    }

    /**
     * generic function to handle copy, delete and move operations
     */
    public function op ()
    {
        $cid      = FormUtil::getPassedValue ('cid', 1);
        $root_id  = FormUtil::getPassedValue ('dr', 1);
        $op       = FormUtil::getPassedValue ('op', 'NOOP');

        if (!SecurityUtil::checkPermission('Categories::category', "ID::$cid", ACCESS_DELETE)) {
            return LogUtil::registerPermissionError();
        }

        $category = new Categories_DBObject_Category();
        $category    = $category->select ($cid);
        $subCats     = CategoryUtil::getSubCategories ($cid, false, false);
        $allCats     = CategoryUtil::getSubCategories ($root_id, true, true, true, false, true, $cid);
        $selector    = CategoryUtil::getSelector_Categories ($allCats);

        $this->view->setCaching(false);

        $this->view->assign('category', $category)
                   ->assign('numSubcats', count($subCats))
                   ->assign('categorySelector', $selector);

        $tplName = 'categories_admin_' . $op . '.tpl';
        return $this->view->fetch($tplName);
    }

    /**
     * global module preferences
     */
    public function preferences()
    {
        if (!SecurityUtil::checkPermission('Categories::preferences', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $this->view->setCaching(false);

        $this->view->assign ('userrootcat', $this->getVar('userrootcat', '/__SYSTEM__'))
                   ->assign ('allowusercatedit', $this->getVar('allowusercatedit', 0))
                   ->assign ('autocreateusercat', $this->getVar('autocreateusercat', 0))
                   ->assign ('autocreateuserdefaultcat', $this->getVar('autocreateuserdefaultcat', 0))
                   ->assign ('userdefaultcatname', $this->getVar('userdefaultcatname', 0))
                   ->assign ('permissionsall', $this->getVar('permissionsall', 0));

        return $this->view->fetch('categories_admin_preferences.tpl');
    }
}
