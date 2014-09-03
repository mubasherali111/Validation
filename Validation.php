<?php

/**
 * Simple Input Validation Class
 * @author Zayn Ali https://www.facebook.com/zaynali53
 * @link http://phpmist.blogspot.com/2014/08/php-validation-class.html
 */
class Validation {

    protected $errors = array();

    public function get_errors() {
        return $this->errors;
    }

    public function validate($data, $rules) {
        if ( ! is_array($data)) {
            trigger_error('validate expects $data to be an array.');
            return;
        }

        if ( ! is_array($rules)) {
            trigger_error('validate expects $rules to be an array.');
            return;
        }

        $valid = TRUE;

        foreach ($rules as $field_name => $rules_str) {
            $rules_arr = explode('|', $rules_str);

            foreach ($rules_arr as $rule) {
                $value = isset($data[$field_name]) ? $data[$field_name] : NULL;

                if (preg_match('/:/', $rule)) {
                    $sub_rule = explode(':', $rule);
                    if ($this->$sub_rule[0]($value, $field_name, $sub_rule[1]) === FALSE)
                        $valid = FALSE;
                } else {
                    if ($this->$rule($value, $field_name) === FALSE)
                        $valid = FALSE;
                }
            }
        }

        return $valid;
    }

    protected function email($value, $field_name) {
        $valid = filter_var($value, FILTER_VALIDATE_EMAIL);
        if ($valid === FALSE)
            $this->errors[] = $field_name . " needs to be a valid E-Mail.";
        return $valid;
    }

    protected function required($value, $field_name) {
        $valid = ! empty($value);
        if ($valid === FALSE)
            $this->errors[] = $field_name . " is required.";
        return $valid;
    }

    protected function min_length($value, $field_name, $length) {
        $valid = TRUE;
        if ( ! is_numeric($length)) {
            trigger_error('min_length Param: $length must be a number');
            return;
        }

        if (trim(strlen($value)) < (int) $length) {
            $valid = FALSE;
            $this->errors[] = $field_name . " Minimum Length must be " . $length;
        }
        return $valid;
    }

    protected function max_length($value, $field_name, $length) {
        $valid = TRUE;
        if ( ! is_numeric($length)) {
            trigger_error('max_length Param: $length must be a number');
            return;
        }

        if (trim(strlen($value)) > (int) $length) {
            $valid = FALSE;
            $this->errors[] = $field_name . " Maximum Length must be " . $length;
        }
        return $valid;
    }

    protected function white_list($value, $field_name, $white_list_string) {
        $white_list = explode(',', $white_list_string);
        $valid = in_array($value, $white_list);
        if ($valid === FALSE)
            $this->errors[] = $field_name . " is invalid";
        return $valid;
    }

}

?>