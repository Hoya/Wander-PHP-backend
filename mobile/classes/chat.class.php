<?

class Chat extends YongoPal
{
	protected $db;
	public $development;

	function __construct()
	{
		$instance = parent::getInstance();
		$this->db = $instance->db;
		$this->development = $instance->development;
	}
	
	public function request($args=NULL)
	{
		if(!empty($args))
		{
			switch($args['task'])
			{
				case "sendMessage":
				{
					$result = $this->sendMessage($args['data']);
					break;
				}
				case "sendPhoto":
				{
					$result = $this->sendPhoto($args['data']);
					break;
				}
				case "downloadPhoto":
				{
					$result = $this->downloadPhoto($args['data']);
					break;
				}
				case "checkNewMessages":
				{
					$result = $this->checkNewMessages($args['data']);
					break;
				}
				case "getNewMessages":
				{
					$result = $this->getNewMessages($args['data']);
					break;
				}
				case "getAllMessages":
				{
					$result = $this->getAllMessages($args['data']);
					break;
				}
				case "getMessageData":
				{
					$result = $this->getMessageData($args['data']);
					break;
				}
				case "confirmReceived":
				{
					$result = $this->confirmReceived($args['data']);
					break;
				}
				case "confirmCrossPost":
				{
					$result = $this->confirmCrossPost($args['data']);
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

	private function sendMessage($requestData)
	{
		$key = $requestData->key;
		$matchNo = intval($requestData->matchNo);
		$memberNo = intval($requestData->memberNo);
		$partnerNo = intval($requestData->partnerNo);
		$firstName = $requestData->firstName;
		$message = $requestData->message;
		if(isset($requestData->resend)) $resend = $requestData->resend;
		else $resend = null;
		
		// don't send if user is no longer matched
		if(!$this->checkMatchStatus($matchNo))
		{
			$results['key'] = 0;
			$results['messageNo'] = 0;
			$results['sendDate'] = null;
			return $results;
		}

		// don't send messages that don't have a key
		if($key == null)
		{
	
			$results['key'] = null;
			$results['messageNo'] = 0;
			$results['sendDate'] = null;
			return $results;
		}

		// check if messege is to be resent
		if($resend == "Y")
		{
			$query = sprintf("select messageNo, sendDate, receiveDate from chatData where `key` = '%s'", $key);
			$confirmQuery = $this->db->query($query);
			
			if($confirmQuery->num_rows != 0)
			{
				$chatData = $confirmQuery->fetch_assoc();
				$messageNo = $chatData['messageNo'];
				$sendDate = $chatData['sendDate'];
	
				if($messageNo != 0 && $messageNo != null)
				{
					if($chatData['receiveDate'])
					{
						$didReceive = true;
					}
					else
					{
						$didReceive = false;
					}
					
					$results['key'] = $key;
					$results['messageNo'] = $messageNo;
					$results['sendDate'] = $sendDate;
					return $results;
				}
			}
		}

		// detect language
		$instance = parent::getInstance();
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, "http://api.microsofttranslator.com/V2/Http.svc/Detect?appId=".$instance->bingKey."&text=".rawurlencode($message));
        $returned = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$detectedLanguage = null;
        if($code == 201 || $code == 200)
        {
        	$languageData = simplexml_load_string($returned);
			if($languageData[0])
			{
				$detectedLanguage = $languageData[0];
			}
        }

		$sendDate = gmdate("Y-m-d H:i:s", time());
		if($detectedLanguage != null)
		{
			$query = sprintf("insert ignore into chatData 
				(matchNo, `key`, sender, receiver, message, detectedLanguage, sendDate) 
				values
				(%d, '%s', %d, %d, '%s', '%s', '%s')
				", $matchNo, $key, $memberNo, $partnerNo, addslashes($message), $detectedLanguage, $sendDate);
		}
		else
		{
			$query = sprintf("insert ignore into chatData 
				(matchNo, `key`, sender, receiver, message, sendDate) 
				values
				(%d, '%s', %d, %d, '%s', '%s')
				", $matchNo, $key, $memberNo, $partnerNo, addslashes($message), $sendDate);
		}

		$sendQuery = $this->db->query($query);
		$messageNo = $this->db->insert_id;

		if($messageNo == 0 || $messageNo == null)
		{
			// create new instance id
			$instance = parent::getInstance();
			$keyParts = explode("-", $key);
			$instanceNo = intval($keyParts[0]);
			$newKey = $instanceNo."-".intval(microtime(true)*1000);

			if($detectedLanguage != null)
			{
				$query = sprintf("insert into chatData 
					(matchNo, `key`, sender, receiver, message, detectedLanguage, sendDate) 
					values
					(%d, '%s', %d, %d, '%s', '%s', '%s')
					", $matchNo, $newKey, $memberNo, $partnerNo, addslashes($message), $detectedLanguage, $sendDate);
			}
			else
			{
				$query = sprintf("insert into chatData 
					(matchNo, `key`, sender, receiver, message, sendDate) 
					values
					(%d, '%s', %d, %d, '%s', '%s')
					", $matchNo, $newKey, $memberNo, $partnerNo, addslashes($message), $sendDate);
			}

			$sendQuery = $this->db->query($query);
			$messageNo = $this->db->insert_id;
		}

		if($messageNo != 0 && $messageNo != null)
		{
			// get partner info for update
			$query = sprintf("SELECT m.city, m.country, m.timezoneOffset, m.newMessageAlert, msm.muted, ms.status, ap.appVersion
				FROM members m 
				JOIN apnDevices ap
				ON m.deviceNo = ap.deviceNo
				JOIN matchSessionMembers msm
				ON m.memberNo = msm.memberNo
				JOIN matchSessions ms
				ON msm.matchNo = ms.matchNo
				AND msm.matchNo = %d
				WHERE m.memberNo = %d", $matchNo, $partnerNo);
			$memberQuery = $this->db->query($query);
			$memberData = $memberQuery->fetch_assoc();
			$city = $memberData['city'];
			$country = $memberData['country'];
			$timezoneOffset = $memberData['timezoneOffset'];
			$newMessageAlert = $memberData['newMessageAlert'];
			$muted = $memberData['muted'];
			$matchStatus = $memberData['status'];
			$appVersion = $memberData['appVersion'];

			if($partnerNo != null && $newMessageAlert == 'Y')
			{
				if($matchStatus != 'Y' && $appVersion < 168)
				{
					$pushMessage = $firstName." sent a message!\nYou can reply if you upgrade to the latest version of Wander!";
				}
				else if(mb_strlen($message, "UTF-8") > 50)
				{
					$pushMessage = $firstName.": ".mb_substr($message, 0, 50, "UTF-8");
				}
				else
				{
					$pushMessage = $firstName.": ".$message;
				}

				require_once("apn.class.php");
				$apn = NEW APN();
				$apn->newMessage($memberNo, $partnerNo, 3);
				$apn->addMessageBadge(1);
				$apn->addMessageCustom('type', 3);
				$apn->addMessageCustom('matchNo', $matchNo);
				if($muted == 'N')
				{
					$apn->addMessageAlert($pushMessage);
					$apn->addMessageCustom('messageText', $message);
					$apn->addMessageSound('default');
				}
				$apn->queueMessage();
				$apn->processQueue($memberNo);
				
				exec("nohup /usr/bin/php jobs/pushNotifications.php ".$this->development." ".$memberNo." &");
			}

			$results['key'] = $key;
			$results['messageNo'] = $messageNo;
			$results['sendDate'] = $sendDate;
			$results['city'] = $city;
			$results['country'] = $country;
			$results['timezoneOffset'] = $timezoneOffset;
		}
		else
		{
			$results['key'] = $key;
			$results['messageNo'] = 0;
			$results['sendDate'] = null;
		}

		return $results;
	}

	private function sendPhoto($requestData)
	{
		$key = $requestData->key;
		$matchNo = intval($requestData->matchNo);
		$memberNo = intval($requestData->memberNo);
		$partnerNo = intval($requestData->partnerNo);
		if(isset($requestData->missionNo)) $missionNo = addslashes($requestData->missionNo);
		else $missionNo = 0;
		if(isset($requestData->caption)) $caption = addslashes($requestData->caption);
		else $caption = null;
		if(isset($requestData->latitude)) $latitude = floatval($requestData->latitude);
		else $latitude = null;
		if(isset($requestData->longitude)) $longitude = floatval($requestData->longitude);
		else $longitude = null;
		if(isset($requestData->cityName)) $cityName = addslashes($requestData->cityName);
		else $cityName = null;
		if(isset($requestData->provinceName)) $provinceName = addslashes($requestData->provinceName);
		else $provinceName = null;
		if(isset($requestData->provinceCode)) $provinceCode = addslashes($requestData->provinceCode);
		else $provinceCode = null;
		if(isset($requestData->countryName)) $countryName = addslashes($requestData->countryName);
		else $countryName = null;
		if(isset($requestData->countryCode)) $countryCode = addslashes($requestData->countryCode);
		else $countryCode = null;
		if(isset($requestData->locationName)) $locationName = addslashes($requestData->locationName);
		else $locationName = null;
		if(isset($requestData->locationId)) $locationId = addslashes($requestData->locationId);
		else $locationId = null;
		$firstName = $requestData->firstName;
		$fileData = $requestData->fileData;
		if(isset($requestData->resend)) $resend = $requestData->resend;
		else $resend = null;

		// don't send if user is no longer matched
		if(!$this->checkMatchStatus($matchNo))
		{
			$results['key'] = 0;
			$results['messageNo'] = 0;
			$results['sendDate'] = null;
			return $results;
		}

		// don't send messages that don't have a key
		if($key == null)
		{
			$results['key'] = 0;
			$results['messageNo'] = 0;
			$results['sendDate'] = null;
			return $results;
		}

		$query = sprintf("select cd.messageNo, cd.sendDate, cd.imageFileNo, fu.url from chatData cd left join fileUrls fu on cd.key = fu.key where cd.key = '%s'", $key);
		$confirmQuery = $this->db->query($query);
		
		if($confirmQuery->num_rows != 0)
		{
			$messageData = $confirmQuery->fetch_assoc();
			$messageNo = $messageData['messageNo'];
			$sendDate = $messageData['sendDate'];
			$fileNo = $messageData['imageFileNo'];
			$url = $messageData['url'];

			// if messege is to be resent
			if($resend == "Y" && $fileNo != 0 && $fileNo != null)
			{
				if($url == null || $url == '')
				{
					$url = $this->createShortUrl($messageNo, $fileNo, $key);
				}

				$results['key'] = $key;
				$results['messageNo'] = $messageNo;
				$results['sendDate'] = $sendDate;
				$results['url'] = $url;
				return $results;
			}
		}

		if($fileData['file']['size'] > 0 && file_exists($fileData['file']['tmp_name']))
		{
			if(!isset($messageNo))
			{
				$sendDate = gmdate("Y-m-d H:i:s", time());
				$query = sprintf("insert ignore into chatData 
				(matchNo, `key`, sender, receiver, sendDate) 
				values
				(%d, '%s', %d, %d, '%s')
				", $matchNo, $key, $memberNo, $partnerNo, $sendDate);
				
				$this->db->query($query);
				$messageNo = $this->db->insert_id;
				$affectedRows = $this->db->affected_rows;

				if($messageNo == 0 || $messageNo == null)
				{
					// create new instance id
					$instance = parent::getInstance();
					$keyParts = explode("-", $key);
					$instanceNo = intval($keyParts[0]);
					$newKey = $instanceNo."-".intval(microtime(true)*1000);

					$query = sprintf("insert into chatData 
					(matchNo, `key`, sender, receiver, sendDate) 
					values
					(%d, '%s', %d, %d, '%s')
					", $matchNo, $newKey, $memberNo, $partnerNo, $sendDate);

					$this->db->query($query);
					$messageNo = $this->db->insert_id;
					$affectedRows = $this->db->affected_rows;
				}
			}
			
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
             */

			if(isset($fileNo) && $fileNo != null)
			{
			    // do not insert image blob data into database anymore!!!
			    /*
				$query = "update files 
					set data = '{$content}', fileSize = {$fileSize}, fileType = '{$fileType}'
					where fileNo = {$fileNo}";
                 */
                $query = "update files 
                    set fileSize = {$fileSize}, fileType = '{$fileType}'
                    where fileNo = {$fileNo}";
                $this->db->query($query);
			}
			else
			{
			    // do not insert image blob data into database anymore!!!
			    /*
				$query = "insert into files
				(data, fileSize, fileType, regDatetime)
				values
				('{$content}', {$fileSize}, '{$fileType}', UTC_TIMESTAMP())";
                 */
                 
                $query = "insert into files
                (fileSize, fileType, regDatetime)
                values
                ({$fileSize}, '{$fileType}', UTC_TIMESTAMP())";
				$this->db->query($query);
				$fileNo = $this->db->insert_id;
				
				$query = $query = sprintf("update chatData set imageFileNo = %d where messageNo = %d", $fileNo, $messageNo);
				$this->db->query($query);
			}
			
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
			$query = "insert into filePathInfo
                (fileNo, filePath)
                values
                ({$fileNo}, '{$filePath}')";
            $this->db->query($query);

			if($missionNo != 0 || $caption != null || ($latitude != null && $longitude != null))
			{
				if($missionNo != 0)
				{
					$query = sprintf("INSERT INTO fileMetaData
						(fileNo, missionNo, caption, latitude, longitude, cityName, provinceName, provinceCode, countryName, countryCode, locationName, locationId)
						VALUES
						(%d, %d, '%s', %f, %f, '%s', '%s', '%s', '%s', '%s', '%s', '%s')
						ON DUPLICATE KEY UPDATE 
						missionNo = %d,
						caption = '%s',
						latitude = %f,
						longitude = %f,
						cityName = '%s',
						provinceName = '%s',
						provinceCode = '%s',
						countryName = '%s',
						countryCode = '%s',
						locationName = '%s',
						locationId = '%s'",
						$fileNo, $missionNo, $caption, $latitude, $longitude, $cityName, $provinceName, $provinceCode, $countryName, $countryCode, $locationName, $locationId,
						$missionNo, $caption, $latitude, $longitude, $cityName, $provinceName, $provinceCode, $countryName, $countryCode, $locationName, $locationId);

					// update mission log
					$query2 = sprintf("INSERT INTO matchMissionLog 
						(matchNo, memberNo, missionNo, fileNo, checked, updateDatetime)
						VALUES 
						(%d, %d, %d, %d, 'Y', UTC_TIMESTAMP())
						ON DUPLICATE KEY UPDATE 
						checked = 'Y',
						updateDatetime = UTC_TIMESTAMP()",
						$matchNo, $memberNo, $missionNo, $fileNo);
					$this->db->query($query2);

					// update apnLog
					$query3 = sprintf("CALL confirmNotification(%d, 5)", $memberNo);
					$this->db->query($query3);
				}
				else
				{
					$query = sprintf("INSERT INTO fileMetaData
						(fileNo, caption, latitude, longitude, cityName, provinceName, provinceCode, countryName, countryCode, locationName, locationId)
						VALUES
						(%d, '%s', %f, %f, '%s', '%s', '%s', '%s', '%s', '%s', '%s')
						ON DUPLICATE KEY UPDATE 
						caption = '%s',
						latitude = %f,
						longitude = %f,
						cityName = '%s',
						provinceName = '%s',
						provinceCode = '%s',
						countryName = '%s',
						countryCode = '%s',
						locationName = '%s',
						locationId = '%s'",
						$fileNo, $caption, $latitude, $longitude, $cityName, $provinceName, $provinceCode, $countryName, $countryCode, $locationName, $locationId,
						$caption, $latitude, $longitude, $cityName, $provinceName, $provinceCode, $countryName, $countryCode, $locationName, $locationId);
				}
				$this->db->query($query);
			}
			
			if($messageNo != 0)
			{
				$query = sprintf("SELECT m.city, m.country, m.timezoneOffset, m.newMessageAlert, msm.muted, ms.status, ap.appVersion
					FROM members m 
					JOIN apnDevices ap
					ON m.deviceNo = ap.deviceNo
					JOIN matchSessionMembers msm
					ON m.memberNo = msm.memberNo
					JOIN matchSessions ms
					ON msm.matchNo = ms.matchNo
					AND msm.matchNo = %d
					WHERE m.memberNo = %d", $matchNo, $partnerNo);
				$memberQuery = $this->db->query($query);
				$memberData = $memberQuery->fetch_assoc();
				$newMessageAlert = $memberData['newMessageAlert'];
				$city = $memberData['city'];
				$country = $memberData['country'];
				$timezoneOffset = $memberData['timezoneOffset'];
				$muted = $memberData['muted'];
				$matchStatus = $memberData['status'];
				$appVersion = $memberData['appVersion'];

				if(!isset($url))
				{
					if(isset($newKey))
					{
						$url = $this->createShortUrl($messageNo, $fileNo, $newKey);
					}
					else
					{
						$url = $this->createShortUrl($messageNo, $fileNo, $key);
					}
				}
				else if($url == null || $url == '')
				{
					if(isset($newKey))
					{
						$url = $this->createShortUrl($messageNo, $fileNo, $newKey);
					}
					else
					{
						$url = $this->createShortUrl($messageNo, $fileNo, $key);
					}
				}

				if($partnerNo != null && $newMessageAlert == 'Y')
				{
					if($matchStatus != 'Y' && $appVersion < 168)
					{
						$pushMessage = $firstName." shared a photo!\nYou can reply if you upgrade to the latest version of Wander!";
						$message = $firstName." shared a photo!";
					}
					else 
					{
						$pushMessage = $firstName." shared a photo!";
						$message = $firstName." shared a photo!";
					}

					require_once("apn.class.php");
					$apn = NEW APN();
					$apn->newMessage($memberNo, $partnerNo, 3);
					$apn->addMessageBadge(1);
					$apn->addMessageCustom('type', 3);
					$apn->addMessageCustom('matchNo', $matchNo);
					if($muted == 'N')
					{
						$apn->addMessageAlert($pushMessage);
						$apn->addMessageCustom('messageText', $message);
						$apn->addMessageSound('default');
					}
					$apn->queueMessage();
					$apn->processQueue($memberNo);
					
					exec("nohup /usr/bin/php jobs/pushNotifications.php ".$this->development." ".$memberNo." &");
				}

				$results['key'] = $key;
				$results['messageNo'] = $messageNo;
				$results['sendDate'] = $sendDate;
				$results['city'] = $city;
				$results['country'] = $country;
				$results['timezoneOffset'] = $timezoneOffset;
				$results['url'] = $url;
			}
			else
			{
				$results['key'] = $key;
				$results['messageNo'] = 0;
				$results['sendDate'] = null;
			}
		}
		else
		{
			trigger_error("Upload failed. Please try again.", E_USER_ERROR);
		}

		return $results;
	}
	
	private function createShortUrl($messageNo, $fileNo, $key)
	{
		// create short url
		$instance = parent::getInstance();
		$params = str_replace("=", "", base64_encode($messageNo."|".$instance->development));

		$imageFileCode = base64_encode($key);
		$imageFileCode = str_replace('=', '', $imageFileCode);
		$photoUrl = urlencode("http://".$_SERVER['SERVER_NAME']."/viewPhoto/index/".$imageFileCode);

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, "http://api.bit.ly/v3/shorten?login=some_id=".$instance->bitlyKey."&domain=some_domain&longUrl=".$photoUrl);
        $returned = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$shortUrl = null;
        if($code == 201 || $code == 200)
        {
        	$bitlyData = json_decode($returned);
			$shortUrl = $bitlyData->data->url;

			$query = sprintf("insert ignore into fileUrls (fileNo, `key`, url) values (%d, '%s', '%s')", $fileNo, $key, $shortUrl);
			$this->db->query($query);
        }
        else
        {
        	trigger_error("Failed to create URL", E_USER_ERROR);
		}
		
		return $shortUrl;
	}
	
	private function downloadPhoto($requestData)
	{
		require_once("phpthumb/ThumbLib.inc.php");

		$messageNo = intval($requestData->messageNo);
		$width = intval($requestData->width);
		$height = intval($requestData->height);

		if($messageNo)
		{
		    $thisMonth = date('Y-m');
            if(!is_dir('cache/'.$thisMonth))
            {
                mkdir('cache/'.$thisMonth);
            }

			// create cache file is it doesn't exist
			if($width == 0 && $height == 0)
			{
				$cacheFileName = sprintf("cache/%s/chat_%d_full.jpg", $thisMonth, $messageNo);
			}
			else
			{
				$cacheFileName = sprintf("cache/%s/chat_%d_%d_%d.jpg", $thisMonth, $messageNo, $width, $height);
			}

			$query = sprintf("select cd.key, f.*, fu.url, fpi.filePath 
			         FROM chatData cd 
			         JOIN files f 
			         ON cd.imageFileNo = f.fileNo 
			         LEFT JOIN fileUrls fu 
			         ON f.fileNo = fu.fileNo
			         LEFT JOIN filePathInfo fpi
			         ON f.fileNo = fpi.fileNo 
			         WHERE cd.messageNo = %d", $messageNo);
			$messageQuery = $this->db->query($query);
			$messageData = $messageQuery->fetch_assoc();

			if(!file_exists($cacheFileName))
			{
				$createCacheFile = true;
			}
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
				$tempFile = sprintf("/var/ram/%s", md5($cacheFileName));
                copy($messageData['filePath'], $tempFile);

				$image = imagecreatefromjpeg($tempFile);
				$exif = exif_read_data($tempFile, 0, true);
				
				if(isset($exif['IFD0']))
				{
					if(isset($exif['IFD0']['Orientation']))
					{
						$orientation = $exif['IFD0']['Orientation'];
					}
				}

				if(isset($orientation))
				{
					switch($orientation)
					{
				        case 2: // horizontal flip
							$image = parent::flipImage($image, 1);
				        break;
	
				        case 3: // 180 rotate left
							$image = imagerotate($image, 180, 0);
				        break;
	
				        case 4: // vertical flip
							$image = parent::flipImage($image, 2);
				        break;
	
				        case 5: // vertical flip + 90 rotate right
							$image = parent::flipImage($image, 2);
							$image = imagerotate($image, -90, 0);
				        break;
	
				        case 6: // 90 rotate right
							$image = imagerotate($image, -90, 0);
				        break;
	
				        case 7: // horizontal flip + 90 rotate right
							$image = parent::flipImage($image, 1);
							$image = imagerotate($image, -90, 0);
				        break;
	
				        case 8:    // 90 rotate left
							$image = imagerotate($image, 90, 0);
				        break;
						
						default: break; // nothing
					}
				}

				imagejpeg($image, $tempFile);
				imagedestroy($image);

				if($width == 0 && $height == 0)
				{
					copy($tempFile, $cacheFileName);
				}
				else
				{
					$thumb = PhpThumbFactory::create($tempFile);
					$thumb->setOptions(array("resizeUp" => true));
					$thumbnailImg = $thumb->adaptiveResize($width, $height);
					$thumbnailImg->save($cacheFileName, 'JPG');
				}

				@unlink($tempFile);
			}

			if(filesize($cacheFileName) == 0)
			{
				trigger_error("Download failed. Please try again.", E_USER_ERROR);
			}

			$key = $messageData['key'];

			$finfo = finfo_open(FILEINFO_MIME_TYPE); 
			$mime = finfo_file($finfo, $cacheFileName);
			$shortUrl = $messageData['url'];

			header("Content-length: ".filesize($cacheFileName));
			header("Content-type: ".$mime);
			header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
			header("Last-Modified: ".gmdate("D, d M Y H:i:s", strtotime($messageData['regDatetime']))." GMT");
			header("X-Yongopal-Messageno: ".$messageNo);
			header("X-Yongopal-Key: ".$key);
			header("X-Yongopal-Shorturl: ".$shortUrl);
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
		else
		{
			trigger_error("member number is null", E_USER_ERROR);
		}
	}

	private function checkNewMessages($requestData)
	{
		$matchNo = intval($requestData->matchNo);
		$memberNo = intval($requestData->memberNo);

		$query = sprintf("SELECT
							COUNT(cd.messageNo) AS newMessages, 
							m.city, 
							m.country, 
							m.timezoneOffset 
						FROM members m
						JOIN matchSessionMembers msm
						ON m.memberNo = msm.memberNo
						AND msm.memberNo != %d
						AND msm.matchNo = %d
						LEFT JOIN chatData cd 
						ON cd.sender = m.memberNo 
						AND cd.sendDate IS NOT NULL 
						AND cd.receiveDate IS NULL", $memberNo, $matchNo);
		$chatQuery = $this->db->query($query);
		$results = $chatQuery->fetch_assoc();
		
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
	
	private function getNewMessages($requestData)
	{
		$matchNo = intval($requestData->matchNo);
		$memberNo = intval($requestData->memberNo);

		$query = sprintf("select * from chatData cd LEFT JOIN fileMetaData fmd ON cd.imageFileNo = fmd.fileNo LEFT JOIN missionPool mp ON fmd.missionNo = mp.missionNo where cd.matchNo = %d and cd.sender != %d and cd.sendDate is not null and cd.receiveDate is null order by cd.sendDate", $matchNo, $memberNo);
		$chatQuery = $this->db->query($query);

		$chatData = array();
		while($rows = $chatQuery->fetch_assoc())
		{
			if($rows['message']) $rows['message'] = stripslashes($rows['message']);
			else $rows['message'] = null;
			if($rows['imageFileNo'] != null) $rows['isImage'] = 1;
			else $rows['isImage'] = 0;
			$chatData[] = $rows;
		}
		
		$results = array();
		if(count($chatData) != 0)
		{
			$results['chatData'] = $chatData;
		}
		else
		{
			$results['chatData'] = array();
		}

		return $results;
	}
	
	private function getAllMessages($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		if(isset($requestData->matchNo)) $matchNo = $requestData->matchNo;
		else $matchNo = null;
		if(isset($requestData->offsetMessageNo)) $offsetMessageNo = $requestData->offsetMessageNo;
		else $offsetMessageNo = null;

		$query = sprintf("select * from chatData cd LEFT JOIN fileMetaData fmd ON cd.imageFileNo = fmd.fileNo LEFT JOIN missionPool mp ON fmd.missionNo = mp.missionNo where (cd.sender = %d and cd.sendDate is not null) OR (cd.receiver = %d and cd.receiveDate is not null) order by cd.sendDate", $memberNo, $memberNo);
		if($matchNo != null)
		{
			if($offsetMessageNo == 0)
			{
				$query = sprintf("select * from chatData cd LEFT JOIN fileMetaData fmd ON cd.imageFileNo = fmd.fileNo LEFT JOIN missionPool mp ON fmd.missionNo = mp.missionNo where ((cd.sender = %d and cd.sendDate is not null) OR (cd.receiver = %d and cd.receiveDate is not null)) and cd.matchNo = %d order by cd.sendDate desc limit 26", $memberNo, $memberNo, $matchNo);
			}
			else
			{
				$query = sprintf("select * from chatData cd LEFT JOIN fileMetaData fmd ON cd.imageFileNo = fmd.fileNo LEFT JOIN missionPool mp ON fmd.missionNo = mp.missionNo where ((cd.sender = %d and cd.sendDate is not null) OR (cd.receiver = %d and cd.receiveDate is not null)) and cd.matchNo = %d and cd.messageNo < %d order by cd.sendDate desc limit 26", $memberNo, $memberNo, $matchNo, $offsetMessageNo);
			}
		}

		$chatQuery = $this->db->query($query);

		$chatData = array();
		while($rows = $chatQuery->fetch_assoc())
		{
			if($rows['message']) $rows['message'] = stripslashes($rows['message']);
			else $rows['message'] = null;
			if($rows['imageFileNo'] != null) $rows['isImage'] = 1;
			else $rows['isImage'] = 0;
			$chatData[] = $rows;
		}

		$results = array();
		if($matchNo != null && count($chatData) == 26)
		{
			$results['nextPageExists'] = 'Y';
			unset($chatData[25]);
		}

		if(count($chatData) != 0)
		{
			$results['chatData'] = $chatData;
		}
		else
		{
			$results['chatData'] = array();
		}
		
		return $results;
	}

	private function getMessageData($requestData)
	{
		if(isset($requestData->key)) $key = $requestData->key;
		else $key = '';
		if(isset($requestData->messageNo)) $messageNo = $requestData->messageNo;
		else $messageNo = null;

		$query = sprintf("select * from chatData where `key` = '%s' OR messageNo = %d", $key, $messageNo);
		$chatQuery = $this->db->query($query);
		$chatData = $chatQuery->fetch_assoc();

		$results = array();
		if(count($chatData) != 0)
		{
			$results = $chatData;
		}
		else
		{
			$results = array();
		}

		return $results;
	}

	private function confirmReceived($requestData)
	{
		$memberNo = intval($requestData->memberNo);
		$messageNoArray = $requestData->receivedMessages;

		if(count($messageNoArray) > 0)
		{
			$messageNoList = implode(',', $messageNoArray);
			$query = sprintf("update chatData set receiveDate = UTC_TIMESTAMP() where messageNo in (%s)", $messageNoList);
			$this->db->query($query);
			$results['numRows'] = $this->db->affected_rows;
			
			// update apnLog
			$query = sprintf("CALL confirmNotification(%d, 3)", $memberNo);
			$this->db->query($query);
			$this->db->close();
		}
		else
		{
			$results['numRows'] = 0;
		}
		
		return $results;
	}
	
	private function confirmCrossPost($requestData)
	{
		$memberNo = $requestData->memberNo;
		$key = $requestData->key;
		$postType = $requestData->postType;
		
		switch($postType)
		{
			case "FB":
			{
				$query = sprintf("update fileUrls SET fileUrls.facebook = 'Y' where fileUrls.key = '%s'", $key);
				break;
			}
			case "TWT":
			{
				$query = sprintf("update fileUrls SET fileUrls.twitter = 'Y' where fileUrls.key = '%s'", $key);
				break;
			}
			case "FSQ":
			{
				$query = sprintf("update fileUrls SET fileUrls.foursquare = 'Y' where fileUrls.key = '%s'", $key);
				break;
			}
		}

		$this->db->query($query);
		
		$query = sprintf("select fileNo from fileUrls where fileUrls.key = '%s'", $key);
		$fileUrlQuery = $this->db->query($query);
		$fileUrlData = $fileUrlQuery->fetch_assoc();
		$fileNo = $fileUrlData['fileNo'];

		$results = array();
		$results['numRows'] = 0;
		if($fileNo)
		{
			$query = sprintf("insert ignore into crossPostLog (memberNo, fileNo, type, regDatetime) values (%d, %d, '%s', UTC_TIMESTAMP())", $memberNo, $fileNo, $postType);
			$this->db->query($query);
			$results['numRows'] = $this->db->affected_rows;
		}

		return $results;
	}

	private function checkMatchStatus($matchNo)
	{
		$query = sprintf("select ms.open from matchSessions ms where ms.matchNo = %d", $matchNo);
		$queryResult = $this->db->query($query);
		$matchData = $queryResult->fetch_assoc();

		if($matchData["open"] == 'Y')
		{
			$result = true;
		}
		else
		{
			$result = false;
		}

		return $result;
	}
}
?>