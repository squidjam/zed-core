<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Form
 * @subpackage Template_Plugins
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Smarty function to wrap Form_Render generated form controls with suitable form tags.
 *
 * @param array       $params  Parameters passed in the block tag.
 * @param string      $content Content of the block.
 * @param Form_Render &$render Reference to Form render object.
 *
 * @return string The rendered output.
 */
function smarty_block_form($params, $content, &$render)
{
    if ($content) {
        PageUtil::addVar('stylesheet', 'system/Theme/style/form/style.css');
        $encodingHtml = (array_key_exists('enctype', $params) ? " enctype=\"$params[enctype]\"" : '');
        $action = htmlspecialchars(System::getCurrentUri());
        $classString = '';
        if (isset($params['cssClass'])) {
            $classString = "class=\"$params[cssClass]\" ";
        }

        $render->postRender();

        $out  =  "<form id=\"pnFormForm\" {$classString}action=\"$action\" method=\"post\"{$encodingHtml}>";
        $out .= $content;
        $out .= "\n<div>\n" . $render->getStateHTML() . "\n"; // Add <div> for XHTML validation
        $out .= $render->getIncludesHTML() . "\n";
        $out .= $render->getAuthKeyHTML() . "
<input type=\"hidden\" name=\"pnFormEventTarget\" id=\"pnFormEventTarget\" value=\"\" />
<input type=\"hidden\" name=\"pnFormEventArgument\" id=\"pnFormEventArgument\" value=\"\" />
<script type=\"text/javascript\">
<!--
function pnFormDoPostBack(eventTarget, eventArgument)
{
  var f = document.getElementById('pnFormForm');
  if (!f.onsubmit || f.onsubmit())
  {
    f.pnFormEventTarget.value = eventTarget;
    f.pnFormEventArgument.value = eventArgument;
    f.submit();
  }
}
// -->
</script>
</div>\n";
        $out .= "</form>\n";
        return $out;
    }
}