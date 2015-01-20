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
        $this->db->where($array);
//        $this->db->where('Keyword', 'Google');
        $this->db->limit(10);
        $this->db->offset(0);
        $this->db->group_by(array('Keyword'));
        $this->db->select_max('ID', 'avgID');
//        print_r($this->db->get_compiled_select('', false));
        var_dump($this->db->get());
        var_dump($this->db->count_all_results('games'));

        var_dump($this->db->count_all('Posts'));
    }
}