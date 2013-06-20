<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FeedViewer extends CI_Controller
{
    public function FeedViewer()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->model('mobile/file_meta_data');
        $countryList = $this->file_meta_data->getCountryList();

        $data = array();
        $data['countryList'] = $countryList;

        $this->load->view('mobile/header', $data);
        $this->load->view('mobile/feedViewer/_top', $data);
        $this->load->view('mobile/feedViewer/index', $data);
        $this->load->view('mobile/feedViewer/_bottom');
        $this->load->view('mobile/footer');
    }
    
    public function selectMissions()
    {
        $countryCode = $this->input->get('countryCode', TRUE);

        $this->load->model('mobile/mission_pool');
        $missionList = $this->mission_pool->getMissionListByCountryCode($countryCode);
        
        $data = array();
        $data['countryCode'] = $countryCode;
        $data['missionList'] = $missionList;

        $this->load->view('mobile/header', $data);
        $this->load->view('mobile/feedViewer/_top', $data);
        $this->load->view('mobile/feedViewer/selectMissions', $data);
        $this->load->view('mobile/feedViewer/_bottom');
        $this->load->view('mobile/footer');
    }
    
    public function photoViewer()
    {
        $countryCode = $this->input->get('countryCode', TRUE);
        $missionNo = $this->input->get('missionNo', TRUE);
        
        $this->load->model('mobile/chat');
        $photoData = $this->chat->getImageWithCountryCodeAndMissionNo($countryCode, $missionNo);

        $data = array();
        $data['photoData'] = $photoData;
        $data['countryCode'] = $countryCode;
        $data['missionNo'] = $missionNo;
        
        if($photoData)
        {
            $chatData = $this->chat->getSharedPhotoMessageData($photoData->imageFileNo, null);
            $senderLocationString = $chatData->senderCity;
            if($chatData->senderProvince) $senderLocationString .= ', '.$chatData->senderProvince;
            $senderLocationString .= ', '.$chatData->senderCountry;
            $data['senderLocationString'] = $senderLocationString;
            $data['profileImageFileCode'] = $chatData->senderProfileImage;
            $data['senderFirstName'] = $chatData->senderFirstName;
        }

        $this->load->view('mobile/header', $data);
        $this->load->view('mobile/feedViewer/_top', $data);
        $this->load->view('mobile/feedViewer/photoViewer', $data);
        $this->load->view('mobile/feedViewer/_bottom');
        $this->load->view('mobile/footer');
    }
    
    public function downloadRandomImage()
    {
        $countryCode = $this->input->get('countryCode', TRUE);
        $missionNo = $this->input->get('missionNo', TRUE);

        $this->load->model('mobile/chat');
        $photoData = $this->chat->getImageWithCountryCodeAndMissionNo($countryCode, $missionNo);
        
        $this->load->model('mobile/files');
        $fileData = $this->files->getFileData($photoData->fileNo);

        $fileData->messageNo = $photoData->messageNo;
        $imageFileName = $this->prepareImageFile($fileData, 300, 0);

        $finfo = finfo_open(FILEINFO_MIME_TYPE); 
        $mime = finfo_file($finfo, $imageFileName);
        $shortUrl = $fileData->url;

        header("Content-type: ".$mime);
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        header("X-Yongopal-Messageno: ".$photoData->messageNo);
        header("X-Yongopal-Shorturl: ".$shortUrl);

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
}