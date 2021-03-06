<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Form
 * @subpackage Form_Plugin
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * TextInput plugin for Form_View
 *
 * The Form_Plugin_TextInput plugin is a general purpose input plugin that allows the user to enter any kind of character based data,
 * including text, numbers, dates and more.
 *
 * Typical use in template file:
 * <code>
 * <!--[formtextinput id="title" maxLength="100" width="30em"]-->
 * </code>
 *
 * The Form_Plugin_TextInput plugin supports basic CSS styling through attributes like "width", "color" and "font_weight". See
 * {@link Form_StyledPlugin} for more info.
 */
class Form_Plugin_TextInput extends Form_StyledPlugin
{
    /**
     * Displayed text in the text input.
     *
     * This variable contains the text to be displayed in the input.
     * At first page display this variable contains whatever set in the template. At postback it contains
     * user input. User input is always trimmed using the HPH function trim().
     *
     * @var string
     */
    public $text = '';

    /**
     * Text input mode.
     *
     * The text mode defines what kind of HTML element to render. The possible values are:
     * - <b>Singleline</b>: renders a normal input element (default).
     * - <b>Password</b>: renders a input element of type "password" (means you cannot read what you are typing).
     * - <b>Multiline</b>: renders a textarea element.
     *
     * @var string
     */
    public $textMode = 'singleline';

    /**
     * Enable or disable read only mode.
     *
     * A text input in read only mode do *not* decode posted data, since the user cannot
     * enter anything in the read only input.
     *
     * @var boolean
     */
    public $readOnly;

    /**
     * Text to show as tool tip for the input.
     *
     * @var string
     */
    public $toolTip;

    /**
     * CSS class to use.
     *
     * @var string
     */
    public $cssClass;

    /**
     * Number of columns for multiline input.
     *
     * @var integer
     */
    public $cols;

    /**
     * Number of rows for multiline input.
     *
     * @var integer
     */
    public $rows;

    /**
     * Data field name for looking up initial data.
     *
     * The name stored here is used to lookup initial data for the plugin in the render's variables.
     * Defaults to the ID of the plugin. See also tutorials on the Zikula site.
     *
     * @var string
     */
    public $dataField;

    /**
     * Enable or disable use of $dataField.
     *
     * @var boolean
     */
    public $dataBased;

    /**
     * Group name for this input.
     *
     * The group name is used to locate data in the render (when databased) and to restrict which
     * plugins to do validation on (to be implemented).
     *
     * @var string
     * @see   Form_View::getValues(), Form_View::isValid()
     */
    public $group;

    /**
     * Validation indicator used by the framework.
     *
     * The true/false value of this variable indicates whether or not the text input is valid
     * (a valid input satisfies the mandatory requirement and regex validation pattern).
     * Use {@link Form_Plugin_TextInput::setError()} and {@link Form_Plugin_TextInput::clearValidation()}
     * to change the value.
     *
     * @var boolean
     */
    public $isValid = true;

    /**
     * Enable or disable mandatory check.
     *
     * By enabling mandatory checking you force the user to enter something in the text input.
     *
     * @var boolean
     */
    public $mandatory;

    /**
     * Enable or disable mandatory asterisk.
     *
     * @var boolean
     */
    public $mandatorysym;

    /**
     * Error message to display when input does not validate.
     *
     * Use {@link Form_Plugin_TextInput::setError()} and {@link Form_Plugin_TextInput::clearValidation()}
     * to change the value.
     *
     * @var string
     */
    public $errorMessage;

    /**
     * Text label for this plugin.
     *
     * This variable contains the label text for the input. The {@link Form_Plugin_Label} plugin will set
     * this text automatically when it is a label for this input.
     *
     * @var string
     */
    public $myLabel;

    /**
     * Size of HTML input (number of characters).
     *
     * @var integer
     */
    public $size;

    /**
     * Maximum number of characters allowed in the text input.
     *
     * @var integer
     */
    public $maxLength;

    /**
     * Regular expression to match input against.
     *
     * User input must match this pattern. Uses PHP preg_match() to match the input and pattern.
     *
     * @var string
     */
    public $regexValidationPattern;

    /**
     * Regular expression error message.
     *
     * Error message to display when the regex validation pattern does not match input.
     *
     * @var string
     */
    public $regexValidationMessage;

    /**
     * HTML input name for this plugin. Defaults to the ID of the plugin.
     *
     * @var string
     */
    public $inputName;

    /**
     * Get filename for this plugin.
     *
     * A requirement from the framework - must be implemented like this. Used to restore plugins on postback.
     *
     * @internal
     * @return string
     */
    function getFilename()
    {
        return __FILE__;
    }

    /**
     * Indicates whether or not the input is empty.
     *
     * @return boolean
     */
    function isEmpty()
    {
        return $this->text == '';
    }

    /**
     * Create event handler.
     *
     * @param Form_View $view    Reference to Form_View object.
     * @param array     &$params Parameters passed from the Smarty plugin function.
     *
     * @see    Form_Plugin
     * @return void
     */
    function create($view, &$params)
    {
        // All member variables are fetched automatically before create (as strings)
        // Here we afterwards load all special and non-string parameters
        $this->inputName = (array_key_exists('inputName', $params) ? $params['inputName'] : $this->id);
        $this->textMode = (array_key_exists('textMode', $params) ? $params['textMode'] : 'singleline');

        $this->dataField = (array_key_exists('dataField', $params) ? $params['dataField'] : $this->id);
        $this->dataBased = (array_key_exists('dataBased', $params) ? $params['dataBased'] : true);

        if (array_key_exists('maxLength', $params)) {
            $this->maxLength = $params['maxLength'];
        } else if ($this->maxLength == null && !in_array(strtolower($this->textMode), array('multiline', 'hidden'))) {
            $view->formDie("Missing maxLength value in textInput plugin '$this->id'.");
        }
    }

    /**
     * Load event handler.
     *
     * @param Form_View $view    Reference to Form_View object.
     * @param array     &$params Parameters passed from the Smarty plugin function.
     *
     * @return void
     */
    function load($view, &$params)
    {
        // The load function expects the plugin to read values from the render.
        // This can be done with the loadValue function (which can be called in other situations than
        // through the onLoad event).
        $this->loadValue($view, $view->get_template_vars());
    }

    /**
     * Initialize event handler.
     *
     * @param FormRender $view Reference to Form_View object.
     *
     * @return void
     */
    function initialize($view)
    {
        $view->addValidator($this);
    }

    /**
     * Render event handler.
     *
     * @param Form_View $view Reference to Form_View object.
     *
     * @return string The rendered output
     */
    function render($view)
    {
        $idHtml = $this->getIdHtml();

        $nameHtml = " name=\"{$this->inputName}\"";

        $titleHtml = ($this->toolTip != null ? ' title="' . $view->translateForDisplay($this->toolTip) . '"' : '');

        $readOnlyHtml = ($this->readOnly ? ' readonly="readonly" tabindex="-1"' : '');

        $sizeHtml = ($this->size > 0 ? " size=\"{$this->size}\"" : '');

        $maxLengthHtml = ($this->maxLength > 0 ? " maxlength=\"{$this->maxLength}\"" : '');

        $text = DataUtil::formatForDisplay($this->text);

        $class = 'text';
        if (!$this->isValid) {
            $class .= ' error';
        }
        if ($this->mandatory && $this->mandatorysym) {
            $class .= ' z-mandatoryinput';
        }
        if ($this->readOnly) {
            $class .= ' readonly';
        }
        if ($this->cssClass != null) {
            $class .= ' ' . $this->cssClass;
        }

        $attributes = $this->renderAttributes($view);

        switch (strtolower($this->textMode)) {
            case 'singleline':
                $result = "<input type=\"text\"{$idHtml}{$nameHtml}{$titleHtml}{$sizeHtml}{$maxLengthHtml}{$readOnlyHtml} class=\"{$class}\" value=\"{$text}\"{$attributes} />";
                if ($this->mandatory && $this->mandatorysym) {
                    $result .= '<span class="z-mandatorysym">*</span>';
                }
                break;

            case 'password':
                $result = "<input type=\"password\"{$idHtml}{$nameHtml}{$titleHtml}{$maxLengthHtml}{$readOnlyHtml} class=\"{$class}\" value=\"{$text}\"{$attributes} />";
                if ($this->mandatory && $this->mandatorysym) {
                    $result .= '<span class="z-mandatorysym">*</span>';
                }
                break;

            case 'multiline':
                $colsrowsHtml = '';
                if ($this->cols != null) {
                    $colsrowsHtml .= " cols=\"{$this->cols}\"";
                }

                if ($this->rows != null) {
                    $colsrowsHtml .= " rows=\"{$this->rows}\"";
                }

                $result = "<textarea{$idHtml}{$nameHtml}{$titleHtml}{$readOnlyHtml}{$colsrowsHtml} class=\"{$class}\"{$attributes}>{$text}</textarea>";
                if ($this->mandatory && $this->mandatorysym) {
                    $result .= '<span class="z-mandatorysym">*</span>';
                }
                break;

            case 'hidden':
                $result = "<input type=\"hidden\"{$idHtml}{$nameHtml}{$maxLengthHtml} value=\"{$text}\" />";
                break;

            default:
                $result = __f('Unknown value [%1$s] for \'%2$s\'.', array($this->textMode, 'textMode'));
        }

        return $result;
    }

    /**
     * Decode event handler.
     *
     * @param Form_View $view Reference to Form_View object.
     *
     * @return void
     */
    function decode($view)
    {
        // Do not read new value if readonly (evil submiter might have forged it)
        if (!$this->readOnly) {
            $this->text = FormUtil::getPassedValue($this->inputName, null, 'POST');
            if (get_magic_quotes_gpc()) {
                $this->text = stripslashes($this->text);
            }

            // Make sure newlines are returned as "\n" - always.
            $this->text = str_replace("\r\n", "\n", $this->text);
            $this->text = str_replace("\r", "\n", $this->text);

            $this->text = trim($this->text);
        }
    }

    /**
     * Validates the input.
     *
     * @param Form_View $view Reference to Form_View object.
     *
     * @return void
     */
    function validate($view)
    {
        $this->clearValidation($view);

        if ($this->mandatory && $this->isEmpty()) {
            $this->setError(__('Error! An entry in this field is mandatory.'));
        } else if (strlen($this->text) > $this->maxLength && $this->maxLength > 0) {
            $this->setError(sprintf(__f('Error! Input text must be no longer than %s characters.'), $this->maxLength));
        } else if ($this->regexValidationPattern != null && $this->text != '' && !preg_match($this->regexValidationPattern, $this->text)) {
            $this->setError($view->translateForDisplay($this->regexValidationMessage));
        }
    }

    /**
     * Sets an error message.
     *
     * @param string $msg Error message.
     *
     * @return void
     */
    function setError($msg)
    {
        $this->isValid = false;
        $this->errorMessage = $msg;
        $this->toolTip = $msg;
    }

    /**
     * Clears the validation data.
     *
     * @param Form_View $view Reference to Form_View object.
     *
     * @return void
     */
    function clearValidation($view)
    {
        $this->isValid = true;
        $this->errorMessage = null;
        $this->toolTip = null;
    }

    /**
     * Saves value in data object.
     *
     * Called by the render when doing $view->getValues()
     * Uses the group parameter to decide where to store data.
     *
     * @param Form_View $view  Reference to Form_View object.
     * @param array     &$data Data object.
     *
     * @return void
     */
    function saveValue($view, &$data)
    {
        if ($this->dataBased) {
            $value = $this->parseValue($view, $this->text);

            if ($this->group == null) {
                $data[$this->dataField] = $value;
            } else {
                if (!array_key_exists($this->group, $data)) {
                    $data[$this->group] = array();
                }
                $data[$this->group][$this->dataField] = $value;
            }
        }
    }

    /**
     * Parses a value.
     *
     * Override this function in inherited plugins if other format is needed.
     *
     * @param Form_View $view Reference to Form_View object.
     * @param string    $text Text.
     *
     * @return string Parsed Text.
     */
    function parseValue($view, $text)
    {
        return $text;
    }

    /**
     * Load values.
     *
     * Called internally by the plugin itself to load values from the render.
     * Can also by called when some one is calling the render object's Form_ViewetValues.
     *
     * @param Form_View $view    Reference to Form_View object.
     * @param array     &$values Values to load.
     *
     * @return void
     */
    function loadValue($view, &$values)
    {
        if ($this->dataBased) {
            $value = null;

            if ($this->group == null) {
                if (array_key_exists($this->dataField, $values)) {
                    $value = $values[$this->dataField];
                }
            } else {
                if (array_key_exists($this->group, $values) && array_key_exists($this->dataField, $values[$this->group])) {
                    $value = $values[$this->group][$this->dataField];
                }
            }

            if ($value !== null) {
                $this->text = $this->formatValue($view, $value);
            }
        }
    }

    /**
     * Format the value to specific format.
     *
     * Override this function in inherited plugins if other format is needed.
     *
     * @param Form_View $view  Reference to Form_View object.
     * @param string    $value The value to format.
     *
     * @return string Formatted value.
     */
    function formatValue($view, $value)
    {
        return $value;
    }
}
