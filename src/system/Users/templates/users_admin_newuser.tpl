{ajaxheader modname='Users' filename='users_newuser.js' noscriptaculous=true effects=true}
{ajaxheader modname='Users' filename='users_admin_newuser.js' noscriptaculous=true effects=true}
{gt text='Create new user' assign='templatetitle'}

{include file='users_admin_menu.htm'}
<a id="users_formtop"></a>
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=filenew.gif set=icons/large alt=$templatetitle}</div>

    <h2>{$templatetitle}</h2>
    <p class="z-warningmsg">{gt text="The items that are marked with an asterisk ('*') are required entries."}</p>

    <div id="users_errormessages_div" class="z-errormsg{if empty($errorMessages)} z-hide{/if}">
        <p class="z-sub">Please correct the following items:</p>
        <ul id="users_errormessages">
        {foreach from=$errorMessages item='message'}
            <li>{$message}</li>
        {/foreach}
        </ul>
    </div>

    <form id="users_newuser" class="z-form" action="{modurl modname='Users' type='admin' func='registerNewUser'}" method="post">
        <div>
        <input type="hidden" id="users_authid" name="authid" value="{insert name='generateauthkey' module='Users'}" />
        <input type="hidden" id="users_checkmode" name="checkmode" value="new" />
        <input type="hidden" id="users_reginfo_agreetoterms" name="reginfo[agreetoterms]" value="{if !$userMustAccept}1{else}0{/if}" />
        <fieldset>
            <legend>{gt text='New user account'}</legend>
            <div class="z-formrow">
                <label for="users_reginfo_uname">{gt text='User name'}<span class="z-mandatorysym">{gt text="*"}</span></label>
                <input id="users_reginfo_uname"{if isset($errorFields.reginfo_uname)} class="errorrequired"{/if} type="text" name="reginfo[uname]" size="21" maxlength="25" value="{$reginfo.uname|default:''}" />
            </div>
            <div class="z-formrow">
                <label for="users_reginfo_email">{gt text='E-mail address'}<span class="z-mandatorysym">{gt text="*"}</span></label>
                <input id="users_reginfo_email"{if isset($errorFields.reginfo_email) || isset($errorFields.emailagain)} class="errorrequired"{/if} type="text" name="reginfo[email]" size="21" maxlength="60" value="{$reginfo.email|default:''}" />
            </div>
            <div class="z-formrow">
                <label for="users_emailagain">{gt text='E-mail address (repeat for verification)'}<span class="z-mandatorysym">{gt text="*"}</span></label>
                <input id="users_emailagain"{if isset($errorFields.emailagain)} class="errorrequired"{/if} type="text" name="emailagain" size="21" maxlength="60" value="{$emailagain|default:''}" />
            </div>
            <div id="users_setpass_container" class="z-formrow{if empty($reginfo.pass) && !isset($errorFields.reginfo_pass) && !isset($errorFields.passagain)} z-hide{/if}">
                <label for="users_setpass">{gt text="Set the user's password now?"}</label>
                <div id="users_setpass">
                    <input id="users_setpass_yes" type="radio" name="setpass" value="1" {if !empty($reginfo.pass) || isset($errorFields.reginfo_pass) || isset($errorFields.passagain)} checked="checked"{/if} />
                    <label for="users_setpass_yes">{gt text="Yes"}</label>
                    <input id="users_setpass_no" type="radio" name="setpass" value="0" {if empty($reginfo.pass) && !isset($errorFields.reginfo_pass) && !isset($errorFields.passagain)} checked="checked"{/if} />
                    <label for="users_setpass_no">{gt text="No"}</label>
                </div>
            </div>
            <div id="users_setpass_yes_wrap">
                <div class="z-formrow">
                    <label for="users_reginfo_pass">{gt text='Password'}<span class="z-mandatorysym">{gt text="*"}</span></label>
                    <input id="users_reginfo_pass"{if isset($errorFields.reginfo_pass) || isset($errorFields.passagain)} class="errorrequired"{/if} type="password" name="reginfo[pass]" size="21" maxlength="20" />
                    <em class="z-sub z-formnote">{gt text='Notice: The minimum length for user passwords is %s characters.' tag1=$modvars.minpass}</em>
                </div>
                <div class="z-formrow">
                    <label for="users_passagain">{gt text='Password (repeat for verification)'}<span class="z-mandatorysym">{gt text="*"}</span></label>
                    <input id="users_passagain"{if isset($errorFields.passagain)} class="errorrequired"{/if} type="password" name="passagain" size="21" maxlength="20" />
                </div>
                <div id="users_sendpass_container" class="z-formrow">
                    <label for="users_sendpass">{gt text="Send password via e-mail?"}</label>
                    <div id="users_sendpass">
                        <input id="users_sendpass_yes" type="radio" name="sendpass" value="1" {if !empty($sendpass)} checked="checked"{/if} />
                        <label for="users_sendpass_yes">{gt text="Yes"}</label>
                        <input id="users_sendpass_no" type="radio" name="sendpass" value="0" {if empty($sendpass)} checked="checked"{/if} />
                        <label for="users_sendpass_no">{gt text="No"}</label>
                    </div>
                    <p class="z-formnote z-warningmsg">{gt text="Sending a password via e-mail is considered unsafe. It is recommended that you provide the password to the user using a secure method of communication."}</p>
                </div>
            </div>
            <div id="users_setpass_no_wrap" class="z-formrow z-hide">
                <p class="z-formnote z-informationmsg">{gt text="The user's e-mail address will be verified. The user will create a password at that time."}</p>
            </div>
            <div id="users_usermustverify_wrap" class="z-formrow">
                <label for="users_usermustverify">{gt text="User's e-mail address must be verified (recommended)"}</label>
                <input id="users_usermustverify" type="checkbox" name="usermustverify"{if $usermustverify} checked="checked"{/if} />
            </div>
        </fieldset>

        {if $showProps}
            {modfunc modname=$profileModName type='form' func='edit' dynadata=$reginfo.dynadata}
        {/if}

        {modcallhooks hookobject='item' hookaction='new' module='Users'}

        <fieldset>
            <legend>{gt text="Check your entries and submit your registration"}</legend>
            <p id="users_checkmessage" class="z-sub">{gt text="Notice: When you are ready, click on 'Check your entries' to have your entries checked. When your entries are OK, click on 'Submit new user' to continue."}</p>
            <p id="users_validmessage" class="z-hide z-sub">{gt text="Your entries seem to be OK. Please click on 'Submit registration' when you are ready to continue."}</p>
            <div class="z-formbuttons z-buttons">
                {button id='checkuserajax' type='button' class='z-hide' src='help.gif' set='icons/extrasmall' __alt='Check your entries' __title='Check your entries' __text='Check your entries'}
                {button id='submitnewuser' type='submit' src='button_ok.gif' set='icons/extrasmall' __alt='Submit new user' __title='Submit new user' __text='Submit new user'}
                <a href="{modurl modname='Users' type='admin' func='view'}">{img modname='core' src='button_cancel.gif' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
                {img id='ajax_indicator' style='display: none;' modname='core' set='icons/extrasmall' src='indicator_circle.gif' alt=''}
            </div>
        </fieldset>
    </div>
    </form>
</div>