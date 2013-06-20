<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
	protected $currentLanguage;

	public function Home()
	{
		parent::__construct();

		if($this->uri->segment(3))
		{
			$this->currentLanguage = $this->uri->segment(3);
		}
		elseif($this->session->userdata('currentLanguage'))
		{
			$this->currentLanguage = $this->session->userdata('currentLanguage');
		}
		else
		{
			if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			{
				$this->currentLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			}
			else
			{
				$this->currentLanguage = "en";
			}
			
		}
		
		$this->session->set_userdata(array('currentLanguage' => $this->currentLanguage));

		switch($this->currentLanguage)
		{
			case "ko":
			{
				$this->lang->load('home', 'korean');
				break;
			}
			case "ja":
			{
				$this->lang->load('home', 'japanese');
				break;
			}
			case "zh":
			{
				$this->lang->load('home', 'simplified_chinese');
				break;
			}
			default:
			{
				$this->lang->load('home', 'english');
			}
		}
		$this->load->helper('language');
	}

	public function index()
	{
		$data['navmenu'] = array('ON', 'OFF', 'OFF');
		$data['currentLanguage'] = $this->currentLanguage;
		$this->load->view('header', $data);
		$this->load->view('home/_top', $data);
		$this->load->view('home/index', $data);
		$this->load->view('home/_bottom');
		$this->load->view('footer');
	}
	
	public function about()
	{
		$data['navmenu'] = array('OFF', 'ON', 'OFF');
		$data['currentLanguage'] = $this->currentLanguage;
		$this->load->view('header', $data);
		$this->load->view('home/_top', $data);
		$this->load->view('home/about', $data);
		$this->load->view('home/_bottom');
		$this->load->view('footer');
	}
	
	public function contact()
	{
		$data['navmenu'] = array('OFF', 'OFF', 'ON');
		$data['currentLanguage'] = $this->currentLanguage;
		$this->load->view('header', $data);
		$this->load->view('home/_top', $data);
		$this->load->view('home/contact', $data);
		$this->load->view('home/_bottom');
		$this->load->view('footer');
	}
	
	public function registerEmail()
	{
		$lang = "en";
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		}
		
		if($_POST) $email = $_POST['email'];
		else $email = null;

		if($this->validateEmail($email))
		{
			$emailParts = explode(".", $email);
			$lastObject = count($emailParts) - 1;
			$checkEmail = $this->db->query("select * from participate where email = ".$this->db->escape($email));

			if($checkEmail->num_rows() != 0)
			{
				echo 110;
			}
			/*
			else if($emailParts[$lastObject] == "edu")
			{
				$dbConn->query("insert into participate (email) values ('".$email."')");
				echo 100;
			}
			else if($emailParts[$lastObject] == "kr")
			{
				$secondLastObject = $lastObject - 1;
				if($emailParts[$secondLastObject] == "ac")
				{
					$dbConn->query("insert into participate (email) values ('".$email."')");
					echo 100;
				}
				else
				{
					echo 105;
				}
			}
			*/
			else
			{
				$this->db->query("insert into participate (email, country, regDatetime) values (".$this->db->escape($email).", ".$this->db->escape($this->getCountryCode()).", NOW())");
				echo 100;
			}
		}
		else
		{
			echo 0;
		}
	}
	
	private function getCountryCode()
	{
		$lang = "en";
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		}

		switch($lang)
		{
			case "ko":
			{
				$countryCode = "KR";
				break;
			}
			case "ja":
			{
				$countryCode = "JP";
				break;
			}
			case "zh":
			{
				$countryCode = "CN";
				break;
			}
			default:
			{
				$countryCode = "US";
			}
		}
		
		return $countryCode;
	}
	
	private function validateEmail($email)
	{
		//check for all the non-printable codes in the standard ASCII set,
		//including null bytes and newlines, and exit immediately if any are found.
		if (preg_match("/[\\000-\\037]/",$email))
		{
			return false;
		}

		$pattern = "/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?$/iD";
		if(!preg_match($pattern, $email))
		{
			return false;
		}

		// Validate the domain exists with a DNS check
		// if the checks cannot be made (soft fail over to true)
		list($user,$domain) = explode('@',$email);

		if( function_exists('checkdnsrr') )
		{
			// Linux: PHP 4.3.0 and higher & Windows: PHP 5.3.0 and higher
			if( !checkdnsrr($domain,"MX") )
			{
				return false;
			}
		}
		else if( function_exists("getmxrr") )
		{
			if ( !getmxrr($domain, $mxhosts) )
			{
				return false;
			}
		}
		return true;
	}
}