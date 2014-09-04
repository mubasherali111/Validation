<?php

/**
 * Simple Input Validation Class
 * @author Zayn Ali https://www.facebook.com/zaynali53
 * @link   https://github.com/zaynali53/Validation
 */
class Validation {

    protected $errors = array();

    /**
     * Get validation errors for custom display
     * @return array
     */
    public function get_errors() {
        return $this->errors;
    }

    /**
     * Show Ordered/Un-ordered list of Generated Errors
     * @param  array   $attributes
     * @param  boolean $ordered_list
     * @return void
     */
    public function show_errors($attributes = array(), $ordered_list = FALSE) {
        if ( ! is_array($attributes)) {
            trigger_error('show_errors expects $attributes to be an array.');
            return;
        }

        if ( ! is_bool($ordered_list)) {
            trigger_error('show_errors expects $ordered_list to be a boolean.');
            return;
        }

        $tag = ($ordered_list == TRUE) ? "ol" : "ul";
        $output = "<$tag";
        foreach ($attributes as $key => $value) {
            $output .= " $key=\"$value\"";
        }
        $output .= ">";

        foreach ($this->errors as $error) {
            $output .= "<li>" . $error . "</li>";
        }
        $output .= "</$tag>";

        echo $output;
    }

    /**
     * Validates the data with the given set of rules
     * @param  array $data
     * @param  array $rules
     * @return bool
     */
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

    /**
     * Email filter rule
     * @param  string $value
     * @param  string $field_name
     * @param  string $domain
     * @return bool
     */
    protected function email($value, $field_name, $domain = NULL) {
        if ( ! is_null($domain)) {
            $specific = "@$domain";
            $verified = ($specific == substr($value, strpos($value, $specific)));
            if (filter_var($value, FILTER_VALIDATE_EMAIL) && $verified === FALSE)
                $this->errors[] = $field_name . " needs to be a valid E-Mail.";
            return $verified;
        }

        $valid = filter_var($value, FILTER_VALIDATE_EMAIL);
        if ($valid === FALSE)
            $this->errors[] = $field_name . " needs to be a valid E-Mail.";
        return $valid;
    }

    /**
     * Required field rule
     * @param  string $value
     * @param  string $field_name
     * @return bool
     */
    protected function required($value, $field_name) {
        $valid = !empty($value);
        if ($valid === FALSE)
            $this->errors[] = $field_name . " is required.";
        return $valid;
    }

    /**
     * Minimum Length of the string rule
     * @param  string $value
     * @param  string $field_name
     * @param  int    $length
     * @return bool
     */
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

    /**
     * Maximum Length of the string rule
     * @param  string $value
     * @param  string $field_name
     * @param  int    $length
     * @return bool
     */
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

    /**
     * White list filter rule
     * @param  string $value
     * @param  string $field_name
     * @param  string $white_list_string
     * @return bool
     */
    protected function white_list($value, $field_name, $white_list_string) {
        $white_list = explode(',', $white_list_string);
        $valid = in_array($value, $white_list);
        if ($valid === FALSE)
            $this->errors[] = $field_name . " is invalid";
        return $valid;
    }

}

?>
