<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->table='posts';
        $this->id='id';
    }
    
    public function output_filter($html) {
        //过滤is_bbcode
        //过滤is_smilies
        //过滤is_html
        //过滤is_hide
    }
    
    public function input_filter($html) {
        //过滤is_bbcode
        //过滤is_smilies
        //过滤is_html
        //过滤is_hide
    }
    
}

?>
