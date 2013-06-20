<?
class Mission_pool extends CI_Model
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
    
    public function getMissionListByCountryCode($countryCode)
    {
        $this->db->select('missionPool.missionNo, missionPool.description');
        $this->db->from('missionPool');
        $this->db->where('missionPool.enabled', 'Y'); 
        if($countryCode != NULL)
        {
            $this->db->join('fileMetaData', 'missionPool.missionNo = fileMetaData.missionNo');
            $this->db->where('fileMetaData.countryCode', $countryCode);
            $this->db->group_by('missionPool.missionNo');
            $this->db->group_by('fileMetaData.countryCode');
        }
        $query = $this->db->get();
        return $query->result();
    }
}
?>