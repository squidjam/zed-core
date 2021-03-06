{gt text='Enter confirmation code' assign='templatetitle'}
{modulelinks modname='Users' type='user'}
{include file='users_user_menu.tpl'}

<p class="z-informationmsg">{gt text="Please enter and EITHER your user name OR your e-mail address, and also enter the confirmation code you received. Once you enter this information and click the 'Submit' button you will receive a new password via e-mail."}</p>

<form class="z-form" action="{modurl modname='Users' type='user' func='passwordreminder'}" method="post">
    <fieldset>
        <input type="hidden" id="lostpasswordauthid" name="authid" value="{insert name='generateauthkey' module='Users'}" />
        <div class="z-formrow">
            <label for="users_uname">{gt text='User name'}</label>
            <input id="users_uname" type="text" name="uname" size="25" maxlength="25" value="{$lostpassword_uname}" />
        </div>
        <div class="z-formrow">
            <span class="z-label">{gt text='or'}</span>
        </div>
        <div class="z-formrow">
            <label for="users_email">{gt text='E-mail address'}</label>
            <input id="users_email" type="text" name="email" size="40" maxlength="60" value="{$lostpassword_email}" />
        </div>
    </fieldset>
    <fieldset>
        <div class="z-formrow">
            <label for="users_code">{gt text='Confirmation code'}</label>
            <input id="users_code" type="text" name="code" size="5" maxlength="6" value="{$lostpassword_code}" />
        </div>
    </fieldset>
    <div class="z-formbuttons z-buttons">
        {button src='button_ok.gif' set='icons/extrasmall' __alt='Submit' __title='Submit' __text='Submit'}
    </div>
</form>
