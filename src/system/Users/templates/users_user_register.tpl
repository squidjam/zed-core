{gt text='New account registration' assign='templatetitle'}
{ajaxheader modname='Users' filename='users.js'}
{ajaxheader modname='Users' filename='users_newuser.js'}

{include file="users_user_menu.tpl"}

{if !$regAllowed}
<h3>{gt text="Sorry! New user registration is currently disabled."}</h3>
<div class="z-warningmsg">
    {gt text="Reason"}: {$regOffReason|safetext}
</div>
{else}
{if $userMustAccept}
{modurl fqurl=true modname='legal' type='user' func='main' assign='touUrl'}
{modurl fqurl=true modname='legal' type='user' func='privacy' assign='ppUrl'}
{gt text='\'Terms of use\'' assign='touTextString'}
{gt text='\'Privacy policy\'' assign='ppTextString'}
{assign var='touLink' value='<a href="%1$s" onclick="window.open(\'%1$s\');return false;">%2$s</a>'|sprintf:$touUrl:$touTextString}
{assign var='ppLink' value='<a href="%1$s" onclick="window.open(\'%1$s\');return false;">%2$s</a>'|sprintf:$ppUrl:$ppTextString}

{if $touActive && $ppActive}
{gt text=' Please read the site\'s %1$s and %2$s beforehand.' tag1=$touLink tag2=$ppLink assign=touppString}
{gt text='%1$s and %2$s' tag1=$touTextString tag2=$ppTextString assign=touppHeadingText}
{elseif $touActive}
{gt text=' Please read the site\'s %s beforehand.' tag1=$touLink assign=touppString}
{assign var='touppHeadingText' value=$touTextString}
{elseif $ppActive}
{gt text=' Please read the site\'s %s beforehand.' tag1=$ppLink assign=touppString}
{assign var='touppHeadingText' value=$ppTextString}
{/if}
{/if}

<a id="users_formtop"></a>
<p>{gt text='Registering for a user account is easy. Registration can give you access to content and to features of the site that are not available to anonymous guests.'}
{if $userMustAccept}{$touppString}{/if}
{gt text='During your visits, you are recommended to set your browser to accept cookies from this site, because various features of the site use cookies.'}</p>

<p class="z-warningmsg">{gt text="The items that are marked with an asterisk ('*') are required entries."}</p>

<div id="users_errormessages_div" class="z-errormsg{if empty($errorMessages)} z-hide{/if}">
    <p>{gt text="Please correct the following items:"}</p>
    <ul id="users_errormessages">
    {foreach from=$errorMessages item='message'}
        <li>{$message}</li>
    {/foreach}
    </ul>
</div>

<form id="users_newuser" class="z-form" action="{modurl modname='Users' type='user' func='registerNewUser'}" method="post">
    <div>
        <input type="hidden" id="users_authid" name="authid" value="{insert name='generateauthkey' module='Users'}" />
        <fieldset>
            <legend>{gt text="Choose a user name"}</legend>
            <div class="z-formrow">
                <label for="users_reginfo_uname">{gt text="User name"}<span class="z-mandatorysym">{gt text="*"}</span></label>
                <input id="users_reginfo_uname" name="reginfo[uname]"{if isset($errorFields.reginfo_uname)} class="errorrequired"{/if} type="text" size="25" maxlength="25" value="{$reginfo.uname}" />
            </div>
        </fieldset>

        <fieldset>
            <legend>{gt text="Set a password and enter your e-mail address"}</legend>
            <div class="z-formrow">
                <label for="users_reginfo_pass">{gt text="Password"}<span class="z-mandatorysym">{gt text="*"}</span></label>
                {if $usePwdStrengthMeter}
                {pageaddvar name='javascript' value='system/Users/javascript/passwordStrengthMeter/jquery.js'}
                {pageaddvar name='javascript' value='system/Users/javascript/passwordStrengthMeter/passwordStrengthMeter.js'}
                <script type="text/javascript">
                    var shortPass = '{{gt text="Too Short Password"}}';
                    var badPass = '{{gt text="Weak, Use letters & numbers"}}';
                    var goodPass = '{{gt text="Medium, Use special characters"}}';
                    var strongPass = '{{gt text="Strong Password"}}';
                    var sameAsUsername = '{{gt text="Password can not match the username, choose a different password."}}';
                </script>
                {pageaddvar name='javascript' value='system/Users/javascript/passwordStrengthMeter_register.js'}
                {/if}
                {if $usePwdStrengthMeter && isset($errorFields.reginfo_pass)}{assign var='tempClass' value='class="users_pass errorrequired"'}
                {elseif $usePwdStrengthMeter}{assign var='tempClass' value='class="users_pass"'}
                {elseif isset($errorFields.reginfo_pass)}{assign var='tempClass' value='class="errorrequired"'}
                {else}{assign var='tempClass' value=''}{/if}
                <input id="users_reginfo_pass" name="reginfo[pass]"{if !empty($tempClass)} {$tempClass}{/if} type="password" size="25" maxlength="60" />
                {if $usePwdStrengthMeter}
                <div id="colorbar" class="z-formnote"></div>
                <span id="percent" class="z-formnote">0%</span><span id="result">&nbsp;&nbsp;{gt text='Enter your new password'}</span>
                {/if}
            </div>
            <div class="z-formrow">
                <label for="users_passagain">{gt text="Password (repeat for verification)"}<span class="z-mandatorysym">{gt text="*"}</span></label>
                <input id="users_passagain" name="passagain"{if isset($errorFields.passagain)} class="errorrequired"{/if} type="password" size="25" maxlength="60" />
                <span class="z-sub z-formnote">{gt text="Notice: The minimum length for user passwords is %s characters." tag1=$minpass}</span>
            </div>
            <div class="z-formrow">
                <label for="users_reginfo_passreminder">{gt text="Password reminder"}<span class="z-mandatorysym">{gt text="*"}</span></label>
                <input id="users_reginfo_passreminder" name="reginfo[passreminder]"{if isset($errorFields.reginfo_passreminder)} class="errorrequired"{/if} type="text" size="25" maxlength="128" value="{$reginfo.passreminder}" />
                <div class="z-sub z-formnote">{gt text="Enter a word or a phrase that will remind you of your password."}</div>
                <div class="z-formnote z-informationmsg">{gt text="Notice: Do not use a word or phrase that will allow others to guess your password! Do not include your password or any part of your password here!"}</div>
            </div>
            <div class="z-formrow">
                <label for="users_reginfo_email">{gt text="E-mail address"}<span class="z-mandatorysym">{gt text="*"}</span></label>
                <input id="users_reginfo_email" name="reginfo[email]"{if isset($errorFields.reginfo_email)} class="errorrequired"{/if} type="text" size="25" maxlength="60" value="{$reginfo.email}" />
            </div>
            <div class="z-formrow">
                <label for="users_emailagain">{gt text="E-mail address (repeat for verification)"}<span class="z-mandatorysym">{gt text="*"}</span></label>
                <input id="users_emailagain" name="emailagain"{if isset($errorFields.emailagain)} class="errorrequired"{/if} type="text" size="25" maxlength="60" value="{$emailagain}" />
            </div>
        </fieldset>

        {if $userMustAccept}
        <fieldset>
            <legend>{gt text='Accept the site\'s %s' tag1=$touppHeadingText}</legend>
            <div class="z-formrow">
                <label for="users_reginfo_agreetoterms"><span class="z-mandatorysym">{gt text="*"}</span></label>
                <div>
                    <input id="users_reginfo_agreetoterms" type="checkbox" name="reginfo[agreetoterms]"{if $reginfo.agreetoterms} checked=checked"{/if} value="1" />
                    {if $touActive && $ppActive}
                    <label for="users_reginfo_agreetoterms" id="users_reginfo_agreetoterms_field"{if isset($errorFields.reginfo_agreetoterms)} class="errorrequired"{/if}>{gt text='I agree to be bound by this site\'s %1$s and %2$s' tag1=$touLink tag2=$ppLink}</label>
                    {elseif  $touActive}
                    <label for="users_reginfo_agreetoterms" id="users_reginfo_agreetoterms_field"{if isset($errorFields.reginfo_agreetoterms)} class="errorrequired"{/if}>{gt text='I agree to be bound by this site\'s %1$s' tag1=$touLink}</label>
                    {elseif $ppActive}
                    <label for="users_reginfo_agreetoterms" id="users_reginfo_agreetoterms_field"{if isset($errorFields.reginfo_agreetoterms)} class="errorrequired"{/if}>{gt text='I agree to be bound by this site\'s %1$s' tag1=$ppLink}</label>
                    {/if}
                </div>
            </div>
        </fieldset>
        {else}
        <input id="users_reginfo_agreetoterms" type="hidden" name="reginfo[agreetoterms]" value="1" />
        {/if}

        {if $showProps}
            {modfunc modname=$profileModName type='form' func='edit' dynadata=$reginfo.dynadata}
        {/if}

        {if $useAntiSpamQuestion}
        <fieldset>
            <legend>{gt text="Answer the security question (this prevents automated sign-ups by bots and scripts)"}</legend>
            <div class="z-formrow">
                <label for="users_antispamanswer">{$antiSpamQuestion|safehtml}<span class="z-mandatorysym">{gt text="*"}</span></label>
                <input id="users_antispamanswer" name="antispamanswer"{if isset($errorFields.antispamanswer)} class="errorrequired"{/if} type="text" size="25" maxlength="60" value="{$antispamanswer}" />
            </div>
        </fieldset>
        {/if}

        <fieldset>
            <legend>{gt text="Check your entries and submit your registration"}</legend>
            <p id="users_checkmessage" class="z-sub">{gt text="Notice: When you are ready, click on 'Check your entries' to have your entries checked. When your entries are OK, click on 'Submit new user' to continue."}</p>
            <p id="users_validmessage" class="z-hide">{gt text="Your entries seem to be OK. Please click on 'Submit registration' when you are ready to continue."}</p>
            <div class="z-formbuttons z-buttons">
                {button id='checkuserajax' type='button' class='z-hide' src='help.gif' set='icons/extrasmall' __alt='Check your entries' __title='Check your entries' __text='Check your entries'}
                {button id='submitnewuser' type='submit' src='button_ok.gif' set='icons/extrasmall' __alt='Submit new user' __title='Submit registration' __text='Submit registration'}
                {img id='ajax_indicator' style='display: none;' modname=core set='icons/extrasmall' src='indicator_circle.gif' alt=''}
            </div>
        </fieldset>
    </div>
</form>
{/if}