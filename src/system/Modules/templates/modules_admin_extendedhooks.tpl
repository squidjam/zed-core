{include file="modules_admin_menu.tpl"}
{ajaxheader modname="Modules" filename="extendedhooks.js"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=package.gif set=icons/large alt=""}</div>
    <h2>{gt text="Extended hook settings for"} {modgetinfo modid=$id info=displayname}</h2>
    <ul class="z-menulinks">
        <li><a href="{modurl modname=Modules type=admin func=hooks id=$id}" title="{gt text="Basic hook settings"}">{gt text="Basic hook settings"}</a></li>
        <li><a href="{modurl modname=Modules type=admin func=extendedhooks id=$id}" title="{gt text="Extended hook settings"}">{gt text="Extended hook settings"}</a></li>
    </ul>
    <p class="z-informationmsg">{gt text="Notice: In this page, you can enable individual hooked modules and change the order in which they are invoked. If you are not sure about the effects, you are recommended to not change their order. You can drag and drop hooked modules to position them where you want <strong>within</strong> sections. You cannot move a hooked module from one section to another."}</p>
    {if $grouped_hooks}
    <form class="z-form" action="{modurl modname="Modules" type="admin" func="extendedupdatehooks"}" method="post" enctype="application/x-www-form-urlencoded">
        {foreach key=hookaction item=hookgroup from=$grouped_hooks}
        <fieldset>
            <legend>{$hookaction|capitalize}&nbsp;Hooked modules</legend>
            <div id="{$hookaction}" class="hookcontainer">
                <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Modules"}" />
                <input type="hidden" name="id" value="{$id|safetext}" />
                {foreach item=hook from=$hookgroup}
                <div id="hook_{$hookaction}_{$hook.tmodule|safetext}" class="z-formrow z-sortable {cycle values="z-odd,z-even"}" style="background-image:url(../../../images/icons/extrasmall/move.gif); background-position:5px 50%; background-repeat:no-repeat; border:1px dotted #999999; padding-left: 30px; line-height:2em; margin:0.5em; padding:0.2em; cursor:move; ">
                    <span class="z-label" style="width:40%;">{gt text="Activate"} {$hook.tmodule|safetext} {gt text="for"} {$modinfo.displayname}</span>
                    <input id="modules_{$hookaction}_{$hook.tmodule|safetext}" name="hooks[{$hookaction}][{$hook.tmodule|safetext}]" type="checkbox" {if $hook.hookvalue eq 1}checked="checked"{/if} value="ON" />
                </div>
                {/foreach}
            </div>
        </fieldset>
        {/foreach}
        <div class="z-buttons z-formbuttons">
            {button src=button_ok.gif set=icons/extrasmall __alt="Save" __title="Save" __text="Save"}
            <a href="{modurl modname=Modules type=admin func=view}" title="{gt text="Cancel"}">{img modname=core src=button_cancel.gif set=icons/extrasmall __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </form>
    {else}
    <p class="z-warningmsg">{gt text="No hookable modules installed."}</p>
    {/if}
</div>
