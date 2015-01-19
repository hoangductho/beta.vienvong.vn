<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 1/9/15
 * Time: 4:20 AM
 */

class Publish_model extends CI_Model {
    public function __construct() {
        parent::__construct();

        $this->load->database();

        $array = array('UID'=>'a516a631663c9724b06c7492bccf4f5a');

        $this->db->select('UID, ID, Time, Title, Express');
        $this->db->from('Posts');
//        $this->db->where($array);
        $this->db->where('Keyword', 'Apple');
        $this->db->limit(10);
        $this->db->offset(12);
        $this->db->group_by(array('UID'));
        $this->db->select_max('ID', 'maxID');
//        print_r($this->db->get_compiled_select('', false));
        $this->db->get();
    }
}