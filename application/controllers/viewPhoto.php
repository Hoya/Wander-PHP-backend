<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ViewPhoto extends CI_Controller
{
	public function ViewPhoto()
	{
		parent::__construct();
		$this->load->helper('language');
		$this->load->library('session');
		$this->load->library('user_agent');
	}

	public function index()
	{
		$this->load->library('Crypto');
		$this->load->model('mobile/chat');
		$fileNo = $this->uri->segment(3);
		$isRandom = $this->uri->segment(5);
		
		if(!$fileNo)
		{
			trigger_error("file number can not be null", E_USER_ERROR);
			exit();
		}

		$padding = str_repeat("=", (4 - strlen($fileNo) % 4));
		$fileNo = base64_decode($fileNo.$padding);

		if(!is_numeric($fileNo))
		{
			$key = $fileNo;
			$fileNo = 0;
		}
		else
		{
			$key = '';
		}

		$chatData = $this->chat->getSharedPhotoMessageData($fileNo, $key);

		if(!$chatData)
		{
			trigger_error("file does not exist", E_USER_ERROR);
			exit();
		}
		
		$fileNo = $chatData->imageFileNo;

		$tempThumbs = array();
		for($i = 0; $i < 30; $i++)
		{
			$tempThumbs[$i]['fileName'] = $i.".png";
			
			if($i < 5) $tempThumbs[$i]['cityName'] = "Amsterdam";
			if($i == 5) $tempThumbs[$i]['cityName'] = "Himeji";
			if($i > 5 && $i < 10) $tempThumbs[$i]['cityName'] = "Hyderabad";
			if($i > 9 && $i < 13) $tempThumbs[$i]['cityName'] = "Kamakura";
			if($i == 13) $tempThumbs[$i]['cityName'] = "Kobe";
			if($i == 14) $tempThumbs[$i]['cityName'] = "Kyoto";
			if($i == 15) $tempThumbs[$i]['cityName'] = "Mountain View";
			if($i == 16) $tempThumbs[$i]['cityName'] = "Osaka";
			if($i > 16 && $i < 19) $tempThumbs[$i]['cityName'] = "Portland";
			if($i == 19) $tempThumbs[$i]['cityName'] = "Seattle";
			if($i == 20) $tempThumbs[$i]['cityName'] = "Secunderabad";
			if($i > 20 && $i < 28) $tempThumbs[$i]['cityName'] = "Tokyo";
			if($i > 27 && $i < 31) $tempThumbs[$i]['cityName'] = "Vancouver";
		}

		$senderLocationString = $chatData->senderCity;
		if($chatData->senderProvince) $senderLocationString .= ', '.$chatData->senderProvince;
		$senderLocationString .= ', '.$chatData->senderCountry;

		$data = array();
		$data['locale'] = get_locale();
		$data['chatData'] = $chatData;
		$data['senderLocationString'] = $senderLocationString;
		$data['tempThumbs'] = $tempThumbs;
		$data['isRandom'] = $isRandom;
		
		// encode shared image file number
		$imageFileCode = base64_encode($fileNo);
		$imageFileCode = str_replace('=', '', $imageFileCode);
		$data['imageFileCode'] = $imageFileCode;
		
		// encode profile image file number
		$profileImageFileCode = base64_encode($chatData->senderProfileImage);
		$profileImageFileCode = str_replace('=', '', $profileImageFileCode);
		$data['profileImageFileCode'] = $profileImageFileCode;

		if($this->agent->is_mobile())
		{
			$this->load->view('header', $data);
			$this->load->view('viewMobile/_top', $data);
			$this->load->view('viewMobile/index', $data);
			$this->load->view('viewMobile/_bottom');
			$this->load->view('footer');
		}
		else
		{
			$this->load->view('header', $data);
			$this->load->view('viewPhoto/_top', $data);
			$this->load->view('viewPhoto/index', $data);
			$this->load->view('viewPhoto/_bottom');
			$this->load->view('footer');
		}
	}

	public function mobile()
	{
		$this->load->library('Crypto');
		$this->load->model('mobile/chat');
		$fileNo = $this->uri->segment(3);
		$isRandom = $this->uri->segment(5);
		
		if(!$fileNo)
		{
			trigger_error("file number can not be null", E_USER_ERROR);
			exit();
		}

		$padding = str_repeat("=", (4 - strlen($fileNo) % 4));
		$fileNo = base64_decode($fileNo.$padding);

		if(!is_numeric($fileNo))
		{
			$key = $fileNo;
			$fileNo = 0;
		}
		else
		{
			$key = '';
		}

		$chatData = $this->chat->getSharedPhotoMessageData($fileNo, $key);

		if(!$chatData)
		{
			trigger_error("file does not exist", E_USER_ERROR);
			exit();
		}
		
		$fileNo = $chatData->imageFileNo;

		$tempThumbs = array();
		for($i = 0; $i < 30; $i++)
		{
			$tempThumbs[$i]['fileName'] = $i.".png";
			
			if($i < 5) $tempThumbs[$i]['cityName'] = "Amsterdam";
			if($i == 5) $tempThumbs[$i]['cityName'] = "Himeji";
			if($i > 5 && $i < 10) $tempThumbs[$i]['cityName'] = "Hyderabad";
			if($i > 9 && $i < 13) $tempThumbs[$i]['cityName'] = "Kamakura";
			if($i == 13) $tempThumbs[$i]['cityName'] = "Kobe";
			if($i == 14) $tempThumbs[$i]['cityName'] = "Kyoto";
			if($i == 15) $tempThumbs[$i]['cityName'] = "Mountain View";
			if($i == 16) $tempThumbs[$i]['cityName'] = "Osaka";
			if($i > 16 && $i < 19) $tempThumbs[$i]['cityName'] = "Portland";
			if($i == 19) $tempThumbs[$i]['cityName'] = "Seattle";
			if($i == 20) $tempThumbs[$i]['cityName'] = "Secunderabad";
			if($i > 20 && $i < 28) $tempThumbs[$i]['cityName'] = "Tokyo";
			if($i > 27 && $i < 31) $tempThumbs[$i]['cityName'] = "Vancouver";
		}

		$senderLocationString = $chatData->senderCity;
		if($chatData->senderProvince) $senderLocationString .= ', '.$chatData->senderProvince;
		$senderLocationString .= ', '.$chatData->senderCountry;

		$data = array();
		$data['locale'] = get_locale();
		$data['chatData'] = $chatData;
		$data['senderLocationString'] = $senderLocationString;
		$data['tempThumbs'] = $tempThumbs;
		$data['isRandom'] = $isRandom;
		
		// encode shared image file number
		$imageFileCode = base64_encode($fileNo);
		$imageFileCode = str_replace('=', '', $imageFileCode);
		$data['imageFileCode'] = $imageFileCode;
		
		// encode profile image file number
		$profileImageFileCode = base64_encode($chatData->senderProfileImage);
		$profileImageFileCode = str_replace('=', '', $profileImageFileCode);
		$data['profileImageFileCode'] = $profileImageFileCode;

		$this->load->view('header', $data);
		$this->load->view('viewMobile/_top', $data);
		$this->load->view('viewMobile/index', $data);
		$this->load->view('viewMobile/_bottom');
		$this->load->view('footer');
	}

	public function random()
	{
		// open and read the cache file
		$handle = fopen('randomList.csv', "r");
		$urlData = fread($handle, filesize('randomList.csv'));
		fclose($handle);

		$urlArray = explode("\n", $urlData);
		if(count($urlArray) > 0)
		{
			$previousRandomIndex = $this->session->userdata('previousRandomIndex');
			while($randomIndex = array_rand($urlArray))
			{
				if($previousRandomIndex != $randomIndex && trim($urlArray[$randomIndex]) != "")
				{
					break;
				}
			}

			$this->session->set_userdata('previousRandomIndex', $randomIndex);
			$url = trim($urlArray[$randomIndex]);
			header("Location: ".$url."/random/1");			
		}
		exit();
	}

	public function downloadImage()
	{
		$this->load->model('mobile/files');

		$fileId = $this->uri->segment(3);
		$messageNo = $this->uri->segment(5);

		if(trim($fileId, '0123456789') == '')
		{
			$fileData = $this->files->getFileData($fileId);
		}
		else
		{
			$fileData = null;
			if(trim($fileId, '0123456789') == '-')
			{
				$fileData = $this->files->getFileDataFromKey($fileId);
			}
			
			if(!$fileData)
			{
				$padding = str_repeat("=", (4 - strlen($fileId) % 4));
				$fileNo = base64_decode($fileId.$padding);
				$fileData = $this->files->getFileData($fileNo);
			}
		}

		if(!$fileData)
		{
			trigger_error("file does not exist", E_USER_ERROR);
			exit();
		}

		$fileData->messageNo = $messageNo;
		$imageFileName = $this->prepareImageFile($fileData, 580, 0);

		$finfo = finfo_open(FILEINFO_MIME_TYPE); 
		$mime = finfo_file($finfo, $imageFileName);
		$shortUrl = $fileData->url;

		header("Content-type: ".$mime);
		header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", strtotime($fileData->regDatetime))." GMT");
		header("X-Yongopal-Messageno: ".$messageNo);
		header("X-Yongopal-Shorturl: ".$shortUrl);

        $imageData = imagecreatefromjpeg($imageFileName);
        imagejpeg($imageData);
        imagedestroy($imageData);
	}

	public function downloadImageWithKey()
	{
		$this->load->model('mobile/files');

		$key = $this->uri->segment(3);

		$padding = str_repeat("=", (4 - strlen($key) % 4));
		$key = base64_decode($key.$padding);
		$fileData = $this->files->getFileDataFromKey($key);

		if(!$fileData)
		{
			trigger_error("file does not exist", E_USER_ERROR);
			exit();
		}

		$fileData->messageNo = $messageNo;
		$imageFileName = $this->prepareImageFile($fileData, 580, 0);

		$finfo = finfo_open(FILEINFO_MIME_TYPE); 
		$mime = finfo_file($finfo, $imageFileName);
		$shortUrl = $fileData->url;

		header("Content-type: ".$mime);
		header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", strtotime($fileData->regDatetime))." GMT");
		header("X-Yongopal-Messageno: ".$messageNo);
		header("X-Yongopal-Shorturl: ".$shortUrl);
		
        $imageData = imagecreatefromjpeg($imageFileName);
        imagejpeg($imageData);
        imagedestroy($imageData);
	}

	public function downloadImageWithMessageNo()
	{
		$this->load->model('mobile/files');

		$messageNo = $this->uri->segment(3);
		$fileData = $this->files->getFileDataFromMessageNo($messageNo);

		if(!$fileData)
		{
			trigger_error("file does not exist", E_USER_ERROR);
			exit();
		}
		$imageFileName = $this->prepareImageFile($fileData, 580, 0);

		$finfo = finfo_open(FILEINFO_MIME_TYPE); 
		$mime = finfo_file($finfo, $imageFileName);
		$shortUrl = $fileData->url;

		header("Content-type: ".$mime);
		header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", strtotime($fileData->regDatetime))." GMT");
		header("X-Yongopal-Messageno: ".$messageNo);
		header("X-Yongopal-Shorturl: ".$shortUrl);

        $imageData = imagecreatefromjpeg($imageFileName);
        imagejpeg($imageData);
        imagedestroy($imageData);
	}
	
	public function downloadProfileImage()
	{
		$this->load->model('mobile/files');

		$fileNo = $this->uri->segment(3);
		$width= $this->uri->segment(5);
		$height = $this->uri->segment(7);
		if(!$width) $width = 90;
		if(!$height) $height = 90;

		$fileData = $this->files->getFileData($fileNo);
		if(!$fileData)
		{
			$padding = str_repeat("=", (4 - strlen($fileNo) % 4));
			$fileNo = base64_decode($fileNo.$padding);
			$fileData = $this->files->getFileData($fileNo);

			if(!$fileData)
			{
				trigger_error("file does not exist", E_USER_ERROR);
				exit();
			}
		}
		$fileData->fileNo = $fileNo;

		$imageFileName = $this->prepareImageFile($fileData, $width, $height);

		$finfo = finfo_open(FILEINFO_MIME_TYPE); 
		$mime = finfo_file($finfo, $imageFileName);
		$shortUrl = $fileData->url;

		header("Content-type: ".$mime);
		header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", strtotime($fileData->regDatetime))." GMT");

        $imageData = imagecreatefromjpeg($imageFileName);
        imagejpeg($imageData);
        imagedestroy($imageData);
	}

	private function prepareImageFile($fileData, $width=0, $height=0)
	{
		require_once(APPPATH."libraries/phpthumb/ThumbLib.inc.php");

		// create cache file if it doesn't exist
		$tempFile = sprintf("/var/ram/cache_%d_%d_%d.jpg", $fileData->fileNo, $width, $height);
		$cacheFileName = sprintf("application/cache/img/cache_%d_%d_%d.jpg", $fileData->fileNo, $width, $height);

        if(!file_exists($cacheFileName))
        {
            set_time_limit(0);
            $source = $tempFile;
            if(isset($fileData->data) && $fileData->data != NULL)
            {
                $fh = fopen($tempFile, 'wc') or trigger_error("Can't open file for write", E_USER_ERROR);
                fwrite($fh, $fileData->data, $fileData->fileSize);
                fflush($fh);
                fclose($fh);
            }
            else if($fileData->filePath != NULL)
            {
                $source = 'mobile/'.$fileData->filePath;
            }
            
            $thumb = PhpThumbFactory::create($source);
            $thumb->setOptions(array("resizeUp" => true));
            if($width == 0 || $height == 0)
            {
                $thumbnailImg = $thumb->resize($width, $height);
            }
            else
            {
                $thumbnailImg = $thumb->adaptiveResize($width, $height);
            }
            $thumbnailImg->save($cacheFileName, 'JPG');
            @unlink($tempFile);
        }

        return $cacheFileName;
	}

	private function flipImage($src, $type)
	{
		$imgsrc = imagecreatefromjpeg($src);
		$width = imagesx($imgsrc);
		$height = imagesy($imgsrc);
		$imgdest = imagecreatetruecolor($width, $height);

		for ($x=0 ; $x<$width ; $x++)
		{
			for ($y=0 ; $y<$height ; $y++)
			{
				if ($type == 1) imagecopy($imgdest, $imgsrc, $width-$x-1, $y, $x, $y, 1, 1);
				if ($type == 2) imagecopy($imgdest, $imgsrc, $x, $height-$y-1, $x, $y, 1, 1);
				if ($type == 3) imagecopy($imgdest, $imgsrc, $width-$x-1, $height-$y-1, $x, $y, 1, 1);
			}
		}

		imagedestroy($imgsrc);
		return $imgdest;
	}
}