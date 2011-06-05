<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index() {
	$this->load->model('joke');
	$this->joke->set_id(1);
	$joke = $this->joke->get();
	$this->load->view('welcome_message', compact('joke'));
    }

}