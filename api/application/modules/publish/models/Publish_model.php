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

        $user1 = array('UID'=>'a516a631663c9724b06c7492bccf4f5a');
        $user2 = array('UID'=>'c5b578a2341fb27fd3cce415b4515e89');
        $user = array('c5b578a2341fb27fd3cce415b4515e89');
        $suser = 'a516a631663c9724b06c7492bccf4f5a';

        $this->db->select('UID, ID, Time, Title, Express');
//        $this->db->distinct();
        $this->db->from('Posts');
//        $this->db->group_start();
        $this->db->group_start();
//        $this->db->where($user2);
        $this->db->or_where('ID > ', 520);
        $this->db->group_end();
//        $this->db->or_where('ID <= ', 5);
//        $this->db->or_where_not_in('UID', $suser);
        $this->db->not_group_start();
//        $this->db->not_like('Title', 'Mongo');
        $this->db->like('Title', 'it');
        $this->db->group_end();
//        $this->db->group_end();
        $this->db->limit(20);
        $this->db->offset(0);
//        $this->db->group_by(array('Keyword'));
//        $this->db->order_by('Keyword   Asc  ');
//        $this->db->order_by('ID', 'Desc');
//        $this->db->select_max('ID', 'avgID');
//        $this->db->having('avgID > ', 500);
        print_r($this->db->get_compiled_select('', false));
        var_dump($this->db->get());
//        var_dump($this->db->get_where()['result']);
//        var_dump($this->db->count_all_results('Posts'));

        var_dump($this->db->count_all('Posts'));
    }
}