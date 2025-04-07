<?php

namespace YFormSpamProtection;

class yform_spam_protection
{
    /**
     * Checkt, ob ein Input-Feld mit einem bestimmten Namen in YForm ein bestimmtes Wort enthält, um Spammer auszuschließen, die so eindeutig zu identifizieren sind, wie z.B. "Eric Jones".
     * @param $input_field_name :string
     * @param $params :array
     */
    public static function checkBadword($input_field_name, $value, $params, $yform): void
    {
        $badword = $params[0];
        $error_msg = $params[1];
        $badword = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $badword));
        // Alle Felder überprüfen
        foreach ($yform->obj as $obj) {
            // Checken, ob Input-Name mit dem mitgegebenen übereinstimmt.
            if ($obj->name == $input_field_name) {
                $input_value = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $obj->value));
                if (str_contains($input_value, $badword)) {
                    $yform->params['warning'][$obj->id] = 'has-error';
                    $yform->params['warning_messages'][$obj->id] = $error_msg;
                }
            }
        }
    }
}
