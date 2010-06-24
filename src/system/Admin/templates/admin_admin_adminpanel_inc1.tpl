<div id="z-adminiconlist">
    {assign var="count" value="0"}
    {assign var="moduleid" value="0"}
    {foreach from=$adminlinks name=adminlink item=adminlink}
    {math equation="$count+1" assign="count"}
    {math equation="$moduleid+1" assign="moduleid"}

    {if $count eq 1}<div class="z-adminiconrow z-clearfix">{/if}
        <div id="A{$adminlink.id}" class="z-adminiconcontainer draggable" style="width:{math equation='100/x' x=$modvars.modulesperrow format='%.0f'}%;z-index:{math equation="2200-$moduleid"};">
            {if $modvars.admingraphic eq 1}
            <a class="z-adminicon z-adminfloat" title="{$adminlink.menutexttitle}" href="{$adminlink.menutexturl|safetext}">
                <img class="z-adminfloat" src="{$adminlink.adminicon}" title="{$adminlink.menutext|safetext}" alt="{$adminlink.menutext|safetext}" />
            </a>
            {/if}
            <div class="z-adminlinkheader">
                {img modname='Admin' src='mouse.png' set='icons/extrasmall' __alt='Drag and drop into a new module category' __title='Drag and drop into a new module category' id="dragicon`$adminlink.id`" class='z-dragicon'}
                <a class="z-adminmodtitle" title="{$adminlink.menutexttitle}" href="{$adminlink.menutexturl|safetext}">{$adminlink.menutext|safetext}</a>

                {assign var="modlinks" value=false}
                {modapifunc modname=$adminlink.modname type="admin" func="getlinks" assign="modlinks"}
                {if $modlinks}
                {img modname='Admin' id="mcontext`$moduleid`" class="z-pointericon" src='arrow.png' __alt='Functions'}
                {/if}

                <script type="text/javascript">
                /* <![CDATA[ */
                {{if $modlinks}}
                    var context_mcontext{{$moduleid}} = new Control.ContextMenu('mcontext{{$moduleid}}',{
                        leftClick: true,
                        animation: false
                    });
                
                    {{foreach from=$modlinks item=modlink}}
                        context_mcontext{{$moduleid}}.addItem({
                            label: '{{$modlink.text|safetext}}',
                            callback: function(){window.location = document.location.pnbaseURL + '{{$modlink.url}}';}
                        });
                    {{/foreach}}

                {{/if}}
                    new Draggable("A{{$adminlink.id}}", {
                        revert: true,
                        handle: "dragicon{{$adminlink.id}}",
                        zindex: 2200 // must be higher than the active minitab and all other admin icons
                    });
                
                /* ]]> */
                </script>


            </div>

            {math equation="170-x*30" x=$modvars.modulesperrow format="%.0f" assign=trunLen}
            <div class="z-menutexttitle">{$adminlink.menutexttitle|safetext|truncate:$trunLen:"&hellip;":false}</div>

        </div>

    {if $count eq $modvars.modulesperrow}{assign var="count" value="0"}</div>
    {else}
{if $smarty.foreach.adminlink.last}</div>{/if}
{/if}

{/foreach}
</div>