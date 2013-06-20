<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Announcements extends CI_Controller
{
    public function FeedViewer()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $data = null;
        $this->load->view('mobile/header', $data);
        $this->load->view('mobile/announcements/_top', $data);
        $this->load->view('mobile/announcements/index', $data);
        $this->load->view('mobile/announcements/_bottom');
        $this->load->view('mobile/footer');
    }
}