<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Form
 * @subpackage Form_Plugin_ContextMenu
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Context menu reference
 *
 * This plugin adds a menu reference (could also be called a "placeholder").
 */
class Form_Plugin_ContextMenu_Reference extends Form_Plugin
{
    /**
     * URL to the item image.
     *
     * @var string
     */
    public $imageURL;

    /**
     * Menu ID.
     *
     * @var string
     */
    public $menuId;

    /**
     * Context menu command argument.
     *
     * @var string
     */
    public $commandArgument;

    /**
     * Get filename of this file.
     *
     * @return string
     */
    function getFilename()
    {
        return __FILE__;
    }

    /**
     * Render event handler.
     *
     * @param Form_View $render Reference to Form render object.
     *
     * @return string The rendered output
     */
    function render($render)
    {
        $imageURL = ($this->imageURL == null ? 'images/icons/extrasmall/tab_right.gif' : $this->imageURL);

        $menuPlugin = $render->getPluginById($this->menuId);
        $menuId = $menuPlugin->id;
        $html = "<img src=\"{$imageURL}\" alt=\"\" class=\"contextMenu\" onclick=\"Form.contextMenu.showMenu(event, '{$menuId}', '{$this->commandArgument}')\" />";

        return $html;
    }
}
