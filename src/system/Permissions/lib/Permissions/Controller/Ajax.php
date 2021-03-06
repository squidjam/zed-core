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

class Permissions_Controller_Ajax extends Zikula_Controller
{
    public function _postSetup()
    {
        // no need for a Zikula_View so override it.
    }
    
    /**
     * Updates a permission rule in the database
     *
     * @param pid the permission id
     * @param gid the group id
     * @param seq the sequence
     * @param component the permission component
     * @param instance the permission instance
     * @param level the permission level
     * @return mixed updated permission as array or Ajax error
     */
    public function updatepermission()
    {
        if (!SecurityUtil::checkPermission('Permissions::', '::', ACCESS_ADMIN)) {
            LogUtil::registerPermissionError(null,true);
            throw new Zikula_Exception_Forbidden();
        }

        if (!SecurityUtil::confirmAuthKey()) {
            LogUtil::registerAuthidError();
            throw new Zikula_Exception_Fatal();
        }


        $pid       = FormUtil::getPassedValue('pid', null, 'post');
        $gid       = FormUtil::getPassedValue('gid', null, 'post');
        $seq       = FormUtil::getPassedValue('seq', 9999, 'post');
        $component = DataUtil::convertFromUTF8(FormUtil::getPassedValue('comp', '.*', 'post'));
        $instance  = DataUtil::convertFromUTF8(FormUtil::getPassedValue('inst', '.*', 'post'));
        $level     = FormUtil::getPassedValue('level', 0, 'post');

        if (preg_match("/[\n\r\t\x0B]/", $component)) {
            $component = trim(preg_replace("/[\n\r\t\x0B]/", "", $component));
            $instance = trim(preg_replace("/[\n\r\t\x0B]/", "", $instance));
        }
        if (preg_match("/[\n\r\t\x0B]/", $instance)) {
            $component = trim(preg_replace("/[\n\r\t\x0B]/", "", $component));
            $instance = trim(preg_replace("/[\n\r\t\x0B]/", "", $instance));
        }

        // Pass to API

        ModUtil::apiFunc('Permissions', 'admin', 'update',
                array('pid'       => $pid,
                'seq'       => $seq,
                'oldseq'    => $seq,
                'realm'     => 0,
                'id'        => $gid,
                'component' => $component,
                'instance'  => $instance,
                'level'     => $level));

        // read current settings and return them
        $perm = DBUtil::selectObjectByID('group_perms', $pid, 'pid');
        $accesslevels = SecurityUtil::accesslevelnames();
        $perm['levelname'] = $accesslevels[$perm['level']];
        switch($perm['gid']) {
            case -1:
                $perm['groupname'] = $this->__('All groups');
                break;
            case 0:
                $perm['groupname'] = $this->__('Unregistered');
                break;
            default:
                $group = DBUtil::selectObjectByID('groups', $perm['gid'], 'gid');
                $perm['groupname'] = $group['name'];
        }

        return new Zikula_Response_Ajax($perm);
    }

    /**
     *
     * @param permorder array of sorted permissions (value = permission id)
     * @return mixed true or Ajax error
     */
    public function changeorder()
    {
        if (!SecurityUtil::checkPermission('Permissions::', '::', ACCESS_ADMIN)) {
            LogUtil::registerPermissionError(null,true);
            throw new Zikula_Exception_Forbidden();
        }

        if (!SecurityUtil::confirmAuthKey()) {
            LogUtil::registerAuthidError();
            throw new Zikula_Exception_Fatal();
        }

        $permorder = FormUtil::getPassedValue('permorder');

        $dbtable = DBUtil::getTables();
        $permcolumn = $dbtable['group_perms_column'];

        for($cnt=0; $cnt<count($permorder); $cnt++) {
            $where = "WHERE $permcolumn[pid] = '" . (int)DataUtil::formatForStore($permorder[$cnt]) . "'";
            $obj = array('sequence' => $cnt);
            DBUtil::updateObject($obj, 'group_perms', $where, 'pid');
        }
        return new Zikula_Response_Ajax(array('result' => true));
    }

    /**
     * Create a blank permission and return it
     *
     * @return mixed array with new permission or Ajax error
     */
    public function createpermission()
    {
        if (!SecurityUtil::checkPermission('Permissions::', '::', ACCESS_ADMIN)) {
            LogUtil::registerPermissionError(null,true);
            throw new Zikula_Exception_Forbidden();
        }

        if (!SecurityUtil::confirmAuthKey()) {
            LogUtil::registerAuthidError();
            throw new Zikula_Exception_Fatal();
        }

        // add a blank permission
        $dummyperm = array('realm'     => 0,
                'id'        => 0,
                'component' => '.*',
                'instance'  => '.*',
                'level'     => ACCESS_NONE,
                'insseq'    => -1);

        $newperm = ModUtil::apiFunc('Permissions', 'admin', 'create', $dummyperm);
        if ($newperm == false) {
            AjaxUtil::error($this->__('Error! Could not create new permission rule.'));
        }

        $accesslevels = SecurityUtil::accesslevelnames();

        $newperm['instance']  = DataUtil::formatForDisplay($newperm['instance']);
        $newperm['component'] = DataUtil::formatForDisplay($newperm['component']);
        $newperm['levelname'] = $accesslevels[$newperm['level']];
        $newperm['groupname'] = $this->__('Unregistered');

        return new Zikula_Response_Ajax($newperm);
    }

    /**
     * Delete a permission
     *
     * @param pid the permission id
     * @return mixed the id of the permission that has been deleted or Ajax error
     */
    public function deletepermission()
    {
        if (!SecurityUtil::checkPermission('Permissions::', '::', ACCESS_ADMIN)) {
            LogUtil::registerPermissionError(null,true);
            throw new Zikula_Exception_Forbidden();
        }

        if (!SecurityUtil::confirmAuthKey()) {
            LogUtil::registerAuthidError();
            throw new Zikula_Exception_Fatal();
        }

        $pid = FormUtil::getPassedValue('pid', null, 'get');

        // check if this is the overall admin permssion and return if this shall be deleted
        $perm = DBUtil::selectObjectByID('group_perms', $pid, 'pid');
        if ($perm['pid'] == 1 && $perm['level'] == ACCESS_ADMIN && $perm['component'] == '.*' && $perm['instance'] == '.*') {
            AjaxUtil::error($this->__('Notice: You cannot delete the main administration permission rule.'));
        }

        if (ModUtil::apiFunc('Permissions', 'admin', 'delete', array('pid' => $pid)) == true) {
            if ($pid == $this->getVar('adminid')) {
                $this->setVar('adminid', 0);
                $this->setVar('lockadmin', false);
            }
            return new Zikula_Response_Ajax(array('pid' => $pid));
        }

        throw new Zikula_Exception_Fatal($this->__f('Error! Could not delete permission rule with ID %s.', $pid));
    }

    /**
     * Test a permission rule for a given username
     *
     * @param test_user the username
     * @param test_component the component
     * @param test_instance the instance
     * @param test_level the accesslevel
     * @return string with test result for display
     */
    public function testpermission()
    {
        if (!SecurityUtil::checkPermission('Permissions::', '::', ACCESS_ADMIN)) {
            LogUtil::registerPermissionError(null,true);
            throw new Zikula_Exception_Forbidden();
        }

        $uname = DataUtil::convertFromUTF8(FormUtil::getPassedValue('test_user', '', 'get'));
        $comp  = DataUtil::convertFromUTF8(FormUtil::getPassedValue('test_component', '.*', 'get'));
        $inst  = DataUtil::convertFromUTF8(FormUtil::getPassedValue('test_instance', '.*', 'get'));
        $level = DataUtil::convertFromUTF8(FormUtil::getPassedValue('test_level', ACCESS_READ, 'get'));

        $result = $this->__('Permission check result:') . ' ';
        $uid = UserUtil::getIdFromName($uname);

        if ($uid==false) {
            $result .= '<span id="permissiontestinfored">' . $this->__('unknown user.') . '</span>';
        } else {
            if (SecurityUtil::checkPermission($comp, $inst, $level, $uid)) {
                $result .= '<span id="permissiontestinfogreen">' . $this->__('permission granted.') . '</span>';
            } else {
                $result .= '<span id="permissiontestinfored">' . $this->__('permission not granted.') . '</span>';
            }
        }

        return new Zikula_Response_Ajax(array('testresult' => $result));
    }
}