<?

class Match extends YongoPal
{
	protected $db;
	public $development;
	public $appVersion;

	function __construct()
	{
		$instance = parent::getInstance();
		$this->db = $instance->db;
		$this->development = $instance->development;
		$this->appVersion = $instance->appVersion;
	}
	
	public function request($args=NULL)
	{
		if(!empty($args))
		{
			switch($args['task'])
			{
				case "findMatch":
				{
					$result = $this->findMatch($args['data']);
					break;
				}
				case "getMatchList":
				{
					$result = $this->getMatchList($args['data']);
					break;
				}
				case "countNewMessages":
				{
					$result = $this->countNewMessages($args['data']);
					break;
				}
				case "confirmMatch":
				{
					$result = $this->confirmMatch($args['data']);
					break;
				}
				case "confirmQuickMatch":
				{
					$result = $this->confirmQuickMatch($args['data']);
					break;
				}
				case "declineMatch":
				{
					$result = $this->declineMatch($args['data']);
					break;
				}
				case "declineQuickMatch":
				{
					$result = $this->declineQuickMatch($args['data']);
					break;
				}
				case "cancelQuickMatch":
				{
					$result = $this->cancelQuickMatch($args['data']);
					break;
				}
				case "declineAllMatches":
				{
					$result = $this->declineAllMatches($args['data']);
					break;
				}
				case "exitMatch":
				{
					$result = $this->exitMatch($args['data']);
					break;
				}
				case "muteMatch":
				{
					$result = $this->muteMatch($args['data']);
					break;
				}
				case "unmuteMatch":
				{
					$result = $this->unmuteMatch($args['data']);
					break;
				}
				case "deleteMatch":
				{
					$result = $this->deleteMatch($args['data']);
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
	
	private function findMatch($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		if(isset($requestData->active)) $active = addslashes($requestData->active);
		else $active = null;
		if(isset($requestData->crossPostedPhotoCount)) $crossPostedPhotoCount = addslashes($requestData->crossPostedPhotoCount);
		else $crossPostedPhotoCount = null;
		
		// this data will be pushed to device
		$willPush = 1;
		
		// check active status
		$query = sprintf("select active from members where memberNo = %d", $memberNo);
		$memberDataQuery = $this->db->query($query);
		$memberData = $memberDataQuery->fetch_assoc();

		if($memberData['active'] != 'Y' || $active != 'Y')
		{
			trigger_error("Your profile information is out of sync with the server. Please try saving your profile information again.", E_USER_NOTICE);
		}

		// up multiple match limit by 1 if user cross posted more then 3 photos
		if($crossPostedPhotoCount >= 3)
		{
			$query = sprintf("update memberPrivileges set multipleMatchLimit = multipleMatchLimit+1 where memberNo = %d and multipleMatchLimit < maxMatchLimit", $memberNo);
			$this->db->query($query);
		}

		// if pending match doesn't exist, generate new match
		$activeDate = gmdate("Y-m-d", time());

		// disable local matches for app versions 2 and above
		if($this->appVersion > 1) $allowLocalMatch = 0;
		else $allowLocalMatch = 1;

		$results = array();
		$results['matchList'] = array();
		$query = sprintf("CALL generateMatch(%d, %d, %d, '%s')", $memberNo, $allowLocalMatch, $willPush, $activeDate);
		$newMatchQuery = $this->db->query($query);
		while($row = $newMatchQuery->fetch_assoc())
		{
			if($row['matchNo'] != 0) $results['matchList'][] = $row;
		}
		$newMatchQuery->close();
		$this->db->next_result();
		
		if(count($results['matchList']) == 0)
		{
			$query = sprintf("CALL confirmNotification(%d, 1)", $memberNo);
			$this->db->query($query);
		}

		// backward compatability
		if($this->appVersion <= 199)
		{
			$i = 0;
			foreach($results['matchList'] as $matchSessionData)
			{
				if($matchSessionData['isQuickMatch'] == 'Y' && $matchSessionData['memberCount'] == 1)
				{
					$results['matchList'][$i]['matchNo'] = -2;
				}
				$i++;
			}
		}

		// delete apnQueues
		$query = sprintf("UPDATE apnQueue SET didConfirm = 'Y' WHERE sender IS NULL AND receiver = %d AND pushType = 1 AND STATUS = 'Q' AND DATE_FORMAT(queueDatetime, '%%Y-%%m-%%d') = UTC_DATE();", $memberNo);
		$this->db->query($query);

		return $results;
	}

	private function getMatchList($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		$pendingSessions = $requestData->pendingSessions;
		if(isset($requestData->hasQuickMatch)) $hasQuickMatch = $requestData->hasQuickMatch;
		else $hasQuickMatch = 'N';
		
		// get matchPriority
		$query = sprintf("select matchPriority, multipleMatchLimit from memberPrivileges where memberNo = %d", $memberNo);
		$privilegeQuery = $this->db->query($query);
		$memberPrivilegeData = $privilegeQuery->fetch_assoc();
		$matchPriority = $memberPrivilegeData['matchPriority'];
		$multipleMatchLimit = $memberPrivilegeData['multipleMatchLimit'];

		$query = sprintf("CALL getMatchList(%d, 1)", $memberNo);
		$matchQuery = $this->db->query($query);
		$matchList = array();
		$quickMatchWasSuccessful = FALSE;
		$matchWasSuccessful = FALSE;
		$doesHaveActiveMatch = FALSE;
		$didGetMatch = FALSE;
		while($row = $matchQuery->fetch_assoc())
		{
			if($row['matchStatus'] == 'Y' && $hasQuickMatch == 'Y')
			{
				$quickMatchWasSuccessful = TRUE;
			}

			if(in_array($row['matchNo'], $pendingSessions))
			{
				if($row['matchStatus'] == 'Y' || $row['matchStatus'] == 'N')
				{
					$matchWasSuccessful = TRUE;
				}

				if($row['matchStatus'] == 'M' || $row['matchStatus'] == 'P')
				{
					$didGetMatch = TRUE;
				}
			}
			
			if($row['matchStatus'] == 'Y')
			{
				$doesHaveActiveMatch = TRUE;
			}
			
			// backward compatibility for builds under 200
			if($this->appVersion < 200 && $row['isQuickMatch'] == 'Y' && $row['memberCount'] == 1)
			{
				$row['matchNo'] = -2;
				if($row['matchStatus'] == 'M')
				{
					$row['order'] = -3;
				}
				else if($row['matchStatus'] == 'A')
				{
					$row['order'] = -2;
				}
			}
			
			// quick fix -- don't send match data for auto match user if match is still pending
			if($row['memberNo'] != 0 && $row['isQuickMatch'] == 'Y' && $row['matchStatus'] == 'P')
			{
				$row['memberNo'] = 0;
				$row['email'] = NULL;
				$row['firstName'] = '';
				$row['lastName'] = NULL;
				$row['gender'] = NULL;
				$row['birthday'] = NULL;
				$row['city'] = NULL;
				$row['provinceCode'] = NULL;
				$row['country'] = NULL;
				$row['countryCode'] = NULL;
				$row['timezone'] = NULL;
				$row['timezoneOffset'] = NULL;
				$row['latitude'] = 0;
				$row['longitude'] = 0;
				$row['intro'] = NULL;
				$row['profileImageNo'] = NULL;
				$row['recentMessage'] = NULL;
			}
			
			$matchList[] = $row;
		}
		$matchQuery->close();
		$this->db->next_result();

		if($quickMatchWasSuccessful || $matchWasSuccessful)
		{
			// update apnLog
			$query = sprintf("CALL confirmNotification(%d, 2)", $memberNo);
			$this->db->query($query);
		}
		
		if($doesHaveActiveMatch)
		{
			// update apnLog
			$query = sprintf("CALL confirmNotification(%d, 1)", $memberNo);
			$this->db->query($query);
		}

		if($didGetMatch)
		{
			// delete apnQueues
			$query = sprintf("UPDATE apnQueue SET didConfirm = 'Y' WHERE sender IS NULL AND receiver = %d AND pushType = 1 AND STATUS = 'Q' AND DATE_FORMAT(queueDatetime, '%%Y-%%m-%%d') = UTC_DATE();", $memberNo);
			$this->db->query($query);
		}

		if(count($matchList) > 0)
		{
			$results['matchList'] = $matchList;
		}
		else
		{
			$results['matchList'] = array();
		}

		$results['matchPriority'] = $matchPriority;
		$results['multipleMatchLimit'] = $multipleMatchLimit;
		return $results;
	}
	
	private function countNewMessages($requestData)
	{
		$matchNo = intval($requestData->matchNo);
		$memberNo = intval($requestData->memberNo);
		$results = array();

		// is match number is 0 return updates for all active matches
		if($matchNo == 0)
		{
			$query = sprintf("
				SELECT 
					ms.matchNo,
					COUNT(cd.messageNo) AS newMessages,
					crm.recentMessage
				FROM matchSessions ms
				JOIN matchSessionMembers msm
				ON ms.matchNo = msm.matchNo
				AND msm.memberNo = %d
				LEFT JOIN chatData cd
				ON ms.matchNo = cd.matchNo
				AND cd.receiveDate IS NULL 
				AND cd.receiver = msm.memberNo
				LEFT JOIN cacheRecentMessage crm
				ON ms.matchNo = crm.matchNo
				AND crm.receiver =  msm.memberNo
				WHERE ms.OPEN = 'Y'
				GROUP BY ms.matchNo", $memberNo);
			$countQuery = $this->db->query($query);

			while($matchMessageData = $countQuery->fetch_assoc())
			{
				$results['activeMatches'][] = $matchMessageData;
			}
		}
		else
		{
			$query = sprintf("
				SELECT 
					COUNT(*) as newMessages,
					crm.recentMessage
				FROM chatData cd 
				LEFT JOIN cacheRecentMessage crm
				ON cd.matchNo = crm.matchNo
				AND crm.receiver =  %d
				WHERE cd.receiveDate IS NULL 
				AND cd.matchNo = %d 
				AND cd.receiver = %d
				GROUP BY cd.matchNo", $memberNo, $matchNo, $memberNo);
			$countQuery = $this->db->query($query);
			$results = $countQuery->fetch_assoc();
			
			if(!$results)
			{
				$results['newMessages'] = 0;
			}
		}

		// update badge count and return current count
		$query = sprintf("CALL updateBadgeCount(%d)", $memberNo);
		$this->db->query($query);

		$query = sprintf("SELECT abc.* from apnBadgeCount as abc JOIN members m ON abc.deviceNo = m.deviceNo WHERE m.memberNo = %d;", $memberNo);
		$queryResult = $this->db->query($query);
		$badgeData = $queryResult->fetch_assoc();

		if($badgeData['badgeCount'])
		{
			$results['badgeCount'] = $badgeData['badgeCount'];
			$results['newMatchAlert'] = $badgeData['newMatchAlert'];
			$results['matchSuccessfulAlert'] = $badgeData['matchSuccessfulAlert'];
			$results['newMessageAlert'] = $badgeData['newMessageAlert'];
			$results['newMissionAlert'] = $badgeData['newMissionAlert'];
		}
		else
		{
			$results['badgeCount'] = 0;
			$results['newMatchAlert'] = 0;
			$results['matchSuccessfulAlert'] = 0;
			$results['newMessageAlert'] = 0;
			$results['newMissionAlert'] = 0;
		}

		return $results;
	}
	
	private function confirmMatch($requestData)
	{
		$matchNo = intval($requestData->matchNo);
		$memberNo = intval($requestData->memberNo);
		$memberName = addslashes($requestData->memberName);
		$matchedMemberNo = $requestData->matchedMemberNo;

		// confirm the match
		if($matchNo > 0)
		{
			$query = sprintf("CALL confirmMatch(%d, %d, @success);", $matchNo, $memberNo);
			$this->db->query($query);
			$query = "SELECT @success as success;";
			$queryResult = $this->db->query($query);
			$confirmData = $queryResult->fetch_assoc();
			
			// get partner data
			$query = sprintf("
				SELECT
					m.memberNo,
					mpr.matchPriority,
					DATE_FORMAT(m.regDatetime, '%%Y-%%m-%%d') as regDate
				FROM matchSessionMembers msm 
				JOIN members m 
				ON msm.memberNo = m.memberNo 
				JOIN memberPrivileges mpr
				on msm.memberNo = mpr.memberNo
				WHERE msm.matchNo = %d 
				AND msm.memberNo != %d 
				AND m.newMatchAlert = 'Y'", $matchNo, $memberNo);
			$partnerQuery = $this->db->query($query);
			$partnerData = $partnerQuery->fetch_assoc();
			$partnerNo = $partnerData['memberNo'];
			$matchPriority = $partnerData['matchPriority'];
			$regDate = $partnerData['regDate'];
			
			$results = array();
	
			// push notification if match is successful
			if($confirmData['success'] == 1)
			{
				// send push notification
				if($partnerNo != null)
				{
					$pushMessage = $memberName." is your new guide.\nYou can now start sharing!";
	
					require_once("apn.class.php");
					$apn = NEW APN();
					$apn->newMessage($memberNo, $partnerNo, 2);
					$apn->addMessageAlert($pushMessage);
					$apn->addMessageBadge(1);
					$apn->addMessageCustom('type', 2);
					$apn->addMessageCustom('matchNo', $matchNo);
					$apn->addMessageSound('default');
					$apn->queueMessage();
	
					exec("nohup /usr/bin/php jobs/pushNotifications.php ".$this->development." ".$memberNo." &");
				}
	
				$results['matchStatus'] = 'Y';
			}
			else if($confirmData['success'] == 0)
			{
				// send push notification on match generation if member signed up today
				if($regDate == gmdate("Y-m-d", time()))
				{
					if($partnerNo != null)
					{
						$pushMessage = "New guide available!";
	
						require_once("apn.class.php");
						$apn = NEW APN();
						$apn->newMessage($memberNo, $partnerNo, 1);
						$apn->addMessageAlert($pushMessage);
						$apn->addMessageBadge(1);
						$apn->addMessageCustom('type', 1);
						$apn->addMessageCustom('matchNo', $matchNo);
						$apn->addMessageSound('default');
						$apn->queueMessage();
	
						exec("nohup /usr/bin/php jobs/pushNotifications.php ".$this->development." ".$memberNo." &");
					}
				}
	
				$results['matchStatus'] = 'P';
			}
		}
		// if user confirmed fake match
		else
		{
			// update match priotiry
			$query = sprintf("UPDATE memberPrivileges mpr SET mpr.matchPriority = mpr.matchPriority + 1 WHERE mpr.memberNo = %d", $memberNo);
			$this->db->query($query);
			$results['matchStatus'] = 'N';
		}

		// update apnLog
		$query = sprintf("CALL confirmNotification(%d, 1)", $memberNo);
		$this->db->query($query);
		
		// delete apnQueues
		$query = sprintf("UPDATE apnQueue SET didConfirm = 'Y' WHERE sender IS NULL AND receiver = %d AND pushType = 1 AND STATUS = 'Q' AND DATE_FORMAT(queueDatetime, '%%Y-%%m-%%d') = UTC_DATE();", $memberNo);
		$this->db->query($query);

		$results['request'] = 'confirm';
		return $results;
	}

	private function confirmQuickMatch($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		if(isset($requestData->active)) $active = addslashes($requestData->active);
		else $active = null;

		if($active == 'N')
		{
			trigger_error("Not active user", E_USER_ERROR);
		}
		
		$results = array();
		$results['matchList'] = array();

		$activeDate = gmdate("Y-m-d", time());

		$query = sprintf("CALL confirmQuickMatch(%d, 0, '%s')", $memberNo, $activeDate);
		$quickMatchQuery = $this->db->query($query);

		// if new match was found
		while($row = $quickMatchQuery->fetch_assoc()) 
		{
			$results['matchList'][] = $row;
		}
		$quickMatchQuery->close();
		$this->db->next_result();

		if(count($results['matchList']) > 0)
		{
			foreach($results['matchList'] as $matchData)
			{
				$pushMessage = $matchData['userFirstName']." is your new guide.\nYou can now start sharing!";

				require_once("apn.class.php");
				$apn = NEW APN();
				$apn->newMessage($memberNo, $matchData['memberNo'], 2);
				$apn->addMessageAlert($pushMessage);
				$apn->addMessageBadge(1);
				$apn->addMessageCustom('type', 2);
				$apn->addMessageCustom('matchNo', $matchData['matchNo']);
				$apn->addMessageSound('default');
				$apn->queueMessage();
			}
			exec("nohup /usr/bin/php jobs/pushNotifications.php ".$this->development." ".$memberNo." &");
		}

		// update apnLog
		$query = sprintf("CALL confirmNotification(%d, 1)", $memberNo);
		$this->db->query($query);

		// delete apnQueues
		$query = sprintf("UPDATE apnQueue SET didConfirm = 'Y' WHERE sender IS NULL AND receiver = %d AND pushType = 1 AND STATUS = 'Q' AND DATE_FORMAT(queueDatetime, '%%Y-%%m-%%d') = UTC_DATE();", $memberNo);
		$this->db->query($query);

		return $results;		
	}

	private function declineMatch($requestData)
	{
		$matchNo = intval($requestData->matchNo);
		$memberNo = intval($requestData->memberNo);
		if($requestData->wasDeclined) $wasDeclined = $requestData->wasDeclined;
		else $wasDeclined = 'N';
		$matchedMemberNo = intval($requestData->matchedMemberNo);
		
		if($matchNo > 0)
		{
			$query = sprintf("CALL declineMatch(%d, %d, '%s', @token);", $matchNo, $memberNo, $wasDeclined);
			$this->db->query($query);
		}
		else
		{
			// if user declined a fake match insert match log so they won't get matched with each other in the future
			$query = sprintf("INSERT INTO matchMemberLog (matchNo, memberNo, matchedMemberNo, regDatetime, activeDate) 
				VALUES
				(-1, %d, %d, UTC_TIMESTAMP(), UTC_DATE()),
				(-1, %d, %d, UTC_TIMESTAMP(), UTC_DATE());", $memberNo, $matchedMemberNo, $matchedMemberNo, $memberNo);
			$this->db->query($query);
		}

		// update apnLog
		$query = sprintf("CALL confirmNotification(%d, 1)", $memberNo);
		$this->db->query($query);
		
		// delete apnQueues
		$query = sprintf("UPDATE apnQueue SET didConfirm = 'Y' WHERE sender IS NULL AND receiver = %d AND pushType = 1 AND STATUS = 'Q' AND DATE_FORMAT(queueDatetime, '%%Y-%%m-%%d') = UTC_DATE();", $memberNo);
		$this->db->query($query);

		$results = array();
		$results['request'] = 'decline';
		$results['matchStatus'] = 'N';
		return $results;
	}

	private function declineQuickMatch($requestData)
	{
		$memberNo = intval($requestData->memberNo);

		$results = array();
		$results['matchList'] = array();
		$query = sprintf("CALL declineQuickMatch(%d)", $memberNo);
		$newMatchQuery = $this->db->query($query);
		while($row = $newMatchQuery->fetch_assoc())
		{
			if($row['matchNo'] != 0) $results['matchList'][] = $row;
		}
		$newMatchQuery->close();
		$this->db->next_result();
		
		// update apnLog
		$query = sprintf("CALL confirmNotification(%d, 1)", $memberNo);
		$this->db->query($query);
		
		// delete apnQueues
		$query = sprintf("UPDATE apnQueue SET didConfirm = 'Y' WHERE sender IS NULL AND receiver = %d AND pushType = 1 AND STATUS = 'Q' AND DATE_FORMAT(queueDatetime, '%%Y-%%m-%%d') = UTC_DATE();", $memberNo);
		$this->db->query($query);

		return $results;
	}

	private function cancelQuickMatch($requestData)
	{
		$memberNo = intval($requestData->memberNo);

		$results = array();
		$results['matchList'] = array();
		$query = sprintf("CALL cancelQuickMatch(%d)", $memberNo);
		$newMatchQuery = $this->db->query($query);
		while($row = $newMatchQuery->fetch_assoc())
		{
			if($row['matchNo'] != 0) $results['matchList'][] = $row;
		}
		$newMatchQuery->close();
		$this->db->next_result();

		return $results;
	}

	private function declineAllMatches($requestData)
	{
		$memberNo = intval($requestData->memberNo);

		$query = sprintf("CALL exitAllMatches(%d);", $memberNo);
		$exitQuery = $this->db->query($query);

		$exitMatchMembers = array();
		while($row = $exitQuery->fetch_assoc())
		{
			$exitMatchMembers[] = $row['memberNo'];
		}
		$exitQuery->close();
		$this->db->next_result();

		require_once("apn.class.php");
		$apn = NEW APN();

		foreach($exitMatchMembers as $partnerNo)
		{
			$apn->newMessage($memberNo, $partnerNo, 4);
			$apn->addMessageCustom('type', 4);
			$apn->addMessageCustom('memberNo', $partnerNo);
			$apn->addMessageSound(NULL);
			$apn->queueMessage();
		}

		// send push notification		
		exec("nohup /usr/bin/php jobs/pushNotifications.php ".$this->development." ".$memberNo." &");

		$results = array();
		$results['request'] = 'decline';
		$results['matchStatus'] = 'N';
		return $results;
	}
	
	private function exitMatch($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		$partnerNo = intval($requestData->partnerNo);
		$matchNo = intval($requestData->matchNo);

		$query = sprintf("CALL exitMatch(%d, %d, %d);", $matchNo, $memberNo, $partnerNo);
		$this->db->query($query);

		require_once("apn.class.php");
		$apn = NEW APN();

		$apn->newMessage($memberNo, $partnerNo, 4);
		$apn->addMessageCustom('type', 4);
		$apn->addMessageCustom('memberNo', $partnerNo);
		$apn->addMessageSound(NULL);
		$apn->queueMessage();

		// send push notification		
		exec("nohup /usr/bin/php jobs/pushNotifications.php ".$this->development." ".$memberNo." &");

		$results = array();
		$results['request'] = 'decline';
		$results['matchStatus'] = 'N';
		return $results;
	}

	private function muteMatch($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		$matchNo = intval($requestData->matchNo);

		$query = sprintf("update matchSessionMembers set muted = 'Y' where matchNo = %d and memberNo = %d", $matchNo, $memberNo);
		$this->db->query($query);

		$results = array();
		$results['numRows'] = $this->db->affected_rows;
		return $results;
	}

	private function unmuteMatch($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		$matchNo = intval($requestData->matchNo);

		$query = sprintf("update matchSessionMembers set muted = 'N' where matchNo = %d and memberNo = %d", $matchNo, $memberNo);
		$this->db->query($query);

		$results = array();
		$results['numRows'] = $this->db->affected_rows;
		return $results;
	}
	
	private function deleteMatch($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		if(isset($requestData->partnerNo)) $partnerNo = intval($requestData->partnerNo);
		else $partnerNo = 0;
		$matchNo = intval($requestData->matchNo);

		$updatedRows = 0;
		if($matchNo != 0 && $memberNo != 0)
		{
			$query = sprintf("update 
				matchSessions ms
				join matchSessionMembers msm
				on ms.matchNo = msm.matchNo
				set ms.open = 'N', msm.deleted = 'Y'
				where ms.matchNo = %d 
				and msm.memberNo = %d
				and ms.status = 'X'", $matchNo, $memberNo);
			$this->db->query($query);
			$updatedRows = $this->db->affected_rows;

			if($updatedRows > 0)
			{
				$query = sprintf("update 
				matchMemberLog mml
				set mml.didDelete = 'Y', mml.deleteDatetime = UTC_TIMESTAMP()
				where mml.matchNo = %d 
				and mml.memberNo = %d", $matchNo, $memberNo);
				$this->db->query($query);
				$updatedRows = $updatedRows + $this->db->affected_rows;
				
				if($memberNo != 0 && $partnerNo != 0)
				{
					require_once("apn.class.php");
					$apn = NEW APN();
					$apn->newMessage($memberNo, $partnerNo, 4);
					$apn->addMessageCustom('type', 4);
					$apn->addMessageCustom('memberNo', $partnerNo);
					$apn->addMessageSound(NULL);
					$apn->queueMessage();
			
					// send push notification		
					exec("nohup /usr/bin/php jobs/pushNotifications.php ".$this->development." ".$memberNo." &");
				}
			}
		}

		$results = array();
		$results['numRows'] = $updatedRows;
		return $results;
	}
}
?>