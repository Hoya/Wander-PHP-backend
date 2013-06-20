<?

class Mission extends YongoPal
{
	protected $db;

	function __construct()
	{
		$instance = parent::getInstance();
		$this->db = $instance->db;
	}
	
	public function request($args=NULL)
	{
		if(!empty($args))
		{
			switch($args['task'])
			{
				case "getNewMissions":
				{
					$result = $this->getNewMissions($args['data']);
					break;
				}
				case "checkMission":
				{
					$result = $this->checkMission($args['data']);
					break;
				}
				default:
				{
					trigger_error("No task defined", E_USER_ERROR);
				}
			}
			
			if(!empty($result))
			{
				$instance = parent::getInstance();
				$instance->setResult($result);
			}
		}
	}
	
	private function getNewMissions($requestData)
	{
		$matchNo = intval($requestData->matchNo);
		$memberNo = intval($requestData->memberNo);

		$results = array();
		if($matchNo != 0)
		{
			$query = sprintf("CALL getNewMissions(%d, %d, 3)", $matchNo, $memberNo);
			$missionQuery = $this->db->query($query);
	
			$results = array();
			while($row = $missionQuery->fetch_assoc())
			{
				$results[] = $row;
			}
			$missionQuery->close();
			$this->db->next_result();
		}

		if($memberNo != 0)
		{
			// update apnLog
			$query = sprintf("CALL confirmNotification(%d, 5)", $memberNo);
			$this->db->query($query);
			
			// delete apnQueues
			$query = sprintf("UPDATE apnQueue SET didConfirm = 'Y' WHERE sender IS NULL AND receiver = %d AND pushType = 5 AND STATUS = 'Q' AND DATE_FORMAT(queueDatetime, '%%Y-%%m-%%d') = UTC_DATE();", $memberNo);
			$this->db->query($query);
		}

		return $results;
	}

	private function checkMission($requestData)
	{
		$matchNo = intval($requestData->matchNo);
		$memberNo = intval($requestData->memberNo);
		$missionNo = intval($requestData->missionNo);
		$checked = $requestData->checked;
		
		$query = sprintf("INSERT INTO matchMissionLog (matchNo, memberNo, missionNo, checked, updateDatetime) VALUES (%d, %d, %d, '%s', UTC_TIMESTAMP()) ON DUPLICATE KEY UPDATE checked = '%s', updateDatetime = UTC_TIMESTAMP()", $matchNo, $memberNo, $missionNo, $checked, $checked);
		$this->db->query($query);

		$results['affectedRows'] = $this->db->affected_rows;

		// update apnLog
		$query = sprintf("CALL confirmNotification(%d, 5)", $memberNo);
		$this->db->query($query);
		
		// delete apnQueues
		$query = sprintf("UPDATE apnQueue SET didConfirm = 'Y' WHERE sender IS NULL AND receiver = %d AND pushType = 5 AND STATUS = 'Q' AND DATE_FORMAT(queueDatetime, '%%Y-%%m-%%d') = UTC_DATE();", $memberNo);
		$this->db->query($query);

		return $results;
	}
}
?>