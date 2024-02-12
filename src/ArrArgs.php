<?php

/*
(c) Mikhail Palikhov <mikepalikhov@gmail.com>
 
This class allows to use complex array input parameters for functions
*/

namespace MikePal;

class ArrArgs {
    function __construct() {
    }

    //Full (check and set) process of params according to defaults
    static public function processParams(&$input, $defaults) {
        if( !is_array($defaults) ) throw new Exception('Argument "defaults" must be an array');
        
        funcUtils::checkParams($input, $defaults);
        funcUtils::setDefaults($input, $defaults);
    }

    //Set defaults for values, that doesn't exists in input
    static public function setDefaults(&$input, $defaults) {
        foreach( $defaults as $key => $value )
            if( !isset($input[$key]) ) $input[$key] = funcUtils::getDefaultValue($key, $value);
    }

    //Check function params according to rules in defaults variable
    static public function checkParams($input, $defaults) {
        foreach( $defaults as $key => $value )
            funcUtils::checkValue($key, $value, $input[$key]);
    }

    //Get default value depending of it's type (plain, array, object)
    static private function getDefaultValue($key, $default) {
        
        if( is_object($default) ) {
            
            if( method_exists($default, 'value') ) {
                return $default->value();
            } elseif( property_exists($default, 'value') ) {
                return $default->value;
            } else {
                throw new Exception('Default value "'.$key.'" has wrong format. If default value is an object it must have "value" method or property.');
            }
            
        } elseif( is_array($default) ) {
            
            if( isset($default['value']) ) {
                return $default['value'];
            } else {
                throw new Exception('Default value "'.$key.'" has wrong format. If default value is an array it must have "value" key.');
            }
            
        } else {
            return $default;
        }
    }

    //Check input values according to rules in defaults
    static private function checkValue($key, $default, $input) {
        
        if( is_object($default) ) {
            
            if( method_exists($default, 'check') ) {
                if( !$default->check($key, $input, $error) ) throw new Exception($error);
            } else {
                throw new Exception('Default value "'.$key.' has wrong format. If default value is an object it must have "check" method.');
            }
        
        } elseif( is_array($default) ) {
            
            if( isset($default['check_func']) && is_callable($default['check_func']) ) {
                if( !$default['check_func']($key, $input, $error) ) throw new Exception($error);
            } elseif(isset($default['required']) && $default['required']==true) {
                if( empty(trim($input)) ) throw new Exception('Value "'.$key.'" is required');
            }
        }
    }
}