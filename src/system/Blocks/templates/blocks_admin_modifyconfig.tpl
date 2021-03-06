{include file="blocks_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=configure.gif set=icons/large __alt='Settings' }</div>
    <h2>{gt text="Settings"}</h2>
    <form class="z-form" action="{modurl modname="Blocks" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Blocks"}" />
            <fieldset>
                <legend>{gt text="General settings"}</legend>
                <div class="z-formrow">
                    <label for="blocks_collapseable">{gt text="Enable menu collapse icons"}</label>
                    {if $collapseable eq 1}
                    <input id="blocks_collapseable" name="collapseable" type="checkbox" value="1" checked="checked" />
                    {else}
                    <input id="blocks_collapseable" name="collapseable" type="checkbox" value="1" />
                    {/if}
                </div>
            </fieldset>

            <div class="z-buttons z-formbuttons">
                {button src=button_ok.gif set=icons/extrasmall __alt="Save" __title="Save" __text="Save"}
                <a href="{modurl modname=Blocks type=admin func=view}" title="{gt text="Cancel"}">{img modname=core src=button_cancel.gif set=icons/extrasmall __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>
        </div>
    </form>
</div>
