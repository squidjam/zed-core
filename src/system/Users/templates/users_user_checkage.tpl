{gt text='New account registration' assign='templatetitle'}
{include file='users_user_menu.tpl'}

{if !$regAllowed}
    <h3>{gt text="Sorry! New user registration is currently disabled."}</h3>
    <div>{$regOffReason|safetext}</div>
{else}
    <p>{gt text="You are required to be %s years of age or older to register for an account on this site. Please choose your case below:" tag1=$minimumAge}</p>
    <ul>
        <li><a href="{modurl modname='Users' type='user' func='register'}">{gt text="I am %s or older." tag1=$minimumAge}</a></li>
        <li><a href="{modurl modname='Users' type='user' func='underage'}">{gt text="I am under %s." tag1=$minimumAge}</a></li>
    </ul>
{/if}
