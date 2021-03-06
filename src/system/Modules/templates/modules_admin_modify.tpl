{include file="modules_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=xedit.gif set=icons/large __alt="Edit module"}</div>
    <h2>{gt text="Edit module"} - {modgetinfo modid=$id info=displayname}</h2>
    <form class="z-form" action="{modurl modname="Modules" type="admin" func="update"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Modules"}" />
            <input type="hidden" name="id" value="{$id|safetext}" />
            <fieldset>
                <legend>{gt text="Module"}</legend>
                <div class="z-formrow">
                    <label for="modules_newdisplayname">{gt text="Module display name"}</label>
                    <input id="modules_newdisplayname" name="newdisplayname" type="text" size="30" maxlength="64" value="{$displayname|safetext}" />
                </div>
                <div class="z-formrow">
                    <label for="modules_newurl">{gt text="Module URL"}</label>
                    <input id="modules_newurl" name="newurl" type="text" size="30" maxlength="64" value="{$url|safetext}" />
                </div>
                <div class="z-formrow">
                    <label for="modules_newdescription">{gt text="Description"}</label>
                    <textarea id="modules_newdescription" name="newdescription" cols="50" rows="10">{$description|safetext}</textarea>
                </div>
                <div class="z-formrow">
                    <label>{gt text="Defaults"}</label>
                    <span><a href="{modurl modname="Modules" type="admin" func="modify" id=$id restore=true}">{gt text="Restore now"}</a> ({gt text="This may break your existing indexed URLs"})</span>
                </div>
            </fieldset>

            <div class="z-buttons z-formbuttons">
                {button src=button_ok.gif set=icons/extrasmall __alt="Save" __title="Save" __text="Save"}
                <a href="{modurl modname=Modules type=admin func=view}" title="{gt text="Cancel"}">{img modname=core src=button_cancel.gif set=icons/extrasmall __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            </div>
        </div>
    </form>
</div>
