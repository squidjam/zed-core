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

class Groups_Controller_Ajax extends Zikula_Controller
{
    public function _postSetup()
    {
        // no need for a Zikula_View so override it.
    }

    /**
     * Updates a group in the database
     *
     * @author Frank Schummertz - Frank Chestnut
     * @param gid the group id
     * @param gtype the group type
     * @param state the group state
     * @param nbumax the maximum of users
     * @param name the group name
     * @param description the group description
     * @return Ajax Response
     */
    public function updategroup($args)
    {
        if (!SecurityUtil::confirmAuthKey()) {
            LogUtil::registerAuthidError();
            throw new Zikula_Exception_Fatal();
        }

        $gid          = FormUtil::getPassedValue('gid', null,    'post');
        $gtype        = FormUtil::getPassedValue('gtype', 9999,  'post');
        $state        = FormUtil::getPassedValue('state', null,  'post');
        $nbumax       = FormUtil::getPassedValue('nbumax', 9999, 'post');
        $name         = DataUtil::convertFromUTF8(FormUtil::getPassedValue('name',        null, 'post'));
        $description  = DataUtil::convertFromUTF8(FormUtil::getPassedValue('description', null, 'post'));

        if (!SecurityUtil::checkPermission('Groups::', $gid.'::', ACCESS_EDIT)) {
            LogUtil::registerPermissionError(null, true);
            throw new Zikula_Exception_Forbidden();
        }

        if (empty($name)) {
            return new Zikula_Response_Ajax(array('result' => false, 'error' => true, 'gid' => $gid, 'message' => $this->__('Error! The group name is missing.')));
        }

        if (preg_match("/[\n\r\t\x0B]/", $name)) {
            $name = trim(preg_replace("/[\n\r\t\x0B]/", "", $name));
        }
        if (preg_match("/[\n\r\t\x0B]/", $description)) {
            $description = trim(preg_replace("/[\n\r\t\x0B]/", "", $description));
        }

        // Pass to API
        $res = ModUtil::apiFunc('Groups',
                'admin',
                'update',
                array('gid'         => $gid,
                'name'        => $name,
                'gtype'       => $gtype,
                'state'       => $state,
                'nbumax'      => $nbumax,
                'description' => $description));

        if ($res == false) {
            // check for sessionvar
            $msgs = LogUtil::getStatusMessagesText();
            if (!empty($msgs)) {
                // return with msg, but not via Zikula_Exception_Fatal
                return new Zikula_Response_Ajax(array('result' => false, 'error' => true, 'gid' => $gid, 'message' => $msgs));
            }
        }

        // Setting various defines
        $groupsCommon = new Groups_Helper_Common();
        $typelabel = $groupsCommon->gtypeLabels();
        $statelabel = $groupsCommon->stateLabels();

        // Using uncached query here as it was returning the unupdated group
        $group = DBUtil::selectObjectByID('groups', $gid, 'gid', null, null, null, false);

        // get group member count
        $group['nbuser'] = ModUtil::apiFunc('Groups', 'user', 'countgroupmembers', array('gid' => $gid));

        $group['statelbl'] = $statelabel[$group['state']];
        $group['gtypelbl'] = $typelabel[$group['gtype']];

        return new Zikula_Response_Ajax($group);
    }

    /**
     * Create a blank group and return it
     *
     * @author Frank Schummertz - Frank Chestnut
     * @param none
     * @return Ajax Response
     */
    public function creategroup()
    {
        if (!SecurityUtil::checkPermission('Groups::', '::', ACCESS_ADD)) {
            LogUtil::registerPermissionError(null, true);
            throw new Zikula_Exception_Forbidden();
        }

        if (!SecurityUtil::confirmAuthKey()) {
            LogUtil::registerAuthidError();
            throw new Zikula_Exception_Fatal();
        }

        $groupsCommon = new Groups_Helper_Common();
        $typelabel = $groupsCommon->gtypeLabels();
        $statelabel = $groupsCommon->stateLabels();

        // Default values
        $obj = array(
            'name'        => '',
            'gtype'       => Groups_Helper_Common::GTYPE_CORE,
            'state'       => Groups_Helper_Common::STATE_CLOSED,
            'nbumax'      => 0,
            'description' => ''
        );

        $newgroup = ModUtil::apiFunc('Groups', 'admin', 'create', $obj);

        if ($newgroup == false) {
            throw new Zikula_Exception_Fatal($this->__('Error! Could not create the new group.'));
        }

        // temporary group name
        $updobj = array(
            'name' => $this->__f('Group %s', $newgroup),
            'gid'  => $newgroup
        );

        DBUtil::updateObject($updobj, 'groups', null, 'gid');

        // finally select the new group
        $obj = DBUtil::selectObjectByID('groups', $newgroup, 'gid', null, null, null, false);

        $obj['statelbl']   = $statelabel[$obj['state']];
        $obj['gtypelbl']   = $typelabel[$obj['gtype']];
        $obj['membersurl'] = ModUtil::url('Groups', 'admin', 'groupmembership', array('gid' => $newgroup));

        return new Zikula_Response_Ajax($obj);
    }

    /**
     * Delete a group
     *
     * @author Frank Schummertz - Frank Chestnut
     * @param gid the group id
     * @return Ajax Response
     */
    public function deletegroup()
    {
        if (!SecurityUtil::confirmAuthKey()) {
            LogUtil::registerAuthidError();
            throw new Zikula_Exception_Fatal();
        }

        $gid   = FormUtil::getPassedValue('gid', null, 'get');
        $group = DBUtil::selectObjectByID('groups', $gid, 'gid');

        if (!SecurityUtil::checkPermission('Groups::', $gid.'::', ACCESS_DELETE)) {
            LogUtil::registerPermissionError(null, true);
            throw new Zikula_Exception_Forbidden();
        }

        // Check if it is the default group...
        $defaultgroup = $this->getVar('defaultgroup');

        if ($group['gid'] == $defaultgroup) {
            throw new Zikula_Exception_Fatal($this->__('Error! You cannot delete the default user group.'));
        }

        if (ModUtil::apiFunc('Groups', 'admin', 'delete', array('gid' => $gid)) == true) {
            return new Zikula_Response_Ajax(array('gid' => $gid));
        }

        throw new Zikula_Exception_Fatal($this->__f('Error! Could not delete the \'%s\' group.', $gid));
    }
}