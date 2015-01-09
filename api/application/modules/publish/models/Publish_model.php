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
    }
}