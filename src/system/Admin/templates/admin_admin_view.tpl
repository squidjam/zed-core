{ajaxheader ui=true}
{gt text="Module categories list" assign=templatetitle}
{include file="admin_admin_menu.tpl"}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=windowlist.gif set=icons/large alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>
    <table class="z-datatable">
        <thead>
            <tr>
                <th>{gt text="Name"}</th>
                <th>{gt text="Actions"}</th>
            </tr>
        </thead>
        <tbody>
            {section name=category loop=$categories}
            <tr class="{cycle values="z-odd,z-even"}">
                <td><a href="{modurl modname=Admin type=admin func=adminpanel acid=$categories[category].cid}">{$categories[category].catname|safetext}</a></td>
                <td>
                    {assign var="options" value=$categories[category].options}
                    {section name=option loop=$options}
                    <a href="{$options[option].url|safetext}">{img modname=core set=icons/extrasmall src=$options[option].image title=$options[option].title alt=$options[option].title class='tooltips'}</a>
                    {/section}
                </td>
            </tr>
            {sectionelse}
            <tr class="z-datatableempty"><td colspan="2">{gt text="No items found."}</td></tr>
            {/section}
        </tbody>
    </table>
    <div class="z-adminviewbuttons">
        <a href="{modurl modname=Admin type=admin func=help fragment=view fqurl=true}">{img modname=core src=agt_support.gif set=icons/small __alt="Help" __title="Help"}</a>
    </div>
    {pager rowcount=$pager.numitems limit=$pager.itemsperpage posvar='startnum'}
</div>

<script type="text/javascript">
    Zikula.UI.Tooltips($$('.tooltips'));
</script>
