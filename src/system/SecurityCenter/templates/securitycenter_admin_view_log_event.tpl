{include file="securitycenter_admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname=core src=windowlist.gif set=icons/large __alt="View Logged Events"}</div>
    {gt text="All" assign=lblAll}
    <h2>{gt text="Logged events list"}</h2>
    <form id="securitycenter_logfilter" class="z-form" action="{modurl modname="SecurityCenter" type="admin" func="viewobj" ot=$ot}" method="post" enctype="application/x-www-form-urlencoded">
        <fieldset>
            <legend>{gt text="Filter"}</legend>
            <label>{gt text="User name"}</label>
            {selector_object_array name="filter[uid]" modname="SecurityCenter" class="log_event" field="uid" displayField="username" selectedValue=$filter.uid defaultValue="0" defaultText="$lblAll" distinct="1" submit="1"}
            <label>{gt text="Component"}</label>
            {selector_object_array name="filter[component]" modname="SecurityCenter" class="log_event" field="component" displayField="component" selectedValue=$filter.component defaultValue="0" defaultText="$lblAll" distinct="1" submit="1"}
            <label>{gt text="Module"}</label>
            {selector_object_array name="filter[module]" modname="SecurityCenter" class="log_event" field="module" displayField="module" selectedValue=$filter.module defaultValue="0" defaultText="$lblAll" distinct="1" submit="1"}
            <label>{gt text="Type"}</label>
            {selector_object_array name="filter[type]" modname="SecurityCenter" class="log_event" field="type" displayField="type" selectedValue=$filter.type defaultValue="0" defaultText="$lblAll" distinct="1" submit="1"}
            {if ($filter.uid || $filter.component || $filter.module || $filter.type)}
            <a href="{modurl modname="SecurityCenter" type="admin" func="viewobj" ot=$ot}">{img src=cancel.gif modname=core set=icons/extrasmall __alt="Clear filter" __title="Clear filter"}</a>
            {/if}
        </fieldset>
    </form>

    <table class="z-admintable">
        <thead>
            <tr>
                <th><a href="{modurl modname="SecurityCenter" type="admin" func="viewobj" ot=$ot sort="date DESC"}">{gt text="Date"}</a></th>
                <th><a href="{modurl modname="SecurityCenter" type="admin" func="viewobj" ot=$ot sort="username"}">{gt text="User name"}</a></th>
                <th><a href="{modurl modname="SecurityCenter" type="admin" func="viewobj" ot=$ot sort="component"}">{gt text="Component"}</a></th>
                <th><a href="{modurl modname="SecurityCenter" type="admin" func="viewobj" ot=$ot sort="module"}">{gt text="Module"}</a></th>
                <th><a href="{modurl modname="SecurityCenter" type="admin" func="viewobj" ot=$ot sort="type"}">{gt text="Type"}</a></th>
                <th><a href="{modurl modname="SecurityCenter" type="admin" func="viewobj" ot=$ot sort="function"}">{gt text="Function name"}</a></th>
                <th><a href="{modurl modname="SecurityCenter" type="admin" func="viewobj" ot=$ot sort="sec_component, sec_instance, sec_permission"}">{gt text="Security"}</a></th>
                <th><a href="{modurl modname="SecurityCenter" type="admin" func="viewobj" ot=$ot sort="message"}">{gt text="Message"}</a></th>
                <th>{gt text="Actions"}</th>
            </tr>
        </thead>
        <tbody>
            {secgenauthkey module="SecurityCenter" assign="authkey"}
            {foreach from=$objectArray item=event}
            <tr class="{cycle values="z-odd,z-even"}">
                <td>{$event.date|safetext}</td>
                <td>{$event.username|safetext}</td>
                <td>{$event.component|safetext}</td>
                <td>{$event.module|safetext}</td>
                <td>{$event.type|safetext}</td>
                <td>{$event.function|safetext}</td>
                <td>{$event.sec_component|safetext}&nbsp;|&nbsp;{$event.sec_instance|safetext}&nbsp;|&nbsp;{$event.sec_permission|safetext}</td>
                <td valign="top">{$event.message|safetext}</td>
                <td valign="top"><a href="{modurl modname="SecurityCenter" type="adminform" func="delete" ot="log_event" id=$event.id authid=$authkey}">{img src=cancel.gif modname=core set=icons/extrasmall __alt="Delete" __title="Delete"}</a></td>
            </tr>
            {foreachelse}
            <tr class="z-admintableempty"><td colspan="9">{gt text="No items found."}</td></tr>
            {/foreach}
        </tbody>
    </table>
    {pager rowcount=$pager.numitems limit=$pager.itemsperpage posvar=startnum shift=1}
</div>