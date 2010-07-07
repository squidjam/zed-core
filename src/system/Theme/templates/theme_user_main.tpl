{ajaxheader imageviewer="true" ui=true}
{gt text="Theme switcher" assign=title}
{pagesetvar name=title value=$title}
<h2>{$title}</h2>
{insert name="getstatusmsg"}
<p class="z-informationmsg">
    {gt text="Themes enable you to change the visual presentation of the site when you are logged-in."} {gt text="The current theme is '%s'." tag1=$currenttheme.displayname}
    {if $currenttheme.name neq $defaulttheme.name}
    {modurl modname=Theme func=resettodefault assign=resetdefaulturl}
    {gt text='Your chosen theme is not the current site default. You can <a href="%1$s">reset</a> your chosen theme to site default of <a href="?theme=%2$s">%3$s</a>.' tag1=$resetdefaulturl tag2=$defaulttheme.name tag3=$defaulttheme.displayname}
    {/if}
</p>
<div style="text-align:center; margin:1em 0;"><img src="{$currentthemepic}" alt="{$currenttheme.displayname}" class="tooltips" title="{$currenttheme.description|default:$currenttheme.displayname}" /></div>

<h3>{gt text="Themes list"}</h3>
<div id="themes_list" class="z-clearfix">
    {foreach from=$themes item=theme}
    {if $theme.name neq $currenttheme.name}
    <dl class="theme_item">
        <dt><strong>{$theme.displayname}</strong></dt>
        <dt>
            <a href="{$theme.largeImage}" title="{$theme.description|default:$theme.displayname}" class="tooltips" rel="imageviewer">
                <img src="{$theme.previewImage}" alt="{$theme.displayname}" title="{$theme.description|default:$theme.displayname}" />
            </a>
        </dt>
        <dd><a href="?theme={$theme.name}">{gt text="Preview theme"}</a></dd>
        <dd><a href="?newtheme={$theme.name}">{gt text="Use theme"}</a></dd>
    </dl>
    {/if}
    {/foreach}
</div>

<script type="text/javascript">
    Zikula.UI.Tooltips($$('.tooltips'));
</script>
