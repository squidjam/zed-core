<?php
/**
 * Copyright 2010 Zikula Foundation
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

/**
 * MultiHook Event Handler class.
 */
class MultiHookHandler extends Zikula_EventHandler
{
    /**
     * Setup handler definitions.
     *
     * @return void
     */
    protected function setupHandlerDefinitions()
    {
        $this->addHandlerDefinition('theme.init', 'handler');
    }

    /**
     * Event handler here.
     *
     * @param Zikula_Event $event Event object.
     *
     * @return void
     */
    public function handler(Zikula_Event $event)
    {
        // subject must be an instance of Theme class.
        $theme = $event->getSubject();
        if (!$theme instanceof Zikula_View_Theme) {
            return;
        }

        // register output filter to add MultiHook environment if requried
        if (ModUtil::available('MultiHook')) {
            $modinfo = ModUtil::getInfoFromName('MultiHook');
            if (version_compare($modinfo['version'], '5.0', '>=') == 1) {
                $theme->load_filter('output', 'multihook');
                ModUtil::apiFunc('MultiHook', 'theme', 'preparetheme');
            }
        }
    }
}