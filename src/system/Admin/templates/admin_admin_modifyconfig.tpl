{gt text="Settings" assign=templatetitle}
{include file="admin_admin_menu.htm"}
{ajaxheader modname=Admin filename=admin_admin_modifyconfig.js noscriptaculous=true effects=true}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=configure.gif set=icons/large alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>
    <form class="z-form" action="{modurl modname="Admin" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Admin"}" />
            <fieldset>
                <legend>{gt text="General settings"}</legend>
                <div class="z-formrow">
                    <label for="admin_ignoreinstallercheck">{gt text="Ignore check for installer"}</label>
                    {if $modvars.ignoreinstallercheck eq 1}
                    <input id="admin_ignoreinstallercheck" name="modvars[ignoreinstallercheck]" type="checkbox" value="1" checked="checked" />
                    {else}
                    <input id="admin_ignoreinstallercheck" name="modvars[ignoreinstallercheck]" type="checkbox" value="1" />
                    {/if}
                    <div id="admin_ignoreinstallercheck_warning">
                        <div class="z-warningmsg">{gt text="Warning! Only enable the above option if this site is isolated from the Internet, otherwise security could be endangered if you omit to remove the Installer script from the site root and are not prompted to do so."}</div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>{gt text="Display settings"}</legend>
                <div class="z-formrow">
                    <label for="admin_graphic">{gt text="Display icons"}</label>
                    {if $modvars.admingraphic eq 1}
                    <input id="admin_graphic" name="modvars[admingraphic]" type="checkbox" value="1" checked="checked" />
                    {else}
                    <input id="admin_graphic" name="modvars[admingraphic]" type="checkbox" value="1" />
                    {/if}
                </div>
                <div class="z-formrow">
                    <label for="admin_moduledesc">{gt text="Display description and context menu"}</label>
                    {if $modvars.moduledescription eq 1}
                    <input id="admin_moduledesc" name="modvars[moduledescription]" type="checkbox" value="1" checked="checked" />
                    {else}
                    <input id="admin_moduledesc" name="modvars[moduledescription]" type="checkbox" value="1" />
                    {/if}
                </div>
                <div class="z-formrow">
                    <label for="admin_displaynametype">{gt text="Form of display for module names"}</label>
                    <select id="admin_displaynametype" name="modvars[displaynametype]">
                        <option value="1" {if $modvars.displaynametype eq 1}selected="selected"{/if}>{gt text="Display name"}</option>
                        <option value="2" {if $modvars.displaynametype eq 2}selected="selected"{/if}>{gt text="Internal name"}</option>
                        <option value="3" {if $modvars.displaynametype eq 3}selected="selected"{/if}>{gt text="Show both internal name and display name"}</option>
                    </select>
                </div>
                <div class="z-formrow">
                    <label for="admin_itemsperpage">{gt text="Modules per page in module categories list"}</label>
                    <input id="admin_itemsperpage" name="modvars[itemsperpage]" type="text" size="3" maxlength="3" value="{$modvars.itemsperpage|safetext}" />
                </div>
                <div class="z-formrow">
                    <label for="admin_modulesperrow">{gt text="Modules per row in admin panel"}</label>
                    <input id="admin_modulesperrow" name="modvars[modulesperrow]" type="text" size="3" maxlength="3" value="{$modvars.modulesperrow|safetext}" />
                </div>
                <div class="z-formrow">
                    <label for="admintheme">{gt text="Theme to use"}</label>
                    <select id="admintheme" name="modvars[admintheme]">
                        <option value="">{gt text="Use site's theme"}</option>
                        {html_select_themes state=PNTHEME_STATE_ACTIVE filter=PNTHEME_FILTER_ADMIN selected=$modvars.admintheme}
                    </select>
                </div>
                <div class="z-formrow">
                    <label for="admin_skin">{gt text="Style sheet to use"}</label>
                    {html_select_modulestylesheets name=modvars[modulestylesheet] modname=Admin selected=$modvars.modulestylesheet id=admin_skin exclude="admin.css,admin_dark.css"}
                </div>
                <div class="z-formrow">
                    <label for="admin_startcategory">{gt text="Category initially selected"}</label>
                    <select id="admin_startcategory" name="modvars[startcategory]">
                        {section name=category loop=$categories}
                        {if  $modvars.startcategory eq $categories[category].cid}
                        <option value="{$categories[category].cid|safetext}" selected="selected">{$categories[category].catname|safetext}</option>
                        {else}
                        <option value="{$categories[category].cid|safetext}">{$categories[category].catname|safetext}</option>
                        {/if}
                        {/section}
                    </select>
                </div>
            </fieldset>
            <fieldset>
                <legend>{gt text="Modules categorisation"}</legend>
                <div class="z-formrow">
                    <label for="admin_defaultcategory">{gt text="Default category for newly-added modules"}</label>
                    <select id="admin_defaultcategory" name="modvars[defaultcategory]">
                        {section name=category loop=$categories}
                        {if  $modvars.defaultcategory eq $categories[category].cid}
                        <option value="{$categories[category].cid|safetext}" selected="selected">{$categories[category].catname|safetext}</option>
                        {else}
                        <option value="{$categories[category].cid|safetext}">{$categories[category].catname|safetext}</option>
                        {/if}
                        {/section}
                    </select>
                </div>
                {section name=modulecategory loop=$modulecategories}
                <div class="z-formrow">
                    <label for="admin_{$modulecategories[modulecategory].name}">{$modulecategories[modulecategory].displayname}</label>
                    <select id="admin_{$modulecategories[modulecategory].name}" name="adminmods[{$modulecategories[modulecategory].name|safetext}]">
                        {section name=category loop=$categories}
                        {if  $modulecategories[modulecategory].category eq $categories[category].cid}
                        <option value="{$categories[category].cid|safetext}" selected="selected">{$categories[category].catname|safetext}</option>
                        {else}
                        <option value="{$categories[category].cid|safetext}">{$categories[category].catname|safetext}</option>
                        {/if}
                        {/section}
                    </select>
                </div>
                {/section}
            </fieldset>

            {modcallhooks hookobject=module hookaction=modifyconfig module=Admin}
            <div class="z-buttons z-formbuttons">
                {button src=button_ok.gif set=icons/extrasmall __alt="Save" __title="Save" __text="Save"}
                <a href="{modurl modname=Admin type=admin func=view}" title="{gt text="Cancel"}">{img modname=core src=button_cancel.gif set=icons/extrasmall __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                <a class="z-btblue" href="{modurl modname=Admin type=admin func=help fragment=modifyconfig}" title="{gt text="Help"}">{img modname=core src=agt_support.gif set=icons/extrasmall __alt="Help" __title="Help"} {gt text="Help"}</a>
            </div>
        </div>
    </form>
</div>