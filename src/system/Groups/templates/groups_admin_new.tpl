{gt text="Create new group" assign=templatetitle}
{include file="groups_admin_menu.tpl"}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=filenew.gif set=icons/large alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>
    <form class="z-form" action="{modurl modname="Groups" type="admin" func="create"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Groups"}" />
            <fieldset>
                <legend>{gt text="New group"}</legend>
                <div class="z-formrow">
                    <label for="groups_name">{gt text="Name"}</label>
                    <input id="groups_name" name="name" type="text" size="30" maxlength="30" />
                </div>
                <div class="z-formrow">
                    <label for="groups_gtype">{gt text="Type"}</label>
                    <select id="groups_gtype" name="gtype">
                        {html_options options=$grouptype default='-1'}
                    </select>
                </div>
                <div class="z-formrow">
                    <label for="groups_state">{gt text="State"}</label>
                    <select id="groups_state" name="state">
                        {html_options options=$groupstate default='0'}
                    </select>
                </div>
                <div class="z-formrow">
                    <label for="groups_nbumax">{gt text="Maximum membership"}</label>
                    <input id="groups_nbumax" name="nbumax" type="text" size="10" maxlength="10" value="0" />
                </div>
                <div class="z-formrow">
                    <label for="groups_description">{gt text="Description"}</label>
                    <textarea id="groups_description" name="description" cols="50" rows="5"></textarea>
                </div>
            </fieldset>
            {modcallhooks hookobject=item hookaction=new module=Groups}
            <div class="z-buttons z-formbuttons">
                {button src=button_ok.gif set=icons/extrasmall __alt="Save" __title="Save" __text="Save"}
                <a href="{modurl modname=Groups type=admin func=view}" title="{gt text="Cancel"}">{img modname=core src=button_cancel.gif set=icons/extrasmall __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>
        </div>
    </form>
</div>