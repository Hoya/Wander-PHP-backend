<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public function Admin()
	{
		parent::__construct();
	}
	
	public function banUser()
	{
		$this->load->library('Crypto');
	}
}