<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_System_Modules
 * @subpackage Users
 */

class Users_Installer extends Zikula_Installer
{
    /**
     * Initialise the users module.
     *
     * This function is only ever called once during the lifetime of a particular
     * module instance. This function MUST exist in the pninit file for a module.
     *
     * @return bool True on success, false otherwise.
     */
    public function install()
    {
        if (!DBUtil::createTable('session_info')) {
            return false;
        }

        if (!DBUtil::createTable('users')) {
            return false;
        }

        if (!DBUtil::createTable('users_temp')) {
            return false;
        }

        if (!DBUtil::createTable('users_shadow')) {
            return false;
        }

        // Set default values for module
        $this->defaultdata();

        ModUtil::setVar('Users', 'itemsperpage', 25);
        ModUtil::setVar('Users', 'accountdisplaygraphics', 1);
        ModUtil::setVar('Users', 'accountitemsperpage', 25);
        ModUtil::setVar('Users', 'accountitemsperrow', 5);
        ModUtil::setVar('Users', 'changepassword', 1);
        ModUtil::setVar('Users', 'changeemail', 1);
        ModUtil::setVar('Users', 'reg_allowreg', 1);
        ModUtil::setVar('Users', 'reg_verifyemail', 1);
        ModUtil::setVar('Users', 'reg_Illegalusername', 'root adm linux webmaster admin god administrator administrador nobody anonymous anonimo');
        ModUtil::setVar('Users', 'reg_Illegaldomains', '');
        ModUtil::setVar('Users', 'reg_Illegaluseragents', '');
        ModUtil::setVar('Users', 'reg_noregreasons', __('Sorry! New user registration is currently disabled.'));
        ModUtil::setVar('Users', 'reg_uniemail', 1);
        ModUtil::setVar('Users', 'reg_notifyemail', '');
        ModUtil::setVar('Users', 'reg_optitems', 0);
        ModUtil::setVar('Users', 'userimg', 'images/menu');
        ModUtil::setVar('Users', 'avatarpath', 'images/avatar');
        ModUtil::setVar('Users', 'allowgravatars', 1);
        ModUtil::setVar('Users', 'gravatarimage', 'gravatar.gif');
        ModUtil::setVar('Users', 'minage', 13);
        ModUtil::setVar('Users', 'minpass', 5);
        ModUtil::setVar('Users', 'anonymous', 'Guest');
        ModUtil::setVar('Users', 'loginviaoption', 0);
        ModUtil::setVar('Users', 'lowercaseuname', 0);
        ModUtil::setVar('Users', 'moderation', 0);
        ModUtil::setVar('Users', 'hash_method', 'sha256');
        ModUtil::setVar('Users', 'login_redirect', 1);
        ModUtil::setVar('Users', 'reg_question', '');
        ModUtil::setVar('Users', 'reg_answer', '');
        ModUtil::setVar('Users', 'idnnames', 1);
        ModUtil::setVar('Users', 'use_password_strength_meter', 0);
        ModUtil::setVar('Users', 'authmodules', 'Users');
        ModUtil::setVar('Users', 'recovery_forcepwdchg', 'Users', 0);

        // Initialisation successful
        return true;
    }

    /**
     * Upgrade the users module from an older version.
     *
     * This function must consider all the released versions of the module!
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param string $oldVersion Version number string to upgrade from.
     *
     * @return mixed True on success, last valid version string or false if fails.
     */
    public function upgrade($oldversion)
    {
        // Upgrade dependent on old version number
        switch ($oldversion)
        {
            case '1.11':
                $this->upgrade_migrateSerialisedUserTemp();
            case '1.12':
                ModUtil::setVar('Users', 'avatarpath', 'images/avatar');
                ModUtil::setVar('Users', 'lowercaseuname', 1);
            case '1.13':
                ModUtil::setVar('Users', 'use_password_strength_meter', 0);
            case '1.14':
                if (ModUtil::getVar('Users', 'hash_method') == 'md5') {
                    ModUtil::setVar('Users', 'hash_method', 'sha256');
                }
            case '1.15':
                ModUtil::delVar('Users', 'savelastlogindate');
                ModUtil::setVar('Users', 'allowgravatars', 1);
                ModUtil::setVar('Users', 'gravatarimage', 'gravatar.gif');
                if (!DBUtil::changeTable('users_temp')) {
                    return '1.15';
                }
            case '1.16':
                ModUtil::setVar('Users', 'authmodules', 'Users');
            case '1.17':
                DBUtil::dropIndex('uname', 'users');
                DBUtil::dropIndex('email', 'users');
                if (!DBUtil::changeTable('users')
                        || !DBUtil::changeTable('users_temp')
                        || !DBUtil::createTable('users_shadow'))
                {
                    return '1.17';
                }
            case '1.18':
        }

        // Update successful
        return true;
    }

    /**
     * Delete the users module.
     *
     * This function is only ever called once during the lifetime of a particular
     * module instance. This function MUST exist in the pninit file for a module.
     *
     * Since the users module should never be deleted we'all always return false here.
     *
     * @return bool false
     */
    public function uninstall()
    {
        // Deletion not allowed
        return false;
    }

    /**
     * Create the default data for the users module.
     *
     * This function is only ever called once during the lifetime of a particular
     * module instance.
     */
    public function defaultdata()
    {
        // Anonymous
        $record = array();
        $record['uid']             = '1';
        $record['uname']           = 'guest';
        $record['pass']            = '';
        $record['storynum']        = '10';
        $record['umode']           = '';
        $record['uorder']          = '0';
        $record['thold']           = '0';
        $record['noscore']         = '0';
        $record['bio']             = '';
        $record['ublockon']        = '0';
        $record['ublock']          = '';
        $record['theme']           = '';
        $record['commentmax']      = '4096';
        $record['counter']         = '0';
        $record['timezone_offset'] = '0';
        $record['hash_method']     = '1';
        $record['activated']       = UserUtil::ACTIVATED_ACTIVE;
        DBUtil::insertObject($record, 'users', 'uid', true);

        // Admin
        $record = array();
        $record['uid']             = '2';
        $record['uname']           = 'admin';
        $record['email']           = '';
        $record['pass']            = 'dc647eb65e6711e155375218212b3964';
        $record['storynum']        = '10';
        $record['umode']           = '';
        $record['uorder']          = '0';
        $record['thold']           = '0';
        $record['noscore']         = '0';
        $record['bio']             = '';
        $record['ublockon']        = '0';
        $record['ublock']          = '';
        $record['theme']           = '';
        $record['commentmax']      = '4096';
        $record['counter']         = '0';
        $record['timezone_offset'] = '0';
        $record['activated']       = UserUtil::ACTIVATED_ACTIVE;

        DBUtil::insertObject($record, 'users', 'uid', true);
    }

    /**
     * Migrate old DUDs to attributes.
     *
     * @return bool True.
     */
    public function migrate_duds_to_attributes()
    {
        ModUtil::dbInfoLoad('Profile');
        ModUtil::dbInfoLoad('ObjectData');
        $pntable = System::dbGetTables();
        $udtable   = $pntable['user_data'];
        $udcolumn  = $pntable['user_data_column'];
        $objtable  = $pntable['objectdata_attributes'];
        $objcolumn = $pntable['objectdata_attributes_column'];

        // load the user properties into an assoc array with prop_id as key
        $userprops = DBUtil::selectObjectArray('user_property', '', '', -1, -1, 'prop_id');

        // this array maps old DUDs to new attributes
        $mappingarray = array('_UREALNAME'      => 'realname',
                '_UFAKEMAIL'      => 'publicemail',
                '_YOURHOMEPAGE'   => 'url',
                '_TIMEZONEOFFSET' => 'tzoffset',
                '_YOURAVATAR'     => 'avatar',
                '_YLOCATION'      => 'city',
                '_YICQ'           => 'icq',
                '_YAIM'           => 'aim',
                '_YYIM'           => 'yim',
                '_YMSNM'          => 'msnm',
                '_YOCCUPATION'    => 'occupation',
                '_SIGNATURE'      => 'signature',
                '_EXTRAINFO'      => 'extrainfo',
                '_YINTERESTS'     => 'interests');

        $aks = array_keys($userprops);
        // expand the old DUDs with the new attribute names for the real conversion
        foreach ($aks as $ak) {
            if ($userprops[$ak]['prop_label'] == '_PASSWORD' || $userprops[$ak]['prop_label'] == '_UREALEMAIL') {
                // password and real email are core information, not attributes!
                unset($userprops[$ak]);
            } elseif (array_key_exists($userprops[$ak]['prop_label'], $mappingarray)) {
                // old DUD found, replace it
                $userprops[$ak]['attribute_name'] = $mappingarray[$userprops[$ak]['prop_label']];
            } else {
                // user defined DUD found, do not touch it
                $userprops[$ak]['attribute_name'] = $userprops[$ak]['prop_label'];
            }
        }

        // One sql per user property to move all data from user_data table to the attributes table
        // This is the most efficient way to do this. During a test upgrade this took less than 0.3 secs for 6700
        // users and >15K of properties.
        foreach ($userprops as $userprop) {
            // Set cr_date and lu_date to now, cr_uid and lu_uid will be the uid of the user the attributes belong to
            $timestring = DateUtil::getDatetime();
            $sql = "INSERT INTO " . $objtable . " (" . $objcolumn['attribute_name'] . ",
                                               " . $objcolumn['object_type'] . ",
                                               " . $objcolumn['object_id'] . ",
                                               " . $objcolumn['value'] . ",
                                               " . $objcolumn['cr_date'] . ",
                                               " . $objcolumn['cr_uid'] . ",
                                               " . $objcolumn['lu_date'] . ",
                                               " . $objcolumn['lu_uid'] . ")
                SELECT '" . $userprop['attribute_name'] . "',
                       'users',
                       " . $udcolumn['uda_uid'] . ",
                       " . $udcolumn['uda_value'] . ",
                       '" . $timestring . "',
                       " . $udcolumn['uda_uid'] . ",
                       '" . $timestring . "',
                       " . $udcolumn['uda_uid'] . "
                FROM " . $udtable . "
                WHERE " . $udcolumn['uda_propid'] . "='" . $userprop['prop_id'] . "'";
            DBUtil::executeSQL($sql);
        }

        // done :-)
        return true;
    }

    /**
     * Migrate serialized data in users_temp.
     */
    public function upgrade_migrateSerialisedUserTemp()
    {
        $array = DBUtil::selectObjectArray('users_temp');
        foreach ($array as $obj) {
            if (DataUtil::is_serialized($obj['dynamics'])) {
                $obj['dynamics'] = serialize(DataUtil::mb_unserialize($obj['dynamics']));
            }
            DBUtil::updateObject($obj, 'users_temp', '', 'tid');
        }
    }
}