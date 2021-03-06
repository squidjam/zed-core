{include file='settings_admin_menu.tpl'}
{ajaxheader modname=Settings filename=settings_admin_modifyconfig.js noscriptaculous=true effects=true}
{pageaddvar name="javascript" value="javascript/texpand.js"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname="core" src="configure.gif" set="icons/large" __alt="Settings"}</div>
    <h2>{gt text="Main settings"}</h2>
    <form class="z-form" action="{modurl modname="Settings" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Settings"}" />
            <fieldset>
                <legend>{gt text="Main info"}</legend>
                <div class="z-formrow">
                    <label for="settings_sitename">{gt text="Site name"}</label>
                    <input id="settings_sitename" type="text" name="settings[sitename]" value="{$modvars.ZConfig.sitename|safetext}" size="50" maxlength="100" />
                </div>
                <div class="z-formrow">
                    <label for="settings_slogan">{gt text="Description line"}</label>
                    <input id="settings_slogan" type="text" name="settings[slogan]" value="{$modvars.ZConfig.slogan|safetext}" size="50" maxlength="100" />
                </div>
                <div class="z-formrow">
                    <label for="settings_adminmail">{gt text="Admin's e-mail address"}</label>
                    <input id="settings_adminmail" type="text" name="settings[adminmail]" value="{$modvars.ZConfig.adminmail|safetext}" size="30" maxlength="100" />
                </div>
                <div class="z-formrow">
                    <label for="settings_siteoff">{gt text="Disable site"}</label>
                    <div id="settings_siteoff">
                        <input id="settings_siteoff_yes" type="radio" name="settings[siteoff]" value="1" {if $modvars.ZConfig.siteoff eq 1}checked="checked"{/if} />
                        <label for="settings_siteoff_yes">{gt text="Yes"}</label>
                        <input id="settings_siteoff_no" type="radio" name="settings[siteoff]" value="0" {if $modvars.ZConfig.siteoff eq 0}checked="checked"{/if} />
                        <label for="settings_siteoff_no">{gt text="No"}</label>
                    </div>
                </div>
                <div id="settings_siteoff_container">
                    <div class="z-formrow">
                        <label for="settings_siteoffreason">{gt text="Reason for disabling site"}</label>
                        <textarea id="settings_siteoffreason" class="z_texpand" name="settings[siteoffreason]" cols="50" rows="5">{$modvars.ZConfig.siteoffreason|safetext}</textarea>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>{gt text="Meta tag settings"}</legend>
                <div class="z-formrow">
                    <label for="settings_defaultpagetitle">{gt text="Default page title"}</label>
                    <input id="settings_defaultpagetitle" type="text" name="settings[defaultpagetitle]" value="{$modvars.ZConfig.defaultpagetitle|safetext}" size="50" maxlength="255" />
                </div>
                <div class="z-formrow">
                    <label for="settings_defaultmetadescription">{gt text="Default meta description"}</label>
                    <input id="settings_defaultmetadescription" type="text" name="settings[defaultmetadescription]" value="{$modvars.ZConfig.defaultmetadescription|safetext}" size="50" maxlength="255" />
                </div>
                <div id="settings_keywords_container">
                    <div class="z-formrow">
                        <label for="settings_metakeywords">{gt text="Default meta keywords"}</label>
                        <textarea id="settings_metakeywords" class="z_texpand" name="settings[metakeywords]" cols="60" rows="5">{$modvars.ZConfig.metakeywords|safetext}</textarea>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>{gt text="Start page settings"}</legend>
                <div class="z-formrow">
                    <label for="settings_startpage">{gt text="Start module"}</label>
                    <select id="settings_startpage" name="settings[startpage]">
                        <option value="">{gt text="No start module (static frontpage)"}</option>
                        {html_select_modules selected=$modvars.ZConfig.startpage type=user}
                    </select>
                    <em class="z-formnote">{gt text="('index.php' points to this)"}</em>
                </div>
                <div class="z-formrow">
                    <label for="settings_starttype">{gt text="Start function type"}</label>
                    <input id="settings_starttype" type="text" name="settings[starttype]" value="{$modvars.ZConfig.starttype|safetext}" size="10" maxlength="10" />
                </div>
                <div class="z-formrow">
                    <label for="settings_startfunc">{gt text="Start function"}</label>
                    <input id="settings_startfunc" type="text" name="settings[startfunc]" value="{$modvars.ZConfig.startfunc|safetext}" size="20" maxlength="40" />
                </div>
                <div class="z-formrow">
                    <label for="settings_startargs">{gt text="Start function arguments"}</label>
                    <input id="settings_startargs" type="text" name="settings[startargs]" value="{$modvars.ZConfig.startargs|safetext}" size="20" maxlength="60" />
                    <em class="z-formnote">{gt text="(Comma-separated)"}</em>
                </div>
                <div class="z-formrow">
                    <label for="settings_entrypoint">{gt text="Site entry point"}</label>
                    <input id="settings_entrypoint" type="text" name="settings[entrypoint]" value="{$modvars.ZConfig.entrypoint|safetext}" size="20" maxlength="60" />
                    <em class="z-formnote">{gt text="(Default: index.php)"}</em>
                    <p class="z-formnote z-informationmsg">{gt text="Notice: The entry point file must be present in the Zikula root directory before you set it here as your site's start page."}</p>
                </div>
            </fieldset>
            <fieldset>
                <legend>{gt text="General settings"}</legend>
                <div class="z-formrow">
                    <label for="settings_usecompression">{gt text="Activate compression"}</label>
                    <div id="settings_usecompression">
                        <input id="UseCompression1" type="radio" name="settings[UseCompression]" value="1" {if $modvars.ZConfig.UseCompression eq 1}checked="checked"{/if} />
                        <label for="UseCompression1">{gt text="Yes"}</label>
                        <input id="UseCompression0" type="radio" name="settings[UseCompression]" value="0" {if $modvars.ZConfig.UseCompression eq 0}checked="checked"{/if} />
                        <label for="UseCompression0">{gt text="No"}</label>
                    </div>
                </div>
                <div class="z-formrow">
                    <label for="settings_profilemodule">{gt text="Module used for managing user profiles"}</label>
                    <select id="settings_profilemodule" name="settings[profilemodule]">
                        <option value="">{gt text="No user profiles"}</option>
                        {html_select_modules selected=$modvars.ZConfig.profilemodule type="profile"}
                    </select>
                </div>
                <div class="z-formrow">
                    <label for="settings_messagemodule">{gt text="Module used for private messaging"}</label>
                    <select id="settings_messagemodule" name="settings[messagemodule]">
                        <option value="">{gt text="No private messaging"}</option>
                        {html_select_modules selected=$modvars.ZConfig.messagemodule type="message"}
                    </select>
                </div>
                <div class="z-formrow">
                    <label for="settings_ajaxtimeout">{gt text="Time-out for Ajax connections"}</label>
                    <input id="settings_ajaxtimeout" name="settings[ajaxtimeout]" value="{$modvars.ZConfig.ajaxtimeout}" />
                    <em class="z-formnote">{gt text="(in milliseconds, default 5000 = 5 seconds)"}</em>
                    <p class="z-formnote z-informationmsg">{gt text="Notice: Increase this value if mobile appliances experience problems with using the site."}</p>
                </div>
            </fieldset>
            <fieldset>
                <legend>{gt text="Permalinks settings"}</legend>
                <p class="z-warningmsg">{gt text="Notice: The following settings will rewrite your permalinks. Sometimes, international characters like 'ñ' and 'ß' may be re-encoded by your browser. Although this is technically the correct action, it may not be aesthetically pleasing.  These settings allow you to replace those characters, using a pair of comma-separated lists. The two fields below should resemble the examples provided: The first element of 'List to search for' will replace the first element in the 'List to replace with' and so on. In the example below, 'À' would be replace with 'A', and 'Á' with 'A'. If you do not want to use this feature, leave both fields blank."}</p>
                <div class="z-formrow">
                    <label for="settings_permasearch">{gt text="List to search for"} </label>
                    <input id="settings_permasearch" name="settings[permasearch]" value="{$modvars.ZConfig.permasearch}" size="60" /><br />
                    <label for="settings_permasearch_default">{gt text="Default"}</label>
                    <input id="settings_permasearch_default" type="text" readonly="readonly" class="readonly" value="{gt text="À,Á,Â,Ã,Å,à,á,â,ã,å,Ò,Ó,Ô,Õ,Ø,ò,ó,ô,õ,ø,È,É,Ê,Ë,è,é,ê,ë,Ç,ç,Ì,Í,Î,Ï,ì,í,î,ï,Ù,Ú,Û,ù,ú,û,ÿ,Ñ,ñ,ß,ä,Ä,ö,Ö,ü,Ü"}" />
                </div>
                <div class="z-formrow">
                    <label for="settings_permareplace">{gt text="List to replace with"}</label>
                    <input id="settings_permareplace" name="settings[permareplace]" value="{$modvars.ZConfig.permareplace}" size="60" /><br />
                    <label for="settings_permareplace_default">{gt text="Default"}</label>
                    <input id="settings_permareplace_default" type="text" readonly="readonly" class="readonly" value="{gt text="A,A,A,A,A,a,a,a,a,a,O,O,O,O,O,o,o,o,o,o,E,E,E,E,e,e,e,e,C,c,I,I,I,I,i,i,i,i,U,U,U,u,u,u,y,N,n,s,a,A,o,O,u,U"}" />
                </div>
            </fieldset>
            <fieldset>
                <legend>{gt text="Short URL settings"}</legend>
                <div class="z-formrow">
                    <label for="settings_shorturls">{gt text="Enable short URLs"}</label>
                    <div id="settings_shorturls">
                        <input id="settings_shorturls_yes" type="radio" name="settings[shorturls]" value="1" {if $modvars.ZConfig.shorturls eq 1}checked="checked"{/if} />
                        <label for="settings_shorturls_yes">{gt text="Yes"}</label>
                        <input id="settings_shorturls_no" type="radio" name="settings[shorturls]" value="0" {if $modvars.ZConfig.shorturls eq 0}checked="checked"{/if} />
                        <label for="settings_shorturls_no">{gt text="No"}</label>
                    </div>
                </div>
                <div id="settings_shorturls_container">
                    <p class="z-formnote z-informationmsg">{gt text="Notice: If running PHP as CGI, you must ensure the line 'cgi.fix_pathinfo = 0' is present in your php.ini configuration file for short URLs to function correctly."}</p>
                    <div class="z-formrow">
                        <label for="settings_shorturlstype">{gt text="Type of URLs generated"}</label>
                        <div id="settings_shorturlstype">
                            {if $modvars.ZConfig.shorturlstype eq 0}
                            <input id="settings_shorturlstype_directory" type="radio" name="settings[shorturlstype]" value="0" checked="checked" />
                            <label for="settings_shorturlstype_directory">{gt text="Directory"}</label>
                            <input id="settings_shorturlstype_file" type="radio" name="settings[shorturlstype]" value="1" />
                            <label for="settings_shorturlstype_file">{gt text="File"}</label>
                            {else}
                            <input id="settings_shorturlstype_directory" type="radio" name="settings[shorturlstype]" value="0" />
                            <label for="settings_shorturlstype_directory">{gt text="Directory"}</label>
                            <input id="settings_shorturlstype_file" type="radio" name="settings[shorturlstype]" value="1" checked="checked" />
                            <label for="settings_shorturlstype_file">{gt text="File"}</label>
                            {/if}
                        </div>
                    </div>
                    <div id="settings_shorturlsstripentrypoint_container" class="z-formrow">
                        <label for="settings_shorturlsstripentrypoint">{gt text="Strip entry point from directory-based URLs"}</label>
                        <div id="settings_shorturlsstripentrypoint">
                            <input id="shorturlsstripentrypoint1" type="radio" name="settings[shorturlsstripentrypoint]" value="1" {if $modvars.ZConfig.shorturlsstripentrypoint eq 1}checked="checked"{/if} />
                            <label for="shorturlsstripentrypoint1">{gt text="Yes"}</label>
                            <input id="shorturlsstripentrypoint0" type="radio" name="settings[shorturlsstripentrypoint]" value="0" {if $modvars.ZConfig.shorturlsstripentrypoint eq 0}checked="checked"{/if} />
                            <label for="shorturlsstripentrypoint0">{gt text="No"}</label>
                        </div>
                    </div>
                    <div id="settings_shorturlsseparator_container" class="z-formrow">
                        <label for="settings_shorturlsseparator">{gt text="Separator for permalink titles"}</label>
                        <input id="settings_shorturlsseparator" size="1" maxlength="1" name="settings[shorturlsseparator]" value="{$modvars.ZConfig.shorturlsseparator}" />
                    </div>
                    <div id="settings_shorturlsext_container" class="z-formrow">
                        <label for="settings_shorturlsext">{gt text="Extension for file-based URLs"}</label>
                        <input id="settings_shorturlsext" name="settings[shorturlsext]" value="{$modvars.ZConfig.shorturlsext}" />
                    </div>
                    <div id="settings_shorturls_defaultmodule_container" class="z-formrow">
                        <label for="settings_shorturls_defaultmodule">{gt text="Module to use when permalink contains no module name"}</label>
                        <select id="settings_shorturls_defaultmodule" name="settings[shorturlsdefaultmodule]">
                            <option value="">{gt text="No default module"}</option>
                            {html_select_modules selected=$modvars.ZConfig.shorturlsdefaultmodule type=user}
                        </select>
                    </div>
                    <div id="settings_shorturlsextwarning_container">
                        <p class="z-warningmsg">{gt text="Notice: You must place an '.htaccess' file in the site root directory if you set 'Type of URLs generated' to 'File', or if you set 'Strip entry point from directory-based URLs' to 'Yes', or if a permalink title separator is used. Copies of the appropriate '.htaccess' file to use for each case can be found in the 'docs' documentation directory."}</p>
                    </div>
                </div>
            </fieldset>

            <div class="z-buttons z-formbuttons">
                {button src="button_ok.gif" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
                <a href="{modurl modname=Settings type=admin}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.gif" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>
        </div>
    </form>
</div>
