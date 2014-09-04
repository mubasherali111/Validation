<?php

/**
 * Validation Rules Class
 * @author Zayn Ali https://www.facebook.com/zaynali53
 * @link   https://github.com/zaynali53/Validation
 */
abstract class Rule {
    
    /**
     * Required field rule
     * @param  string $value
     * @param  string $field_name
     * @return bool
     */
    protected function required($value, $field_name) {
        $valid = ! empty($value);
        if ($valid === FALSE)
            $this->errors[] = $field_name . " is required.";
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

        if ( !empty($value) && trim(strlen($value)) < (int) $length) {
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

        if ( !empty($value) && trim(strlen($value)) > (int) $length) {
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
