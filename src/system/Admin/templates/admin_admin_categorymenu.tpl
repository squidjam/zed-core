{pageaddvar name="javascript" value="javascript/ajax/prototype.js,javascript/ajax/scriptaculous.js"}
{pageaddvar name="javascript" value="javascript/livepipe/livepipe.js,javascript/livepipe/contextmenu.js"}
{pageaddvar name="javascript" value="javascript/ajax/controls.js"}
{ajaxheader modname=Admin filename=admin_admin_ajax.js}

<script type="text/javascript">
    /* <![CDATA[ */
    var lblclickToEdit = "{{gt text='Right-click to edit module category'}}";
    var lblEdit = "{{gt text='Edit category'}}";
    var lblDelete = "{{gt text='Delete category'}}";
    var lblSaving = "{{gt text='Saving'}}";
    /* ]]> */
</script>

<h1>{gt text="Administration" domain="zikula"} ({version})</h1>

{include file=admin_admin_securityanalyzer.htm}
{include file=admin_admin_developernotices.htm}
{nocache}{include file=admin_admin_updatechecker.htm}{/nocache}
{insert name="getstatusmsg"}

<div class="admintabs-container" id="admintabs-container">
    <ul id="admintabs" class="z-clearfix">
        {foreach from=$menuoptions name=menuoption item=menuoption}
        <li {if $currentcat eq $menuoption.cid} class="active"{/if}>
            <a id="C{$menuoption.cid}" href="{$menuoption.url|safetext}" title="{$menuoption.description|safetext}">{$menuoption.title|safetext}</a>
            <span id="catcontext{$menuoption.cid}" class="z-admindrop">&nbsp;</span>

            <script type="text/javascript">
            /* <![CDATA[ */
                var context_catcontext{{$menuoption.cid}} = new Control.ContextMenu('catcontext{{$menuoption.cid}}',{
                    leftClick: true,
                    animation: false
                });

                {{foreach from=$menuoption.items item=item}}
                    context_catcontext{{$menuoption.cid}}.addItem({
                        label: '{{$item.menutext|safetext}}',
                        callback: function(){window.location = document.location.pnbaseURL + '{{$item.menutexturl}}';}
                    });
                {{/foreach}}

            /* ]]> */
            </script>

        </li>
        {/foreach}
        <li id="addcat">
            <a id="addcatlink" href="{modurl modname=Admin type=admin func=new}" title="{gt text='New module category'}" onclick='return newCategory(this);'>&nbsp;</a>
        </li>
    </ul>
    {adminonlinemanual}
    {include file=admin_admin_ajaxAddCategory.htm}
</div>

<div class="z-hide" id="admintabs-none">
    <input type="hidden" name="authid" id="admintabsauthid" value="{insert name="generateauthkey" module="Admin"}" />
</div>