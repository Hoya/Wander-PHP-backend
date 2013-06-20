<?

class Member extends YongoPal
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
				case "checkMemberExists":
				{
					$result = $this->checkMemberExists($args['data']);
					break;
				}
				case "addMember":
				{
					$result = $this->addMember($args['data']);
					break;
				}
				case "login":
				{
					$result = $this->login($args['data']);
					break;
				}
				case "registerDevice":
				{
					$result = $this->registerDevice($args['data']);
					break;
				}
				case "registerNewInstance":
				{
					$result = $this->registerNewInstance($args['data']);
					break;
				}
				case "registerPush":
				{
					$result = $this->registerPush($args['data']);
					break;
				}
				case "updateMemberLocale":
				{
					$result = $this->updateMemberLocale($args['data']);
					break;
				}
				case "updateProfile":
				{
					$result = $this->updateProfile($args['data']);
					break;
				}
                case "updateTimezone":
                {
                    $result = $this->updateTimezone($args['data']);
                    break;
                }
                case "getAnnouncements":
                {
                    $result = $this->getAnnouncements($args['data']);
                    break;
                }
				case "deletePhoto":
				{
					$result = $this->deletePhoto($args['data']);
					break;
				}
				case "uploadPhoto":
				{
					$result = $this->uploadPhoto($args['data']);
					break;
				}
				case "logout":
				{
					$result = $this->logout($args['data']);
					break;
				}
				case "downloadProfileImage":
				{
					$this->downloadProfileImage($args['data']);
					break;
				}
				case "resetPassword":
				{
					$result = $this->resetPassword($args['data']);
					break;
				}
				case "getAllLocations":
				{
					$result = $this->getAllLocations();
					break;
				}
				case "getAccessCode":
				{
					$result = $this->getAccessCode($args['data']);
					break;
				}
				case "setMatchPriority":
				{
					$result = $this->setMatchPriority($args['data']);
					break;
				}
				case "setQuickMatch":
				{
					$result = $this->setQuickMatch($args['data']);
					break;
				}
				case "setMatchLimit":
				{
					$result = $this->setMatchLimit($args['data']);
					break;
				}
				case "setUserSuspended":
				{
					$result = $this->setUserSuspended($args['data']);
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
	
	private function checkMemberExists($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		
		$query = sprintf("select memberNo from members where memberNo = %d", $memberNo);
		$memberQuery = $this->db->query($query);

		if($memberQuery->num_rows > 0)
		{
			$results['memberNo'] = $memberNo;
		}
		else
		{
			$results['memberNo'] = 0;
		}

		return $results;
	}
	
	private function addMember($requestData)
	{
		$udid = addslashes($requestData->udid);
		if(isset($requestData->deviceNo)) $deviceNo = $requestData->deviceNo;
		else $deviceNo = null;
		if(isset($requestData->email)) $email = addslashes(strtolower($requestData->email));
		else $email = null;
		if(isset($requestData->password)) $password = md5($requestData->password);
		else $password = null;
		if(isset($requestData->firstName)) $firstName = addslashes($requestData->firstName);
		else $firstName = null;
		if(isset($requestData->lastName)) $lastName = addslashes($requestData->lastName);
		else $lastName = null;		
		if(isset($requestData->facebookID)) $facebookID = addslashes($requestData->facebookID);
		else $facebookID = null;
		
		if(isset($requestData->locale)) $locale = $requestData->locale;
		else $locale = "";

		// update device token
		if($deviceNo)
		{
			$query = sprintf("update members set deviceNo = null where deviceNo = '%s'", $deviceNo);
			$this->db->query($query);
		}
		
		// check email
		if(!$email)
		{
			trigger_error("You must provide an email address. If you are signing up using Facebook check if you have an email address associated with your account.", E_USER_ERROR);
		}
		
		// check if member exists
		$query = sprintf("select * from members where email = '%s'", $email);
		$emailQuery = $this->db->query($query);
		
		$results = array();

		// if member exists
		if($emailQuery->num_rows > 0)
		{
			$memberData = $emailQuery->fetch_assoc();
			
			// check facebook id and update or do nothing
			if($memberData['facebookID'] && $memberData['facebookID'] == $facebookID)
			{
				// create new instance id
				$query = sprintf("INSERT INTO instances (udid, regDatetime) VALUES ('%s', UTC_TIMESTAMP())", $udid);
				$this->db->query($query);
				$instanceNo = $this->db->insert_id;

				// update member data
				if($deviceNo)
				{
					$query = sprintf("update members set currentInstance = %d, deviceNo = %d, firstName = '%s', lastName = '%s', locale = '%s', updateDatetime = UTC_TIMESTAMP() where memberNo = %d", $instanceNo, $deviceNo, $firstName, $lastName, $locale, $memberData['memberNo']);
				}
				else
				{
					$query = sprintf("update members set currentInstance = %d, firstName = '%s', lastName = '%s', locale = '%s',  updateDatetime = UTC_TIMESTAMP() where memberNo = %d", $instanceNo, $firstName, $lastName, $locale, $memberData['memberNo']);
				}
				
				$this->db->query($query);
				
				// set results
				$results = $memberData;
				if($memberData['profileImage'] == NULL) $results['profileImage'] = 0;
				$results['instanceNo'] = $instanceNo;
			}
			else
			{
				$results['memberNo'] = 0;
			}
		}
		// else add new member
		else
		{
			// create new instance id
			$query = sprintf("INSERT INTO instances (udid, regDatetime) VALUES ('%s', UTC_TIMESTAMP())", $udid);
			$this->db->query($query);
			$instanceNo = $this->db->insert_id;

			// signup with facebook
			if($facebookID)
			{
				if($deviceNo)
				{
					$query = sprintf("insert into members (currentInstance, deviceNo, email, password, firstName, lastName, facebookID, locale, regDatetime) values (%d, %d, '%s', '%s', '%s', '%s', %d, '%s', UTC_TIMESTAMP())", $instanceNo, $deviceNo, $email, $password, $firstName, $lastName, $facebookID, $locale);
				}
				else
				{
					$query = sprintf("insert into members (currentInstance, email, password, firstName, lastName, facebookID, locale, regDatetime) values (%d, '%s', '%s', '%s', '%s', %d, '%s', UTC_TIMESTAMP())", $instanceNo, $email, $password, $firstName, $lastName, $facebookID, $locale);
				}
			}
			// signup with email
			else
			{
				if($deviceNo)
				{
					$query = sprintf("insert into members (currentInstance, deviceNo, email, password, firstName, lastName, locale, regDatetime) values (%d, %d, '%s', '%s', '%s', '%s', '%s', UTC_TIMESTAMP())", $instanceNo, $deviceNo, $email, $password, $firstName, $lastName, $locale);
				}
				else
				{
					$query = sprintf("insert into members (currentInstance, email, password, firstName, lastName, locale, regDatetime) values (%d, '%s', '%s', '%s', '%s', '%s', UTC_TIMESTAMP())", $instanceNo, $email, $password, $firstName, $lastName, $locale);
				}
			}
			$this->db->query($query);
			$memberNo = $this->db->insert_id;

			// add new member to match pool
			$query = sprintf("INSERT INTO matchPool VALUES (%d, 0, 'P', 0, 0, 0, 0, UTC_TIMESTAMP(), NULL)", $memberNo);
			$this->db->query($query);
			
			// add new member to memberPrivileges table
			$query = sprintf("INSERT INTO memberPrivileges VALUES (%d, 5, 'Y', 1, 5)", $memberNo);
			$this->db->query($query);

			// set results
			$results['memberNo'] = $memberNo;
			$results['active'] = null;
			$results['instanceNo'] = $instanceNo;
		}

		return $results;
	}
	
	private function login($requestData)
	{
		$udid = addslashes($requestData->udid);
		$email = addslashes(strtolower($requestData->email));
		$password = md5($requestData->password);
		if(isset($requestData->deviceNo)) $deviceNo = $requestData->deviceNo;
		else $deviceNo = null;
		if(isset($requestData->locale)) $locale = $requestData->locale;
		else $locale = "";

		$query = sprintf("select * from members where email = '%s'", $email);
		
		$memberQuery = $this->db->query($query);

		// check if email exists
		if($memberQuery->num_rows > 0)
		{
			$memberData = $memberQuery->fetch_assoc();

			// check password
			if($memberData['password'] == $password)
			{
				// update device token
				if($deviceNo)
				{
					$query = sprintf("update members set deviceNo = null where deviceNo = %d", $deviceNo);
					$this->db->query($query);
					$query = sprintf("update members set deviceNo = %d, locale = '%s' where memberNo = %d", $deviceNo, $locale, $memberData['memberNo']);
					$this->db->query($query);
				}
			
				// create new instance id
				$query = sprintf("INSERT INTO instances (udid, regDatetime) VALUES ('%s', UTC_TIMESTAMP())", $udid);
				$this->db->query($query);
				$instanceNo = $this->db->insert_id;
			
				// set result
				unset($memberData['profileImage']);
				$results['memberData'] = $memberData;
				$results['instanceNo'] = $instanceNo;
			}
			else
			{
				$results['memberData'] = array();
				$results['error'] = -1;
			}
		}
		else
		{
			$results['memberData'] = array();
			$results['error'] = -2;
		}

		return $results;
	}
	
	private function registerDevice($requestData)
	{
		require_once("apn.class.php");

		$memberNo = intval($requestData->memberNo);
		if(isset($requestData->deviceToken)) $deviceToken = $requestData->deviceToken;
		else $deviceToken = null;

		// update last session date time
		$query = sprintf("UPDATE members SET lastSessionDatetime = UTC_TIMESTAMP() WHERE memberNo = %d", $memberNo);
		$this->db->query($query);

		// activate suspended members
		$query = sprintf("UPDATE matchPool mp SET mp.STATUS = 'P' WHERE mp.STATUS in ('S', 'U') AND mp.memberNo = %d", $memberNo);
		$this->db->query($query);

		$results = array();
		if($deviceToken)
		{
			$apn = new APN();
			$deviceNo = $apn->_registerDevice($requestData);
			$results['updatedRows'] = $this->db->affected_rows;

			// update device number
			if(intval($memberNo) != 0 && intval($deviceNo) != 0)
			{
				$query = sprintf("CALL updateDeviceNo(%d, %d)", $memberNo, $deviceNo);
				$deviceQuery = $this->db->query($query);
				$deviceInfo = $deviceQuery->fetch_assoc();
				$results['deviceNo'] = $deviceNo;
				$results['debug'] = $deviceInfo['debug'];
				$deviceQuery->close();
				$this->db->next_result();
			}
		}
		
		// update badge count and return current count
		$query = sprintf("CALL updateBadgeCount(%d)", $memberNo);
		$this->db->query($query);

		$query = sprintf("SELECT abc.*, m.latitude, m.longitude, m.timezoneOffset, m.active, mpv.quickMatch, mpv.multipleMatchLimit, mpv.maxMatchLimit FROM members m LEFT JOIN apnBadgeCount as abc ON abc.deviceNo = m.deviceNo LEFT JOIN memberPrivileges mpv ON m.memberNo = mpv.memberNo WHERE m.memberNo = %d;", $memberNo);
		$queryResult = $this->db->query($query);
		$memberData = $queryResult->fetch_assoc();
		
		if($memberData['badgeCount'])
		{
			$results['badgeCount'] = $memberData['badgeCount'];
			$results['newMatchAlert'] = $memberData['newMatchAlert'];
			$results['matchSuccessfulAlert'] = $memberData['matchSuccessfulAlert'];
			$results['newMessageAlert'] = $memberData['newMessageAlert'];
			$results['newMissionAlert'] = $memberData['newMissionAlert'];
		}
		else
		{
			$results['badgeCount'] = 0;
			$results['newMatchAlert'] = 0;
			$results['matchSuccessfulAlert'] = 0;
			$results['newMessageAlert'] = 0;
			$results['newMissionAlert'] = 0;
		}
		
		if($memberData['active'])
		{
			$results['active'] = $memberData['active'];
		}
		else
		{
			$results['active'] = 'N';
		}
		
		if($memberData['quickMatch'] && $memberData['multipleMatchLimit'])
		{
			$results['quickMatchEnabled'] = $memberData['quickMatch'];
			$results['matchLimit'] = $memberData['multipleMatchLimit'];
			$results['maxMatchCount'] = $memberData['maxMatchLimit'];
		}
		else
		{
			$results['quickMatchEnabled'] = 'Y';
			$results['matchLimit'] = 1;
			$results['maxMatchCount'] = 2;
		}
		
		if($memberData['timezoneOffset'] == 0 && $memberData['latitude'] != 0 && $memberData['longitude'] != 0)
		{
			$instance = parent::getInstance();
			// get timezone
            $url = sprintf("https://maps.googleapis.com/maps/api/timezone/json?location=%s,%s&timestamp=%f&sensor=false", rawurlencode($memberData['latitude']), rawurlencode($memberData['longitude']), time());
            
			$retry = 0;
            $jsonString = NULL;
			while(!$jsonString && $retry < 3)
			{
			    $jsonString = @file_get_contents($url);
				$retry++;
			}
			$locationData = json_decode($jsonString);
			
			if(isset($locationData->results->timeZoneId))
			{
				$timezone = $locationData->results->timeZoneId;
				
				// update timezone
				if($timezone != null && $timezone != "")
				{
					$dateTime = new DateTime();
                    $dateTimeZone = @new DateTimeZone($timezone);
                    if($dateTimeZone)
                    {
                        $dateTime->setTimeZone($dateTimeZone);
                        $timezone = $dateTime->format('T');
                        $timezoneOffset = $dateTime->format('Z');

                        $query = sprintf("UPDATE members SET timezone = '%s', timezoneOffset = %d WHERE memberNo = %d;", $timezone, $timezoneOffset, $memberNo);
                        $this->db->query($query);
                    }
				}
			}
		}

		return $results;
	}

	private function registerNewInstance($requestData)
	{
		$udid = addslashes($requestData->udid);

		// create new instance id
		$query = sprintf("INSERT INTO instances (udid, regDatetime) VALUES ('%s', UTC_TIMESTAMP())", $udid);
		$this->db->query($query);
		$instanceNo = $this->db->insert_id;
		
		$results = array();
		$results['instanceNo'] = $instanceNo;
		return $results;
	}
	
	private function registerPush($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		if(isset($requestData->newMatchAlert))
		{
			$newMatchAlert = $requestData->newMatchAlert;
			$query = sprintf("update members set newMatchAlert = '%s' where memberNo = %d", $newMatchAlert, $memberNo);
			$this->db->query($query);
		}

		if(isset($requestData->newMissionAlert))
		{
			$newMissionAlert = $requestData->newMissionAlert;
			$query = sprintf("update members set newMissionAlert = '%s' where memberNo = %d", $newMissionAlert, $memberNo);
			$this->db->query($query);
		}
		
		if(isset($requestData->newMessageAlert))
		{
			$newMessageAlert = $requestData->newMessageAlert;
			$query = sprintf("update members set newMessageAlert = '%s' where memberNo = %d", $newMessageAlert, $memberNo);
			$this->db->query($query);
		}
		
		$results = array();
		$results['updatedRows'] = $this->db->affected_rows;
		return $results;
	}
	
	public function updateMemberLocale($requestData)
	{
		$memberNo = $requestData->memberNo;
		$locale = $requestData->locale;

		$query = sprintf("UPDATE members SET locale = '%s' WHERE memberNo = %d", $locale, $memberNo);
		$this->db->query($query);
		
		$results = array();
		$results['updatedRows'] = $this->db->affected_rows;
		return $results;
	}
	
	private function updateProfile($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		if(isset($requestData->password)) $password = md5($requestData->password);
		else $password = null;
		if(isset($requestData->firstName)) $firstName = addslashes($requestData->firstName);
		else $firstName = null;
		if(isset($requestData->lastName)) $lastName = addslashes($requestData->lastName);
		else $lastName = null;
		if(isset($requestData->gender)) $gender = addslashes($requestData->gender);
		else $gender = null;
		if(isset($requestData->birthday)) $birthday = addslashes($requestData->birthday);
		else $birthday = null;
		if(isset($requestData->cityName)) $cityName = addslashes($requestData->cityName);
		else $cityName = null;
		if(isset($requestData->provinceCode)) $provinceCode = addslashes($requestData->provinceCode);
		else $provinceCode = null;
		if(isset($requestData->countryName)) $countryName = addslashes($requestData->countryName);
		else $countryName = null;
		if(isset($requestData->countryCode)) $countryCode = addslashes($requestData->countryCode);
		else $countryCode = null;
		if(isset($requestData->timezone)) $timezone = $requestData->timezone;
		else $timezone = null;
		if(isset($requestData->latitude)) $latitude = floatval($requestData->latitude);
		else $latitude = 0;
		if(isset($requestData->longitude)) $longitude = floatval($requestData->longitude);
		else $longitude = 0;
		if(isset($requestData->intro)) $intro = addslashes($requestData->intro);
		else $intro = null;
		if(isset($requestData->imageIsSet)) $imageIsSet = addslashes($requestData->imageIsSet);
		else $imageIsSet = 'N';
		if(isset($requestData->locale)) $locale = $requestData->locale;
		else $locale = "";
		
		$instance = parent::getInstance();

		// get gps coords if none are given
		if($latitude == 0 && $longitude == 0 && $cityName && $countryName)
		{
			$location = $cityName;
			if($provinceCode != null) $location .= ",".$provinceCode;
			$location .= ",".$countryName;

            $url = sprintf("http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false", rawurlencode($location));

			$retry = 0;
            $jsonString = NULL;
			while(!$jsonString && $retry < 3)
			{
			    $jsonString = @file_get_contents($url);
				$retry++;
			}

			$locationData = json_decode($jsonString);
			
			if(count($locationData->results) > 0)
			{
			    $firstResult = $locationData->results[0];
                $location = $firstResult->geometry->location;
			    
				$latitude = $location->lat;
				$longitude = $location->lng;
			}
		}
		
		// get timezone
		if($timezone == null || $timezone == "")
		{
			if($latitude != 0 && $longitude != 0)
			{
                $url = sprintf("https://maps.googleapis.com/maps/api/timezone/json?location=%s,%s&timestamp=%f&sensor=false", rawurlencode($latitude), rawurlencode($latitude), time());
				$retry = 0;
				$jsonString = NULL;
                while(!$jsonString && $retry < 3)
                {
                    $jsonString = @file_get_contents($url);
                    $retry++;
                }
				$locationData = json_decode($jsonString);
				
				if(isset($locationData->results->timeZoneId))
                {
                    $timezone = $locationData->results->timeZoneId;
				}
			}
		}

		// get timezone offset
		if($timezone != null && $timezone != "")
		{
			$dateTime = new DateTime();
            $dateTimeZone = @new DateTimeZone($timezone);
            if($dateTimeZone)
            {
                $dateTime->setTimeZone($dateTimeZone);
                $timezone = $dateTime->format('T');
                $timezoneOffset = $dateTime->format('Z');
            }
		}
		else
		{
			$timezoneOffset = 0;
		}

		if($firstName && $lastName && $gender && $birthday && $cityName && $countryName && $intro && $imageIsSet == 'Y')
		{
			$active = "Y";
		}
		else
		{
			$active = "N";
		}

		$query = sprintf("update members set firstName = '%s', lastName = '%s', gender = '%s', birthday = '%s', city = '%s', provinceCode = '%s', country = '%s', countryCode = '%s', latitude = %f, longitude = %f, intro = '%s', imageIsSet = '%s', active = '%s', locale = '%s', timezone = '%s', timezoneOffset = %d, updateDatetime = UTC_TIMESTAMP() where memberNo = %d", $firstName, $lastName, $gender, $birthday, $cityName, $provinceCode, $countryName, $countryCode, $latitude, $longitude, $intro, $imageIsSet, $active, $locale, $timezone, $timezoneOffset, $memberNo);
		
		$this->db->query($query);
		$updateProfile = $this->db->affected_rows;
	
		if($active == 'N')
		{
			// remove member from apn queue if member is inactive
			$query = sprintf("CALL removeApnQueue(%d, 1)", $memberNo);
			$this->db->query($query);
		}
		
		$results['active'] = $active;
		$results['profileUpdated'] = $updateProfile;
		return $results;
	}

    private function updateTimezone($requestData)
    {
        $memberNo = intval($requestData->memberNo);
        if(isset($requestData->timezone)) $timezone = $requestData->timezone;
        else $timezone = null;
        
        // get timezone offset
        if($timezone != null && $timezone != "")
        {
            $dateTime = new DateTime();
            $dateTimeZone = @new DateTimeZone($timezone);
            if($dateTimeZone)
            {
                $dateTime->setTimeZone($dateTimeZone);
                $timezone = $dateTime->format('T');
                $timezoneOffset = $dateTime->format('Z');
            }
        }
        
        $query = sprintf("update members set timezone = '%s', timezoneOffset = %d, updateDatetime = UTC_TIMESTAMP() where memberNo = %d", $timezone, $timezoneOffset, $memberNo);
        
        $this->db->query($query);
        $updateProfile = $this->db->affected_rows;

        $results['profileUpdated'] = $updateProfile;
        return $results;
    }
    
    private function getAnnouncements($requestData)
    {
        $query = sprintf("select announcementNo from announcements");
        $announcementQuery = $this->db->query($query);

        $results = array();
        $results['announcements'] = array();
        while($row = $announcementQuery->fetch_assoc())
        {
            $results['announcements'][] = $row;
        }
        return $results;
    }

	private function deletePhoto($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		$query = sprintf("update members set profileImage = 0 where memberNo = %d", $memberNo);
		$this->db->query($query);
		$results['affectedRows'] = $this->db->affected_rows;

		$query = sprintf("update members set imageIsSet = 'N', active = 'N' where memberNo = %d", $memberNo);
		$this->db->query($query);
		
		return $results;
	}
	
	private function uploadPhoto($requestData)
	{
		require_once("phpthumb/ThumbLib.inc.php");

		$fileData = $requestData->fileData;

		if($fileData['file']['size'] > 0)
		{
			$memberNo = intval($requestData->memberNo);

			$fileName = $fileData['file']['name'];
			$tmpName  = $fileData['file']['tmp_name'];
			$fileSize = $fileData['file']['size'];
			$fileType = $fileData['file']['type'];
			$fileName = addslashes($fileName);

			if(filesize($tmpName) == 0)
			{
				trigger_error("Upload failed. Please try again.", E_USER_ERROR);
			}
			
            // do not insert image blob data into database anymore!!!
            /*
			$fp = fopen($tmpName, 'r');
			$content = fread($fp, filesize($tmpName));
			$content = addslashes($content);
			fclose($fp);
            
			$query = "INSERT INTO files (data, fileSize, fileType, regDatetime) VALUES ('".$content."', ".$fileSize.", '".$fileType."', UTC_TIMESTAMP())";
             */

			$query = "INSERT INTO files (fileSize, fileType, regDatetime) VALUES (".$fileSize.", '".$fileType."', UTC_TIMESTAMP())";
			$this->db->query($query);
			$fileNo = $this->db->insert_id;
            
            // write image data to disk file
            $today = date('Y-m-d');
            $containerDir = 'files/'.$today;
            if(!is_dir($containerDir))
            {
                mkdir($containerDir);
            }
            $filePath = $containerDir.'/'.$fileNo.'.dat';
            move_uploaded_file($tmpName, $filePath);
            
            // insert file path data into new table
            $query = "insert into filePathInfo (fileNo, filePath) values ({$fileNo}, '{$filePath}')";
            $this->db->query($query);

			$results = array();
			$results['affectedRows'] = 0;
			if($fileNo != 0)
			{
				$query = sprintf("UPDATE members SET profileImage = %d WHERE memberNo = %d", $fileNo, $memberNo);
				$this->db->query($query);
				$results['affectedRows'] = $this->db->affected_rows;
	
				if($results['affectedRows'] != 0)
				{
					// delete old cache files
					exec(sprintf("/bin/rm -f cache/profile_%d_*_*.jpg", $memberNo));
				}				
			}

			return $results;
		}
	}
	
	private function logout($requestData)
	{
		$memberNo = intval($requestData->memberNo);

		$query = "update members set deviceNo = null where memberNo = ".$memberNo;
		$this->db->query($query);
		
		$results['affectedRows'] = $this->db->affected_rows;
		
		return $results;
	}
	
	private function downloadProfileImage($requestData)
	{
		require_once("phpthumb/ThumbLib.inc.php");

		$memberNo = $requestData->memberNo;
		$width = $requestData->width;
		$height = $requestData->height;
		if(isset($requestData->section)) $section = $requestData->section;
		else $section = null;
		if(isset($requestData->row)) $row = $requestData->row;
		else $row = null;

		if($memberNo && $width && $height)
		{
		    $thisMonth = date('Y-m');
            if(!is_dir('cache/'.$thisMonth))
            {
                mkdir('cache/'.$thisMonth);
            }

			// create cache file if it doesn't exist
			$cacheFileName = sprintf("cache/%s/profile_%d_%d_%d.jpg", $thisMonth, $memberNo, $width, $height);
			if(!file_exists($cacheFileName))
			{
				$createCacheFile = true;
			}
			// re-create cache if file size is 0 or less
			else if(filesize($cacheFileName) <= 0)
			{
				@unlink($cacheFileName);
				$createCacheFile = true;
			}
			else
			{
				$createCacheFile = false;
			}
			
			if($createCacheFile == true)
			{
				$query = sprintf("SELECT m.updateDatetime, fpi.filePath
				        FROM members m 
                        JOIN files f 
                        ON m.profileImage = f.fileNo
                        LEFT JOIN filePathInfo fpi
                        ON f.fileNo = fpi.fileNo
				        WHERE m.memberNo = %d", $memberNo);
				$memberQuery = $this->db->query($query);
				$memberData = $memberQuery->fetch_assoc();

                if($memberData['filePath'])
                {
                    $thumb = PhpThumbFactory::create($memberData['filePath']);
                    $thumbnailImg = $thumb->adaptiveResize ($width, $height);
                    $thumbnailImg->save($cacheFileName, 'JPG');
                }
			}

			// open and read the cache file
			if(file_exists($cacheFileName))
			{
				if(filesize($cacheFileName) == 0)
				{
					trigger_error("Download failed. Please try again.", E_USER_ERROR);
				}
				
				$finfo = finfo_open(FILEINFO_MIME_TYPE); 
				$mime = finfo_file($finfo, $cacheFileName);
	
				header("Content-length: ".filesize($cacheFileName));
				header("Content-type: ".$mime);
				header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
				header("X-Yongopal-Memberno: ".$memberNo);
				header("X-Yongopal-Section: ".$section);
				header("X-Yongopal-Row: ".$row);
				// open and read the cache file
                if($stream = fopen($cacheFileName, 'r'))
                {
                    while(!feof($stream) && connection_status() == 0)
                    {
                        //reset time limit for big files
                        set_time_limit(0);
                        print(fread($stream,1024*8));
                        flush();
                    }
                    fclose($stream);
                }
			}
		}
	}

	private function resetPassword($requestData)
	{
		require("phpmailer/class.phpmailer.php");

		$email = addslashes(strtolower($requestData->email));

		$characters = str_split("0123456789abcdefghijklmnopqrstuvwxyz");
		$randomString = "";
		for ($p = 0; $p < 8; $p++)
		{
			$randomString .= $characters[mt_rand(0, count($characters)-1)];
		}

		$query = sprintf("UPDATE members SET password = md5('%s') WHERE email = '%s' AND facebookID is null", $randomString, $email);
		$this->db->query($query);
		$results = array();
		$results['updatedRows'] = $this->db->affected_rows;
		
		if($results['updatedRows'] == 1)
		{
			$query = sprintf("SELECT * FROM members WHERE email = '%s' AND facebookID is null", $email);
			$memberQuery = $this->db->query($query);
			$memberData = $memberQuery->fetch_assoc();

			$mail = new PHPMailer(false);
			$mail->IsSMTP();
			$mail->SMTPDebug = false;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = "ssl";
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465;
			$mail->Username = "";
			$mail->Password = "";

			$mail->SetFrom('support@wanderwith.us', 'Wander');
			$mail->AddAddress($email, $memberData['firstName']." ".$memberData['lastName']);

			$mail->Subject = "Reset your Wander password";			
			$body = "<p>Hello ".$memberData['firstName']."</p> ";
			$body .= "<p>Your Wander password has been reset to: ".$randomString."<br /> ";
			$body .= "Please use it to login to Wander and feel free to contact us if you have any other problems.</p> ";
			$body .= "<p>Thanks,<br /> The Wander Team</p> ";
			$mail->MsgHTML($body);
			$mail->AltBody = strip_tags($body);

			if(!$mail->Send())
			{
				trigger_error($mail->ErrorInfo, E_USER_ERROR);
			}
		}

		return $results;
	}

	private function getAccessCode($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		
		$query = sprintf("SELECT * FROM memberAccessCodes WHERE memberNo = %d", $memberNo);
		$accessQuery = $this->db->query($query);
		$accessData = $accessQuery->fetch_assoc();
		
		$results = array();
		$results['accessCode'] = $accessData['accessCode'];
		return $results;
	}

	private function setMatchPriority($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		$matchPriority = intval($requestData->matchPriority);
		
		$query = sprintf("UPDATE memberPrivileges SET matchPriority = %d WHERE memberNo = %d", $matchPriority, $memberNo);
		$this->db->query($query);
		
		$results = array();
		$results['updatedRows'] = $this->db->affected_rows;
		return $results;
	}

	private function setQuickMatch($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		$quickMatch = addslashes($requestData->quickMatch);
		
		$query = sprintf("UPDATE memberPrivileges SET quickMatch = '%s' WHERE memberNo = %d", $quickMatch, $memberNo);
		$this->db->query($query);
		
		$results = array();
		$results['updatedRows'] = $this->db->affected_rows;
		return $results;
	}
	
	private function setMatchLimit($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		$matchLimit = intval($requestData->matchLimit);
		
		$query = sprintf("UPDATE memberPrivileges SET multipleMatchLimit = %d WHERE memberNo = %d", $matchLimit, $memberNo);
		$this->db->query($query);

		$query = sprintf("SELECT COUNT(*) as matchCount
								FROM matchSessionMembers msm
								WHERE msm.memberNo = %d
								AND DATE_FORMAT(msm.regDatetime, '%%Y-%%m-%%d') = UTC_DATE()
								AND msm.STATUS != 'M'
								GROUP BY msm.memberNo;", $memberNo);
		$matchCountQuery = $this->db->query($query);
		$queryResult = $matchCountQuery->fetch_assoc();
		$matchCount = $queryResult['matchCount'];
		if($matchLimit <= $matchCount)
		{
			$query = sprintf("UPDATE matchPool SET matchPool.status = 'M' WHERE matchPool.memberNo = %d and matchPool.status = 'P'", $memberNo);
			$this->db->query($query);
		}
		else
		{
			$query = sprintf("UPDATE matchPool SET matchPool.status = 'P' WHERE matchPool.memberNo = %d and matchPool.status != 'P'", $memberNo);
			$this->db->query($query);
		}

		$results = array();
		$results['updatedRows'] = $this->db->affected_rows;
		return $results;
	}
	
	private function setUserSuspended($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		if(isset($requestData->active)) $active = addslashes($requestData->active);
		else $active = null;
		$suspend = addslashes($requestData->suspend);
		
		if($suspend == 'Y')
		{
			$query = sprintf("UPDATE members SET active = 'H' WHERE memberNo = %d", $memberNo);
			$this->db->query($query);
		}
		else
		{
			if($active == null)
			{
				$query = sprintf("SELECT firstName, lastName, gender, birthDay, city, country, intro, imageIsSet FROM members WHERE memberNo = %d", $memberNo);
				$memberDataQuery = $this->db->query($query);
				$memberData = $memberDataQuery->fetch_assoc();
				if($memberData['firstName'] && $memberData['lastName'] && $memberData['gender'] && $memberData['birthDay'] && $memberData['city'] && $memberData['country'] && $memberData['intro'] && $memberData['imageIsSet'] == 'Y')
				{
					$active = 'Y';
				}
				else
				{
					$active = 'N';
				}
			}
			$query = sprintf("UPDATE members SET active = '%s' WHERE memberNo = %d", $active, $memberNo);
			$this->db->query($query);
		}
		
		$results = array();
		$results['updatedRows'] = $this->db->affected_rows;
		return $results;
	}

	private function getAllLocations()
	{
		$locationQuery = $this->db->query("SELECT latitude, longitude, city FROM members WHERE (latitude != 0 OR longitude != 0) AND city != '' GROUP BY ROUND(latitude*0.1), ROUND(longitude*0.1)");

		$results = array();
		$results['locations'] = array();
		while($row = $locationQuery->fetch_assoc())
		{
			$results['locations'][] = $row;
		}

		return $results;
	}
}

?>
