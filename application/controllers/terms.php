<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terms extends CI_Controller
{
	public function Terms()
	{
		parent::__construct();
		$this->load->helper('language');
	}

	public function index()
	{	
		$this->load->view('header');
		$this->load->view('terms/_top');
		$this->load->view('terms/index');
		$this->load->view('terms/_bottom');
		$this->load->view('footer');
	}
}