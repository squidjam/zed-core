<tr>
    <td>
        <label for="rmodule">{gt text="Module name" domain='zikula'}</label>
    </td>
    <td>
        <input id="rmodule" value="{$module|safetext}" maxlength="100" size="40" name="rmodule" type="text" />
    </td>
</tr>
<tr>
    <td>
        <label for="rtemplate">{gt text="Template file" domain='zikula'}</label>
    </td>
    <td>
        <input id="rtemplate" value="{$template|safetext}" maxlength="100" size="40" name="rtemplate" type="text" />
    </td>
</tr>
<tr>
    <td colspan="2">
        <strong>{gt text="Notice: The template file must be placed in the '/templates' subdirectory of the specified module." domain='zikula'}</strong>
    </td>
</tr>
<tr>
    <td>
        <label for="rparameters">{gt text="Parameters (format: assign=value;assign2=value2...)" domain='zikula'}</label>
    </td>
    <td>
        <input id="rparameters" value="{$parameters|safetext}" maxlength="100" size="40" name="rparameters" type="text" />
    </td>
</tr>
