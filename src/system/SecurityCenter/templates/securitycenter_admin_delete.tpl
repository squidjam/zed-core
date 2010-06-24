{include file="securitycenter_admin_menu.tpl"}
<div class="z-admincontainer">
    {gt text="Delete hacking attempt" assign=templatetitle}
    <div class="z-adminpageicon">{img modname=core src=editdelete.gif set=icons/large alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>
    <form class="z-form" action="{modurl modname="SecurityCenter" type="admin" func="delete"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="SecurityCenter"}" />
            <input type="hidden" name="confirmation" value="1" />
            <input type="hidden" name="hid" value="{$hid|safetext}" />
            <fieldset>
                <legend>{gt text="Confirmation prompt"}</legend>
                <div class="z-formrow">
                    <p>{gt text="Do you really want to delete this hacking attempt?"}</p>
                </div>
            </fieldset>
            <div class="z-buttons z-formbuttons">
                {button class="z-btgreen" src=button_ok.gif set=icons/extrasmall __alt="Delete" __title="Delete" __text="Delete"}
                <a class="z-btred" href="{modurl modname=SecurityCenter type=admin func=view}" title="{gt text="Cancel"}">{img modname=core src=button_cancel.gif set=icons/extrasmall __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>
        </div>
    </form>
</div>