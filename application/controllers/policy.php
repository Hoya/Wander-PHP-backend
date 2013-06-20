<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Policy extends CI_Controller
{
	public function Terms()
	{
		parent::__construct();
		$this->load->helper('language');
	}

	public function index()
	{
		
		$this->load->view('header');
		$this->load->view('policy/_top');
		$this->load->view('policy/index');
		$this->load->view('policy/_bottom');
		$this->load->view('footer');
	}
}