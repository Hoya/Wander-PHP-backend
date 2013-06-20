<?
class files extends CI_Model
{
    public function __construct()
    {
    	$serverName = explode('.', $_SERVER['SERVER_NAME']);
		$serverName = $serverName[0];

		if($serverName == '' || $serverName == '' || $serverName == '')
    	{
    		$this->db = $this->load->database('mobileDev', TRUE);
    	}
    	elseif($serverName == 'james')
		{
	    	$this->db = $this->load->database('mobileAdhoc', TRUE);
	    }
	    else
	    {
	    	$this->db = $this->load->database('mobile', TRUE);
	    }

        // Call the Model constructor
        parent::__construct();
    }

    public function getFileData($fileNo)
    {
		$fileQuery = $this->db->query("SELECT * FROM files f LEFT JOIN fileUrls fu ON f.fileNo=fu.fileNo LEFT JOIN filePathInfo fpi ON f.fileNo = fpi.fileNo WHERE f.fileNo = ?", array(intval($fileNo)));
		$fileData = $fileQuery->result();
		if(count($fileData))
		{
			$fileData = $fileData[0];
		}
		else
		{
			$fileData = null;
		}

		return $fileData;
    }
	
	public function getFileDataFromKey($key)
    {
		$fileQuery = $this->db->query("SELECT * FROM files f LEFT JOIN fileUrls fu ON f.fileNo=fu.fileNo LEFT JOIN filePathInfo fpi ON f.fileNo = fpi.fileNo JOIN chatData cd on f.fileNo = cd.imageFileNo WHERE cd.key = ?", array($key));
		$fileData = $fileQuery->result();
		if(count($fileData))
		{
			$fileData = $fileData[0];
		}
		else
		{
			$fileData = null;
		}

		return $fileData;
    }
	
	public function getFileDataFromMessageNo($messageNo)
    {
		$fileQuery = $this->db->query("SELECT * FROM files f LEFT JOIN fileUrls fu ON f.fileNo=fu.fileNo JOIN chatData cd on f.fileNo = cd.imageFileNo LEFT JOIN filePathInfo fpi ON f.fileNo = fpi.fileNo WHERE cd.messageNo = ?", array($messageNo));
		$fileData = $fileQuery->result();
		if(count($fileData))
		{
			$fileData = $fileData[0];
		}
		else
		{
			$fileData = null;
		}

		return $fileData;
    }
}
?>