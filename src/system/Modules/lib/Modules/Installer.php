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

class Modules_Installer extends Zikula_Installer
{
    /**
     * initialise the Modules module
     *
     * This function is only ever called once during the lifetime of a particular
     * module instance.
     * This function MUST exist in the pninit file for a module
     *
     * @return       bool       true on success, false otherwise
     */
    public function install()
    {
        // modules
        if (!DBUtil::createTable('modules')) {
            return false;
        }

        // module_vars
        if (!DBUtil::createTable('module_vars')) {
            return false;
        }

        // hooks
        if (!DBUtil::createTable('hooks')) {
            return false;
        }

        // module_deps
        if (!DBUtil::createTable('module_deps')) {
            return false;
        }

        // populate default data
        $this->defaultdata();
        $this->setVar('itemsperpage', 25);

        // Initialisation successful
        return true;
    }

    /**
     * upgrade the module from an old version
     *
     * This function must consider all the released versions of the module!
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param        string   $oldVersion   version number string to upgrade from
     * @return       mixed    true on success, last valid version string or false if fails
     */
    public function upgrade($oldversion)
    {
        // Upgrade dependent on old version number
        switch ($oldversion)
        {
            case '3.7':
                // legacy is no longer supported
                System::delVar('loadlegacy');
                // future upgrade routines
        }

        // Update successful
        return true;
    }

    /**
     * delete the modules module
     *
     * This function is only ever called once during the lifetime of a particular
     * module instance
     * This function MUST exist in the pninit file for a module
     *
     * Since the modules module should never be deleted we'all always return false here
     * @return       bool       false
     */
    public function uninstall()
    {
        // Deletion not allowed
        return false;
    }

    /**
     * create the default data for the Modules module
     *
     * This function is only ever called once during the lifetime of a particular
     * module instance
     *
     * @return       bool       false
     */
    public function defaultdata()
    {
        $modversion = array();
        include 'version.php';
        // modules module
        $modversion['name']          = 'Modules';
        $modversion['type']          = ModUtil::TYPE_SYSTEM;
        $modversion['displayname']   = __('Modules manager') ;
        $modversion['description']   = __('Provides support for modules, and incorporates an interface for adding, removing and administering core system modules and add-on modules.');
        //! module name that appears in URL
        $modversion['url']            = __('modules');
        $modversion['regid']         = 1;
        $modversion['directory']     = 'Modules';
        $modversion['admin_capable'] = 1;
        $modversion['user_capable']  = 0;
        $modversion['state']         = ModUtil::STATE_ACTIVE;

        DBUtil::insertObject($modversion, 'modules');
    }

    /**
     * update the default data for the Modules module
     *
     * This function is only ever called once during the lifetime of a particular
     * module instance
     *
     * @author       Frank Schummertz
     * @return       none
     */
    public function updatedefaultdata($lang)
    {
        // set the default data for the Modules module

        $dbtables = DBUtil::getTables();
        $modcolumn = $dbtables['modules_column'];

        $where = 'WHERE '.$modcolumn['name'].'=\'Modules\'';
        $modversion = DBUtil::selectObject('modules', $where);
        include 'version.php';
        $modversion['admin_capable']   = 1;
        $modversion['user_capable']    = 0;
        $modversion['profile_capable'] = 0;
        $modversion['message_capable'] = 0;
        $modversion['state']           = 3;
        DBUtil::updateObject($record, 'modules');
    }
}