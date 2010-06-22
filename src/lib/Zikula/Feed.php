<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv2.1 (or at your option, any later version).
 * @package Zikula
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

require_once 'lib/vendor/SimplePie/simplepie.inc';

/**
 * ZFeed.
 */
class Zikula_Feed extends SimplePie
{
    /**
     * Class constructor.
     */
    public function __construct($feed_url = null, $cache_duration = null)
    {
        $cache_dir = CacheUtil::getLocalDir() . '/feeds';
        $this->SimplePie($feed_url, $cache_dir, $cache_duration);
    }
}