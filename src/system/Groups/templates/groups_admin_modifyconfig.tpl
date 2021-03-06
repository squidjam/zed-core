{* Do not allow editing of primaryadmingroup. For now it is read-only. *}
{include file="groups_admin_menu.tpl"}
{gt text="Settings" assign=templatetitle}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=configure.gif set=icons/large alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>
    <form class="z-form" action="{modurl modname="Groups" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Groups"}" />
            <fieldset>
                <legend>{gt text="General settings"}</legend>
                <div class="z-formrow">
                    <label for="groups_itemsperpage">{gt text="Items per page"}</label>
                    <input id="groups_itemsperpage" type="text" name="itemsperpage" size="3" value="{$itemsperpage|safetext}" />
                </div>
                <div class="z-formrow">
                    <label for="groups_defaultgroupid">{gt text="Initial user group"}</label>
                    <select id="groups_defaultgroupid" name="defaultgroupid">
                        {html_options options=$groups selected=$defaultgroupid}
                    </select>
                </div>
                <div class="z-formrow">
                    <label for="groups_hideclosed">{gt text="Hide closed groups"}</label>
                    <input id="groups_hideclosed" name="hideclosed" type="checkbox"{if $hideclosed eq 1} checked="checked"{/if} />
                </div>
                <div class="z-formrow">
                    <label for="groups_mailwarning">{gt text="Receive e-mail alert when there are new applicants"}</label>
                    <input id="groups_mailwarning" name="mailwarning" type="checkbox"{if $mailwarning eq 1} checked="checked"{/if} />
                </div>
            </fieldset>

            <div class="z-buttons z-formbuttons">
                {button src=button_ok.gif set=icons/extrasmall __alt="Save" __title="Save" __text="Save"}
                <a href="{modurl modname=Groups type=admin func=view}" title="{gt text="Cancel"}">{img modname=core src=button_cancel.gif set=icons/extrasmall __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>
        </div>
    </form>
</div>
