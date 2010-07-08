<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Multisites
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class Blocks_Block_Menutree extends Zikula_Block
{
    public function init()
    {
        SecurityUtil::registerPermissionSchema('menutree:menutreeblock:', 'Block title:Link name:');
    }

    public function info()
    {
        return array('text_type'      => 'menutree',
                'module'         => 'menutree',
                'text_type_long' => $this->__('Tree-like menu (menutree)'),
                'allow_multiple' => true,
                'form_content'   => false,
                'form_refresh'   => false,
                'show_preview'   => true,
                'admin_tableless' => true);
    }

    public function display($blockinfo)
    {
        // Security check
        if (!Securityutil::checkPermission('menutree:menutreeblock:', $blockinfo['title'] . '::', ACCESS_READ)) {
            return false;
        }

        // Get variables from content block
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // set cache id with user id (due to user oriented permissions)
        $this->view->cache_id = $blockinfo['bkey'].$blockinfo['bid'].'u'.UserUtil::getVar('uid');

        // template to use
        if (!isset($vars['menutree_tpl']) || empty($vars['menutree_tpl']) || !$this->view->template_exists($vars['menutree_tpl'])) {
            $vars['menutree_tpl'] = 'menutree/blocks_block_menutree.tpl';
        }

        //check if block is cached, if so - fetch cached tpl to aviod further proceedeing
        if ($this->view->is_cached($vars['menutree_tpl'])) {
            $blockinfo['content'] = $this->view->fetch($vars['menutree_tpl']);
            return BlockUtil::themeBlock($blockinfo);
        }

        // set default block vars
        $vars['menutree_content'] = isset($vars['menutree_content']) ? $vars['menutree_content'] : array();
        $vars['menutree_titles'] = isset($vars['menutree_titles']) ? $vars['menutree_titles'] : array();
        $vars['menutree_stylesheet'] = isset($vars['menutree_stylesheet']) ? $vars['menutree_stylesheet'] : '';
        $vars['menutree_editlinks'] = isset($vars['menutree_editlinks']) ? $vars['menutree_editlinks'] : false;

        // set current user lang
        $lang = ZLanguage::getLanguageCode();
        $deflang = 'en';

        if (!in_array($lang, array_keys(current($vars['menutree_content'])))) {
            $lang = $deflang;
        }

        if(!empty($vars['menutree_content'])) {
            // select current lang, check permissions for each item and exclude unactive nodes
            $newTree = array();
            $blocked = array();
            foreach($vars['menutree_content'] as $item) {
                $item = $item[$lang];
                // due to bug #9 we have to check two possible perms syntax
                $perms = !Securityutil::checkPermission('menutree:menutreeblock:',"$blockinfo[title]::$item[name]",ACCESS_READ) || !Securityutil::checkPermission('menutree:menutreeblock:',"$blockinfo[title]:$item[name]:",ACCESS_READ);
                if($perms || in_array($item['parent'], $blocked)) {
                    $blocked[] = $item['id'];
                } elseif ($item['state'] != 1) {
                    $blocked[] = $item['id'];
                } else {
                    // dynamic components
                    if(strpos($item['href'],'{ext:') === 0) {
                        $dynamic = explode(':', substr($item['href'], 1,  - 1));
                        $modname = $dynamic[1];
                        $func = $dynamic[2]; // plugin
                        $extrainfo = (isset($dynamic[3]) && !empty($dynamic[3])) ? $dynamic[3] : null;
                        if(!empty($modname) && !empty($func)) {
                            $args = array(
                                    'item' => $item,
                                    'lang' => $lang,
                                    'bid' => $blockinfo['bid'],
                                    'extrainfo' => $extrainfo,
                            );
                            $node = ModUtil::apiFunc($modname, 'menutree', $func, $args);
                            if(!is_array($node)) {
                                $node = array(array($lang => $item));
                            }
                        }
                    } else {
                        $node = array(array($lang => $item));
                    }
                    $newTree = array_merge($newTree,(array)$node);
                }
            }

            // bulid structured array
            $langs = array('ref' => $lang,
                    'list' => $lang,
                    'flat' => true);
            $newTree = $this->_decode_tree($newTree,$langs,$_parseURL = true);
        } else {
            $newTree = array();
        }

        // block title
        if (!empty($vars['menutree_titles'][$lang])) {
            $blockinfo['title'] = $vars['menutree_titles'][$lang];
        }

        // stylesheet
        if(file_exists($vars['menutree_stylesheet'])) {
            PageUtil::addVar('stylesheet', $vars['menutree_stylesheet']);
        }

        $this->view->assign('menutree_editlinks',$vars['menutree_editlinks'] && Securityutil::checkPermission('Blocks::', 'menutree:'.$blockinfo['title'].':'.$blockinfo['bid'], ACCESS_EDIT));
        $this->view->assign('menutree_content',$newTree);
        $this->view->assign('blockinfo', $blockinfo);

        $blockinfo['content'] = $this->view->fetch($vars['menutree_tpl']);

        return BlockUtil::themeBlock($blockinfo);
    }

    public function modify($blockinfo)
    {
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        // set some default vars
        $vars['isnew'] =                    empty($vars);
        $vars['menutree_content'] =         isset($vars['menutree_content']) ? $vars['menutree_content'] : array();
        $vars['menutree_tpl'] =             isset($vars['menutree_tpl']) ? $vars['menutree_tpl'] : '';
        $vars['menutree_stylesheet'] =      isset($vars['menutree_stylesheet']) ? $vars['menutree_stylesheet'] : '';
        $vars['menutree_stylesheet'] =      isset($vars['menutree_stylesheet']) ? $vars['menutree_stylesheet'] : '';
        $vars['menutree_linkclass'] =       isset($vars['menutree_linkclass']) ? $vars['menutree_linkclass'] : false;
        $vars['menutree_linkclasses'] =     isset($vars['menutree_linkclasses']) ? $vars['menutree_linkclasses'] : array();
        $vars['menutree_titles'] =          isset($vars['menutree_titles']) ? $vars['menutree_titles'] : array();
        $vars['menutree_editlinks'] =       isset($vars['menutree_editlinks']) ? $vars['menutree_editlinks'] : false;
        $vars['menutree_stripbaseurl'] =    isset($vars['menutree_stripbaseurl']) ? $vars['menutree_stripbaseurl'] : true;
        $vars['menutree_maxdepth'] =        isset($vars['menutree_maxdepth']) ? $vars['menutree_maxdepth'] : 0;
        $vars['oldlanguages'] =             isset($vars['oldlanguages']) ? $vars['oldlanguages'] : array();
        $vars['olddefaultanguage'] =        isset($vars['olddefaultanguage']) ? $vars['olddefaultanguage'] :'';

        // get list of languages
        $vars['languages'] = ZLanguage::getInstalledLanguageNames();
        $userlanguage = ZLanguage::getLanguageCode();

        // get default langs
        $vars['defaultanguage'] = !empty($blockinfo['language']) ? $blockinfo['language'] : $userlanguage;
        // rebuild langs array - default lang has to be first
        if(isset($vars['languages']) && count($vars['languages']) > 1) {
            $deflang[$vars['defaultanguage']] = $vars['languages'][$vars['defaultanguage']];
            unset($vars['languages'][$vars['defaultanguage']]);
            $vars['languages'] = array_merge($deflang,$vars['languages']);
            $vars['multilingual'] = true;
        } else {
            $vars['multilingual'] = false;
        }
        // check if there is allredy content
        if(empty($vars['menutree_content'])) {
            // no content - get list of menus to allow import
            $vars['menutree_menus'] = $this->_get_current_menus($blockinfo['bid']);
        } else {
            // get data to decode content
            $langs = array('list' => array_keys($vars['languages']),
                    'flat' => false);

            // are there new langs not present in current menu?
            // check if there are new languages not present in current menu
            // if so - need to set reference lang to copy initial menu items data
            if(count(array_diff($vars['languages'],$vars['oldlanguages'])) > 1) {
                // fisrt try current default lang
                if(in_array($vars['defaultanguage'],$vars['oldlanguages'])) {
                    $langs['ref'] = $vars['defaultanguage'];
                    // or user lang
                } elseif (in_array($userlanguage,$vars['oldlanguages'])) {
                    $langs['ref'] = $userlanguage;
                    // or old default lang
                } elseif (in_array($vars['olddefaultanguage'],$vars['languages'])) {
                    $langs['ref'] = $vars['olddefaultanguage'];
                    // it must be any language present in old and new lang list
                } else {
                    $langs['ref'] = current(array_intersect($vars['languages'], $vars['oldlanguages']));
                }
            }
            // decode tree array
            $vars['menutree_content'] = $this->_decode_tree($vars['menutree_content'],$langs);
        }

        // Create output object
        $this->view->setCaching(false);

        // get all templates and stylesheets.
        $vars['tpls'] = Blocks_MenutreeUtil::getTemplates();
        $vars['styles'] =  Blocks_MenutreeUtil::getStylesheets();
        $someThemes = $this->__('Only in some themes');
        $vars['somethemes'] = isset($vars['tpls'][$someThemes]) || isset($vars['styles'][$someThemes]) ? true : false;

        // template to use
        if (empty($vars['menutree_tpl']) || !$this->view->template_exists($vars['menutree_tpl'])) {
            $vars['menutree_tpl'] = 'menutree/blocks_block_menutree.tpl';
        }

        // prepare block titles array
        foreach(array_keys($vars['languages']) as $lang) {
            if (!array_key_exists($lang, $vars['menutree_titles'])) {
                $vars['menutree_titles'][$lang] = '';
            }
        }

        // for permissions settings get first supported permlevels
        $vars['permlevels']  = $this->_permlevels();

        // check if saved permlevels are correct
        $vars['menutree_titlesperms'] = !empty($vars['menutree_titlesperms']) ? $vars['menutree_titlesperms'] : 'ACCESS_EDIT';
        $vars['menutree_displayperms'] = !empty($vars['menutree_displayperms']) ? $vars['menutree_displayperms'] : 'ACCESS_EDIT';
        $vars['menutree_settingsperms'] = !empty($vars['menutree_settingsperms']) ? $vars['menutree_settingsperms'] : 'ACCESS_EDIT';

        // check user permissions for settings sections
        $useraccess = SecurityUtil::getSecurityLevel(SecurityUtil::getAuthInfo(), 'Blocks::', "$blockinfo[bkey]:$blockinfo[title]:$blockinfo[bid]");
        $vars['menutree_titlesaccess'] = $useraccess >= constant($vars['menutree_titlesperms']);
        $vars['menutree_displayaccess'] = $useraccess >= constant($vars['menutree_displayperms']);
        $vars['menutree_settingsaccess'] = $useraccess >= constant($vars['menutree_settingsperms']);
        $vars['menutree_adminaccess'] = $useraccess >= ACCESS_ADMIN;
        $vars['menutree_anysettingsaccess'] = $vars['menutree_adminaccess'] || $vars['menutree_titlesaccess'] || $vars['menutree_displayaccess'] || $vars['menutree_settingsaccess'];

        // check if the users wants to add a new link via the "Add current url" link in the block
        $addurl = FormUtil::getPassedValue('addurl', 0, 'GET');
        // or if we come from the normal "edit this block" link
        $fromblock = FormUtil::getPassedValue('fromblock', null, 'GET');

        $vars['redirect'] = '';
        $vars['menutree_newurl'] = '';
        if ($addurl == 1) {
            // set a marker for redirection later on
            $newurl = System::serverGetVar('HTTP_REFERER');
            $vars['redirect'] = urlencode($newurl);
            $newurl = str_replace(System::getBaseUrl(), '', $newurl);
            if (empty($newurl)) {
                $newurl = System::getHomepageUrl();
            }
            $vars['menutree_newurl'] = $newurl;
        } elseif (isset($fromblock)) {
            $vars['redirect'] = urlencode(System::serverGetVar('HTTP_REFERER'));
        }

        // assign all block variables
        $this->view->assign($vars);
        $this->view->assign('blockinfo', $blockinfo);

        // Return the output that has been generated by this function
        return $this->view->fetch('menutree/blocks_block_menutree_modify.tpl');
    }

    public function update($blockinfo)
    {
        // Get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);

        $this->view->setCaching(false);

        // check if import old menu
        $menutree_menus = FormUtil::getPassedValue('menutree_menus', 'null');

        if($menutree_menus != 'null') {
            $vars['menutree_content'] = $this->_import_menu($menutree_menus);
        } else {
            $vars['menutree_content'] = FormUtil::getPassedValue('menutree_content', '', 'POST');
            $vars['menutree_content'] = json_decode($vars['menutree_content'],true);
        }

        // get other form data
        $menutree_data = FormUtil::getPassedValue('menutree');

        $vars['menutree_tpl'] = isset($menutree_data['tpl']) ? $menutree_data['tpl'] : '';
        if (empty($vars['menutree_tpl']) || !$this->view->template_exists($vars['menutree_tpl'])) {
            $vars['menutree_tpl'] = 'menutree/blocks_block_menutree.tpl';
        }

        $vars['menutree_stylesheet'] = isset($menutree_data['stylesheet']) ? $menutree_data['stylesheet'] : '';
        if (empty($vars['menutree_stylesheet']) || $vars['menutree_stylesheet'] == 'null' || !file_exists($vars['menutree_stylesheet'])) {
            $vars['menutree_stylesheet'] = '';
        }

        $vars['menutree_titles'] = isset($menutree_data['titles']) ? $menutree_data['titles'] : array();

        $vars['menutree_linkclass'] = isset($menutree_data['linkclass']) ? (bool)$menutree_data['linkclass'] : false;
        // if class list is provided - rebuild array and fill empty entries
        if($vars['menutree_linkclass'] && isset($menutree_data['linkclasses'])) {
            foreach((array)$menutree_data['linkclasses'] as $k => $class) {
                if(empty($class['name'])) {
                    unset($menutree_data['linkclasses'][$k]);
                } elseif (empty($class['title'])) {
                    $menutree_data['linkclasses'][$k]['title'] = $class['name'];
                }
            }
            $vars['menutree_linkclasses'] = $menutree_data['linkclasses'];
            if(count($vars['menutree_linkclasses']) < 1) {
                $vars['menutree_linkclass'] = false;
            }
        }

        $vars['menutree_maxdepth'] = isset($menutree_data['maxdepth']) ? (int)$menutree_data['maxdepth'] : 0;
        $vars['menutree_editlinks'] = isset($menutree_data['editlinks']) ? (bool)$menutree_data['editlinks'] : false;
        $vars['menutree_stripbaseurl'] = isset($menutree_data['stripbaseurl']) ? (bool)$menutree_data['stripbaseurl'] : false;

        $vars['menutree_titlesperms'] = isset($menutree_data['titlesperms']) && array_key_exists($menutree_data['titlesperms'],$this->_permlevels()) ? $menutree_data['titlesperms'] : 'ACCESS_EDIT';
        $vars['menutree_displayperms'] = isset($menutree_data['displayperms']) && array_key_exists($menutree_data['displayperms'],$this->_permlevels()) ? $menutree_data['displayperms'] : 'ACCESS_EDIT';
        $vars['menutree_settingsperms'] = isset($menutree_data['settingsperms']) && array_key_exists($menutree_data['settingsperms'],$this->_permlevels()) ? $menutree_data['settingsperms'] : 'ACCESS_EDIT';

        if(empty($vars['menutree_content'])) {
            unset($vars['menutree_content']);
        } else {
            // check langs and save current langs list and current default lang
            $tmp = current($vars['menutree_content']);
            $vars['oldlanguages'] = array_keys($tmp);
            $vars['olddefaultanguage'] = $vars['oldlanguages'][0];

            // strip base url - if needed
            if($vars['menutree_stripbaseurl'] === true) {
                $baseurl = System::getBaseUrl();
                foreach($vars['menutree_content'] as $itemid => $item) {
                    foreach($item as $lang => $_item) {
                        // strip base url only when it occurs at the beginning of url and only once
                        if(strpos($_item['href'],$baseurl) === 0) {
                            $vars['menutree_content'][$itemid][$lang]['href'] = substr_replace($_item['href'], '', 0, strlen($baseurl));

                        }
                    }
                }
            }
        }

        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($vars);

        // clear the block cache
        // due to cache id containing user id we have to clear all block cache
        $this->view->clear_all_cache();

        return $blockinfo;
    }

    /**
     * Function to convert flat array to structured, tree-like array
     */
    private function _decode_tree($array,$langs,$_parseURL = false)
    {
        $tree = array();
        $map = array();

        if(!empty($langs['ref'])) {
            $reflang = $langs['ref'];
        } else {
            $reflang = $langs['list'][0];
        }

        foreach($array as $a) {
            $item = array();

            foreach((array)$langs['list'] as $lang) {
                if(empty($a[$lang])) {
                    if(!empty($a[$reflang])) {
                        $item[$lang] = $a[$reflang];
                    } else {
                        $item[$lang] = current($a);
                    }
                    $item[$lang]['state'] = 0;
                    $item[$lang]['lang'] = $lang;
                } else {
                    $item[$lang] = $a[$lang];
                }
                if ($_parseURL) {
                    $item[$lang]['href'] = $this->_parseUrl($item[$lang]['href']);
                }
                $item[$lang]['dynamic'] = strpos($item[$lang]['href'],'{menutree:') === 0;
            }
            if($langs['flat']) {
                $_node = array('item' => $item[$reflang], 'nodes' => array());
            } else {
                $_node = array('item' => $item, 'nodes' => array());
            }
            if($a[$reflang]['parent'] == 0) {
                $tree[$a[$reflang]['id']] = $_node;
                $path = null;
            } else {
                $path = $map[$a[$reflang]['parent']];
                $path[] = $a[$reflang]['parent'];
                $handle =& $tree;
                while (list($key, $value) = each($path)) {
                    if($value === 0) continue;
                    $handle =& $handle[$value]['nodes'];
                }
                $handle[$a[$reflang]['id']] = $_node;
            }
            $map[$a[$reflang]['id']] = $path;
        }
        return $tree;

    }
    /**
     * Prepare a menu item url
     * Copy of buildURL function from extmenu block
     */
    private function _parseUrl($url)
    {
        // allow a simple portable way to link to the home page of the site
        if ($url == '{homepage}') {
            $url = System::getBaseUrl();
        } elseif (!empty($url)) {
            switch ($url[0]) // Used to allow support for linking to modules with the use of bracket
            {
                case '[': // old style module link
                    {
                        $url = explode(':', substr($url, 1,  - 1));
                        $url = System::getVar('entrypoint', 'index.php') . '?name='.$url[0].(isset($url[1]) ? '&file='.$url[1]:'');
                        break;
                    }
                case '{': // new module link
                    {
                        $url = explode(':', substr($url, 1,  - 1));
                        // url[0] should be the module name
                        if (isset($url[0]) && !empty($url[0])) {
                            $modname = $url[0];
                            // default for params
                            $params = array();
                            // url[1] can be a function or function&param=value
                            if (isset($url[1]) && !empty($url[1])) {
                                $urlparts = explode('&', $url[1]);
                                $func = $urlparts[0];
                                unset($urlparts[0]);
                                if (count($urlparts) > 0) {
                                    foreach ($urlparts as $urlpart) {
                                        $part = explode('=', $urlpart);
                                        $params[trim($part[0])] = trim($part[1]);
                                    }
                                }
                            } else {
                                $func = 'main';
                            }
                            // addon: url[2] can be the type parameter, default 'user'
                            $type = (isset($url[2]) &&!empty($url[2])) ? $url[2] : 'user';
                            //  build the url
                            $url = ModUtil::url($modname, $type, $func, $params);
                        } else {
                            $url = System::getHomepageUrl();
                        }
                        break;
                    }
            }  // End Bracket Linking
        }

        return $url;
    }

    private function _permlevels()
    {
        return array('ACCESS_EDIT' => $this->__('Edit access'),
                'ACCESS_ADD' => $this->__('Add access'),
                'ACCESS_DELETE' => $this->__('Delete access'),
                'ACCESS_ADMIN' => $this->__('Admin access'));
    }

    /**
     * Get list of menus with type supported to import
     */
    private function _get_current_menus($bid)
    {
        $_menus = BlockUtil::getBlocksInfo();
        $menus = array();
        $supported = array('menu','extmenu','menutree','dynamenu');
        foreach($_menus as $menu) {
            if(in_array($menu['bkey'],$supported) && $menu['bid'] != $bid) {
                $menus[$menu['bid']] = $menu['title'];
            }
        }

        return $menus;
    }

    /**
     * Convert data of selected menu to menutree style
     * Used to import menus
     */
    private function _import_menu($bid)
    {
        if ((!isset($bid)) || (isset($bid) && !is_numeric($bid))) {
            return;
        }
        $menu = BlockUtil::getBlockInfo($bid);
        $menuVars = BlockUtil::varsFromContent($menu['content']);

        $userlanguage = ZLanguage::getLanguageCode();

        switch($menu['bkey']) {
            case 'menutree':
                $data = isset($menuVars['menutree_content']) ? $menuVars['menutree_content'] : array();
                break;
            case 'menu':
                if (isset($menuVars['content']) && !empty($menuVars['content'])) {
                    $reflang = $userlanguage;
                    $pid = 1;
                    $data = array();
                    $contentlines = explode('LINESPLIT', $menuVars['content']);
                    foreach ($contentlines as $lineno => $contentline) {
                        list($href, $name, $title) = explode('|', $contentline);
                        if(!empty($name)) {
                            $className = '';
                            $parent = 0;
                            $state = 1;
                            $lang = $reflang;
                            $id = $pid;
                            $data[$lineno][$reflang] = compact('href','name','title','className','parent','state','lang','lineno','id');
                            $pid++;
                        }
                    }
                    $langs = (array)$reflang;
                    $lineno++;
                }
                break;
            case 'extmenu':
                if (isset($menuVars['links']) && !empty($menuVars['links'])) {
                    $langs = array_keys($menuVars['links']);
                    $data = array();
                    foreach($langs as $lang) {
                        foreach($menuVars['links'][$lang] as $id => $link) {
                            $data[$id][$lang] = array(
                                    'id'        => $id + 1,
                                    'name'      => isset($link['name']) && !empty($link['name']) ? $link['name'] : $this->__('no name'),
                                    'href'      => isset($link['url']) ? $link['url'] : '',
                                    'title'     => isset($link['title']) ? $link['title'] : '',
                                    'className' => '',
                                    'state'     => isset($link['active']) && $link['active'] && $link['name'] ? 1 : 0,
                                    'lang'      => $lang,
                                    'lineno'    => $id,
                                    'parent'    => 0
                            );
                        }
                    }
                    ksort($data);
                    $pid = $id + 2;
                    $lineno = count($data);
                }
                break;
            // patch by Erik Spaan
            case 'dynamenu':
                if (isset($menu['content']) && !empty($menu['content'])) {
                    $reflang = $userlanguage;
                    $pid = 1;
                    $data = array();
                    $level = 1;
                    $block_params = explode('~~', $menu['content']);
                    // $block_content[0] contains the menu layout,
                    // 0 vertical menu, 1 vertical tree with folder icons, 2 vertical plain, 5 vertical tree without folder icons
                    // 3 horizontal menu, 5 horizontal plain
                    // Every menu line contains:   LevelInDots | Name | <URL> | <Title> | <Icon> | <target> |
                    // .|Home|index.php|Homepage|home.gif||
                    // ..|Recent News|[News]|Actueel Nieuws||_new|
                    // Target is converted into the classname target<target> and the icon is not used ATM
                    // Reshape the contentlines into an array with all menu items
                    $contentlines = preg_split("[\n]", $block_params[3]);
                    $dynamenu = array();
                    foreach ($contentlines as $menuid => $contentline) {
                        list($level, $name, $href, $title, $icon, $target) = explode('|', $contentline);
                        $dynamenu[$menuid] = array(
                                'level'     => strlen($level)-1,
                                'name'      => isset($name) && !empty($name) ? $name : $this->__('no name'),
                                'icon'      => isset($icon) && !empty($icon) ? "<img src=\"images/dynamenu/$icon\" alt=\"$icon\"width=\"16\" height=\"16\" />" : '',
                                'href'      => isset($href) && !empty($href) ? $href : '#',
                                'title'     => isset($title) && !empty($title) ? $title : '',
                                'classname' => !empty($target) ? 'target'.$target : ''
                        );
                    }

                    $dmid = 0;
                    $data = $this->_parse_dynamic($dynamenu, $dmid, $reflang);
                    ksort($data);
                    $langs = (array)$reflang;
                    $lineno = count($data);
                }
                break;
        }

        if (!empty($menuVars['displaymodules'])) {
            $mods = ModUtil::getUserMods();

            if(is_array($mods) && count($mods)>0) {
                foreach($mods as $mod) {
                    switch($mod['type']) {
                        case 1:
                            $tmp = array('name'   => $mod['displayname'],
                                    'href'    => System::getVar('entrypoint', 'index.php') . '?name=' . DataUtil::formatForDisplay($mod['directory']),
                                    'title'  => $mod['description']);
                            break;
                        case 2:
                        case 3:
                            $tmp = array('name'   => $mod['displayname'],
                                    'href'    => DataUtil::formatForDisplay(ModUtil::url($mod['name'], 'user', 'main')),
                                    'title'  => $mod['description']);
                            break;
                    }
                    foreach($langs as $lang) {
                        $tmp = array_merge($tmp, array('className' => '',
                                'parent' => 0,
                                'lang' => $lang,
                                'state' => 1,
                                'lineno' => $lineno,
                                'id' => $pid));
                        $tmparray[$lang] = $tmp;
                    }
                    $data[] = $tmparray;
                    $pid++;
                    $lineno++;
                }
            }
        }

        return $data;
    }

    /**
     * Convert the multi level dynamenu block into menutree datastructure (patch by Erik Spaan)
     */
    private function _parse_dynamic($dynamenu, &$id, $lang, $level=0, $parent=0)
    {
        $data = array();
        $lineno = 0;
        while ($id < count($dynamenu)) {
            if ($dynamenu[$id]['level'] == $level) {
                $data[$id][$lang] = array(
                        'id'        => $id + 1,
                        'name'      => $dynamenu[$id]['name'],
                        'href'      => $dynamenu[$id]['href'],
                        'title'     => $dynamenu[$id]['title'],
                        'className' => '',
                        'state'     => 1,
                        'lang'      => $lang,
                        'lineno'    => $lineno,
                        'parent'    => $parent
                );
            } elseif ($dynamenu[$id]['level'] > $level) {
                $data = array_merge($data, $this->_parse_dynamic($dynamenu, $id, $lang, $dynamenu[$id]['level'], $id));
            } elseif ($dynamenu[$id]['level'] < $level) {
                $id--;
                break;
            }
            $id++;
            $lineno++;
        }
        return $data;
    }
}