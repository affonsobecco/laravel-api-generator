<?php

namespace Casa\Generator;

use Casa\Generator\Utils\DataBaseHelper;
use Illuminate\Support\Str;

class FormFieldsGenerator
{
    public static function generateLabel($field)
    {
        $label = Str::title(str_replace('_', ' ', $field['fieldName']));

        $template = "{!! Form::label('\$FIELD_NAME\$', '\$FIELD_NAME_TITLE\$:') !!}";

        $template = str_replace('$FIELD_NAME_TITLE$', $label, $template);
        $template = str_replace('$FIELD_NAME$', $field['fieldName'], $template);

        return $template;
    }

    private static function replaceFieldVars($textField, $field)
    {
        $label = Str::title(str_replace('_', ' ', $field['fieldName']));

        $textField = str_replace('$FIELD_NAME$', $field['fieldName'], $textField);
        $textField = str_replace('$FIELD_NAME_TITLE$', $label, $textField);
        $textField = str_replace('$FIELD_INPUT$', $textField, $textField);

        return $textField;
    }

    public static function text($templateData, $field)
    {
        $textField = self::generateLabel($field);

        $validatorInput = self::getInputValidators($field);

        $textField .= "\n\t{!! Form::text('\$FIELD_NAME\$', null, [" . $validatorInput . "'class' => 'form-control']) !!}";

        $templateData = str_replace('$FIELD_INPUT$', $textField, $templateData);

        $templateData = self::replaceFieldVars($templateData, $field);

        return $templateData;
    }

    public static function textarea($templateData, $field)
    {
        $textareaField = self::generateLabel($field);

        $validatorInput = self::getInputValidators($field);

        $textareaField .= "\n\t{!! Form::textarea('\$FIELD_NAME\$', null, [" . $validatorInput . "'class' => 'form-control']) !!}";

        $templateData = str_replace('$FIELD_INPUT$', $textareaField, $templateData);

        $templateData = self::replaceFieldVars($templateData, $field);

        return $templateData;
    }

    public static function password($templateData, $field)
    {
        $textField = self::generateLabel($field);

        $validatorInput = self::getInputValidators($field);

        $textField .= "\n\t{!! Form::password('\$FIELD_NAME\$', [" . $validatorInput . "'class' => 'form-control']) !!}";

        $templateData = str_replace('$FIELD_INPUT$', $textField, $templateData);

        $templateData = self::replaceFieldVars($templateData, $field);

        return $templateData;
    }

    public static function email($templateData, $field)
    {
        $textField = self::generateLabel($field);

        $validatorInput = self::getInputValidators($field);

        $textField .= "\n\t{!! Form::email('\$FIELD_NAME\$', null, [" . $validatorInput . "'class' => 'form-control']) !!}";
        $templateData = str_replace('$FIELD_INPUT$', $textField, $templateData);

        $templateData = self::replaceFieldVars($templateData, $field);

        return $templateData;
    }

    public static function file($templateData, $field)
    {
        $textField = self::generateLabel($field);

        $validatorInput = self::getInputValidators($field);

        $textField .= "\n\t{!! Form::file('\$FIELD_NAME\$') !!}";

        $templateData = str_replace('$FIELD_INPUT$', $textField, $templateData);

        $templateData = self::replaceFieldVars($templateData, $field);

        return $templateData;
    }

    public static function checkbox($templateData, $field)
    {
        $textField = "<div class=\"checkbox\">\n";
        $textField .= "\t\t<label>";

        $textField .= "{!! Form::checkbox('\$FIELD_NAME\$', 1, true) !!}";
        $textField .= '$FIELD_NAME_TITLE$';

        $textField .= '</label>';
        $textField .= "\n\t</div>";

        $templateData = str_replace('$FIELD_INPUT$', $textField, $templateData);

        $templateData = self::replaceFieldVars($templateData, $field);

        return $templateData;
    }

    public static function radio($templateData, $field)
    {
        $textField = self::generateLabel($field);

        if (count($field['typeOptions']) > 0)
        {
            $arr = explode(',', $field['typeOptions']);

            foreach ($arr as $item)
            {
                $label = Str::title(str_replace('_', ' ', $item));

                $textField .= "\n\t<div class=\"radio-inline\">";
                $textField .= "\n\t\t<label>";

                $textField .= "\n\t\t\t{!! Form::radio('\$FIELD_NAME\$', '" . $item . "', null) !!} $label";

                $textField .= "\n\t\t</label>";
                $textField .= "\n\t</div>";
            }
        }

        $templateData = str_replace('$FIELD_INPUT$', $textField, $templateData);

        $templateData = self::replaceFieldVars($templateData, $field);

        return $templateData;
    }

    public static function number($templateData, $field)
    {
        $textField = self::generateLabel($field);

        $textField .= "\n\t{!! Form::number('\$FIELD_NAME\$', null, ['class' => 'form-control']) !!}";
        $templateData = str_replace('$FIELD_INPUT$', $textField, $templateData);

        $templateData = self::replaceFieldVars($templateData, $field);

        return $templateData;
    }

    public static function date($templateData, $field)
    {
        $textField = self::generateLabel($field);

        $textField .= "\n\t{!! Form::date('\$FIELD_NAME\$', null, ['class' => 'form-control form-control-inline input-medium date-picker']) !!}";
        $templateData = str_replace('$FIELD_INPUT$', $textField, $templateData);

        $templateData = self::replaceFieldVars($templateData, $field);

        return $templateData;
    }

    public static function select($templateData, $field, $inputArray = true)
    {
        $textField = self::generateLabel($field);

        $validatorInput = self::getInputValidators($field);

        $textField .= "\n\t{!! Form::select('\$FIELD_NAME\$', \$INPUT_ARR\$, null, [" . $validatorInput . "'class' => 'form-control']) !!}";
        $textField = str_replace('$FIELD_NAME$', $field['fieldName'], $textField);

        //If options will be an array
        if (count($field['typeOptions']) > 0)
        {

            if ($inputArray)
            {
                $arr = explode(',', $field['typeOptions']);
                $inputArr = '[';
                foreach ($arr as $item)
                {
                    $inputArr .= " '$item' => '$item',";
                }

                $inputArr = substr($inputArr, 0, strlen($inputArr) - 1);

                $inputArr .= ' ]';

                $textField = str_replace('$INPUT_ARR$', $inputArr, $textField);
            }
            else
            {
                $options = explode($field['typeOptions'], ':');
                $modelName = Str::title(Str::camel( Str::singular($options[0])));
                $columnNameToList = $options[1];
                $inputArr = "$modelName::lists('$columnNameToList')";
                $textField = str_replace('$INPUT_ARR$', $inputArr, $textField);
            }

        }
        else
        {
            $textField = str_replace('$INPUT_ARR$', '[]', $textField);
        }

        $templateData = str_replace('$FIELD_INPUT$', $textField, $templateData);

        $templateData = self::replaceFieldVars($templateData, $field);

        return $templateData;
    }

    private static function getInputValidators($field)
    {
        $val = '';
        if (strpos($field['validations'], 'required') >= 0)
            $val = "'required' => 'required' ";
        if ($val != '') $val .= ', ';

        if (strpos($field['validations'], 'max') >= 0)
            //$val = "'required' => 'required' ";
            //'maxlength' => 10

        return $val;
    }
}
