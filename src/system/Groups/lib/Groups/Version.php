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

class Groups_Version extends Zikula_Version
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = $this->__('Groups manager');
        $meta['description']    = $this->__('Provides support for user groups, and incorporates an interface for adding, removing and administering them.');
        //! module name that appears in URL
        $meta['url']            = $this->__('groups');
        $meta['version']        = '2.3.2';
        $meta['securityschema'] = array('Groups::' => 'Group ID::');
        return $meta;
    }
}