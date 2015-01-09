<?php
/**
 * Created by PhpStorm.
 * User: hoanggia
 * Date: 1/8/15
 * Time: 8:03 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Publish extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->model('Publish_model');
        echo 'Hello World!';
        echo 'This is Publish Controller';
    }
}