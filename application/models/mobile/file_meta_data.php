<?
class File_meta_data extends CI_Model
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
    
    public function getCountryList()
    {
        $this->db->select('countryName, countryCode');
        $this->db->from('fileMetaData');
        $this->db->where('countryCode IS NOT NULL');
        $this->db->where('countryName IS NOT NULL');
        $this->db->where('countryCode !=', '');
        $this->db->where('countryName !=', '');
        $this->db->group_by("countryCode");
        $this->db->order_by("countryName", "asc"); 
        $query = $this->db->get();
        return $query->result();
    }
}
?>