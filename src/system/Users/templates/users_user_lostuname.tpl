{gt text='Lost user name recovery' assign='templatetitle'}
{modulelinks modname='Users' type='user'}
{include file='users_user_menu.tpl'}

<p class="z-informationmsg">{gt text="Please enter your e-mail address below and click the 'Submit' button. You will be e-mailed your user name."}</p>

<form class="z-form" action="{modurl modname='Users' type='user' func='mailuname'}" method="post">
    <fieldset>
        <input type="hidden" id="lostunameauthid" name="authid" value="{insert name='generateauthkey' module='Users'}" />
        <div class="z-formrow">
            <label for="users_email">{gt text='E-mail address'}</label>
            <input id="users_email" type="text" name="email" size="40" maxlength="60" value="{sessiongetvar name='lostuname_email'}" />
        </div>
    </fieldset>
    <div class="z-formbuttons z-buttons">
        {button src='button_ok.gif' set='icons/extrasmall' __alt='Submit' __title='Submit' __text='Submit'}
    </div>
</form>
