<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formatic_model_example extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_contacts()
    {
       return (array(0 => 'Option 1', 1 => 'Option 2', 2 => 'Option 3'));
    }

}