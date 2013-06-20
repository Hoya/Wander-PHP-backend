<?
class chat extends CI_Model
{
    public function __construct()
    {
    	$serverName = explode('.', $_SERVER['SERVER_NAME']);
		$serverName = $serverName[0];

		if($serverName == '' || $serverName == '' || $serverName == '')
    	{
    		$this->db = $this->load->database('mobileDev', TRUE);
    	}
    	elseif($serverName == '')
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

    public function getSharedPhotoMessageData($fileNo, $key)
    {
		$fileQuery = $this->db->query("SELECT * FROM chatData cd JOIN fileUrls fu on cd.imageFileNo = fu.fileNo LEFT JOIN fileMetaData fmd on cd.imageFileNo = fmd.fileNo LEFT JOIN missionPool mp on fmd.missionNo = mp.missionNo WHERE cd.imageFileNo = ? OR cd.key = ?", array(intval($fileNo), $key));
		$fileData = $fileQuery->result();
		
		if(count($fileData) > 0)
		{
			$fileData = $fileData[0];
			
			$memberQuery = $this->db->query("SELECT * FROM members m WHERE m.memberNo IN (?, ?)", array($fileData->sender, $fileData->receiver));
			$memberData = $memberQuery->result();

			foreach($memberData as $member)
			{
				if($member->memberNo == $fileData->sender)
				{
					$fileData->senderFirstName = $member->firstName;
					$fileData->senderLastName = $member->lastName;
					$fileData->senderCity = $member->city;
					$fileData->senderProvince = $member->provinceCode;
					$fileData->senderCountry = $member->country;
					$fileData->senderCountryCode = $member->countryCode;
					$fileData->senderProfileImage = $member->profileImage;
				}
				elseif($member->memberNo == $fileData->receiver)
				{
					$fileData->receiverFirstName = $member->firstName;
					$fileData->receiverLastName = $member->lastName;
					$fileData->receiverProfileImage = $member->profileImage;
				}
			}
		}

		return $fileData;
    }

    public function getImageWithCountryCodeAndMissionNo($countryCode, $missioNo)
    {
        $this->db->select('*');
        $this->db->from('chatData');
        
        if($countryCode != NULL || $missioNo != NULL)
        {
            $this->db->join('fileMetaData', 'chatData.imageFileNo = fileMetaData.fileNo');
        }
        $this->db->join('missionPool', 'fileMetaData.missionNo = missionPool.missionNo', 'left');
        
        if($countryCode != NULL)
        {
            $this->db->where('fileMetaData.countryCode', $countryCode);
        }
        if($missioNo != NULL)
        {
            $this->db->where('fileMetaData.missionNo', $missioNo);
        }
        $this->db->order_by("chatData.imageFileNo", "random");         
        $this->db->limit(1);

        $query = $this->db->get();

        return $query->row();
    }
}
?>