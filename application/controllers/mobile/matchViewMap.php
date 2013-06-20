<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MatchViewMap extends CI_Controller
{
	public function MatchViewMap()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = null;
		$this->load->view('mobile/header', $data);
		$this->load->view('mobile/matchViewMap/_top', $data);
		$this->load->view('mobile/matchViewMap/index', $data);
		$this->load->view('mobile/matchViewMap/_bottom');
		$this->load->view('mobile/footer');
	}
}