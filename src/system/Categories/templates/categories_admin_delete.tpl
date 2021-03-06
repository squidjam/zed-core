{gt text="Delete category" assign=templatetitle}
{include file="categories_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=editdelete.gif set=icons/large alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>

    <p class="z-warningmsg">
        {gt text="Do you really want to delete this category?"}<br />
        {gt text="Category"}: <strong>{$category.name}</strong>
    </p>

    <form class="z-form" action="{modurl modname="Categories" type="adminform" func="delete"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="cid" value="{$category.id}" />
            <input type="hidden" name="authid" id="authid" value="{insert name="generateauthkey" module="Categories"}" />
            <fieldset>
                <legend>{gt text="Confirmation prompt"}</legend>
                {if $numSubcats > 0}
                <p class="z-informationmsg">
                    {gt text="It contains %s direct sub-categories." tag1=$numSubcats}
                    {gt text="Please also choose what to do with this category's sub-categories:"}
                </p>
                <div class="z-formrow">
                    <label for="subcat_action_delete" >{gt text="Delete all sub-categories"}</label>
                    <input type="radio" id="subcat_action_delete" name="subcat_action" value="delete" checked="checked" onclick="document.getElementById('subcat_move').style.visibility='hidden'" />
                </div>
                <div class="z-formrow">
                    <label for="subcat_action_move">{gt text="Move all sub-categories to next category"}</label>
                    <input type="radio" id="subcat_action_move" name="subcat_action" value="move" onclick="document.getElementById('subcat_move').style.visibility='visible'" />
                    <div id="subcat_move" style="visibility: hidden;">
                        {$categorySelector}
                    </div>
                </div>
                {else}
                <input type="hidden" name="subcat_action" id="subcat_action" value="delete" />
                {/if}
                <div class="z-buttons z-formbuttons">
                    {button class="z-btgreen" src=button_ok.gif set=icons/extrasmall __alt="Delete" __title="Delete" __text="Delete"}
                    <a class="z-btred" href="{modurl modname=Categories type=admin}" title="{gt text="Cancel"}">{img modname=core src=button_cancel.gif set=icons/extrasmall __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
                </div>
            </fieldset>
        </div>
    </form>
</div>
