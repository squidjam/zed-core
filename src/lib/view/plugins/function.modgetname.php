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
 * Smarty function to the topmost module name
 *
 * This function currently returns the name of the current top-level
 * module, false if not in a module.
 *
 *
 * Available parameters:
 *   - assign:   If set, the results are assigned to the corresponding
 *               variable instead of printed out
 *
 * Example
 *   {modgetname|varprepfordisplay}
 *
 * @param array  $params  All attributes passed to this function from the template.
 * @param Smarty &$smarty Reference to the Smarty object.
 *
 * @return string The module variable.
 */
function smarty_function_modgetname ($params, &$smarty)
{
    $assign = isset($params['assign']) ? $params['assign'] : null;

    $result = ModUtil::getName();

    if ($assign) {
        $smarty->assign($assign, $result);
    } else {
        return $result;
    }
}