<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula_View
 * @subpackage Template_Plugins
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Smarty function to get the language direction.
 *
 * Example
 * <html dir="{langdirection}">
 *
 * @param array  $params  All attributes passed to this function from the template.
 * @param object &$smarty Reference to the Smarty object.
 *
 * @return   string   the language direction
 */
function smarty_function_langdirection($params, &$smarty)
{
    return ZLanguage::getDirection();
}