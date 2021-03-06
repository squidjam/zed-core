{gt text="Move category" assign=templatetitle}
{include file="categories_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=editcut.gif set=icons/large alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>
    <form class="z-form" action="{modurl modname="Categories" type="adminform" func="move"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="cid" value="{$category.id}" />
            <input type="hidden" name="authid" id="authid" value="{insert name="generateauthkey" module="Categories"}" />
            <fieldset>
                <legend>{gt text="Category"}</legend>
                <div class="z-formrow">
                    <label>{gt text="Name"}</label>
                    <span>{$category.name}</span>
                </div>
                <div class="z-formrow">
                    <label>{gt text="Path"}</label>
                    <span>{$category.path}</span>
                </div>
                <div class="z-formrow">
                    <label for="subcat_move">{gt text="Move all sub-categories to next category"}</label>
                    {$categorySelector}
                </div>
            </fieldset>
            <div class="z-buttons z-formbuttons">
                {button src=button_ok.gif set=icons/extrasmall __alt="Move" __title="Move" __text="Move"}
                <a href="{modurl modname=Categories type=admin}" title="{gt text="Cancel"}">{img modname=core src=button_cancel.gif set=icons/extrasmall __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>
        </div>
    </form>
</div>
