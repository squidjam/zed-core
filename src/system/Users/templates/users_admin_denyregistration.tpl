{gt text='Deny registration request of \'%1$s\'' tag1=$reginfo.uname assign='templatetitle'}
{include file='users_admin_menu.tpl'}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='Users' src='admin.png' alt=$templatetitle}</div>

    <h2>{$templatetitle}</h2>

    <p class="z-warningmsg">{gt text="Warning! This will delete the registration from the database. It cannot be undone."}</p>

    {include file='users_admin_includeregistration.tpl'}

    <form id="users_denyregistration" class="z-form" action="{modurl modname='Users' type='admin' func='denyRegistration'}" method="post">
        <div>
            <input type="hidden" id="users_authid" name="authid" value="{insert name='generateauthkey' module='Users'}" />
            <input type="hidden" id="users_uid" name="uid" value="{$reginfo.uid}" />
            <input type="hidden" id="users_restoreview" name="restoreview" value="{$restoreview}" />
            <input type="hidden" id="users_confirmed" name="confirmed" value="true" />
            <fieldset>
                <legend>{gt text='Applicant notification'}</legend>
                <div class="z-formrow">
                    <label for="users_usernotify">{gt text="Notify the applicant via e-mail?"}</label>
                    <div id="users_usernotify">
                        <input id="users_usernotifyyes" type="radio" name="usernotify" value="1" />
                        <label for="users_usernotifyyes">{gt text="Yes"}</label>
                        <input id="users_usernotifyno" type="radio" name="usernotify" value="0" checked="checked" />
                        <label for="users_usernotifyno">{gt text="No"}</label>
                    </div>
                </div>
                <div class="z-formrow">
                    <label for="users_reason">{gt text="Reason"}</label>
                    <textarea id="users_reason" name="reason" cols="50" rows="6"></textarea>
                    <div class="z-formnote">{gt text='Note: The reason is sent in the user notification e-mail. All formatting, including extra spaces and blank lines are ignored.'}</div>
                </div>
            </fieldset>
            <div class="z-formbuttons z-buttons">
                {button id='confirm' type='submit' src='delete_user.gif' set='icons/extrasmall' __alt='Delete registration' __title='Delete registration' __text='Delete registration'}
                <a href="{$cancelurl|safetext}" title="{gt text='Cancel'}">{img modname='core' src='button_cancel.gif' set='icons/extrasmall'  __alt="Cancel" __title="Cancel"} {gt text='Cancel'}</a>
            </div>
        </div>
    </form>
</div>
