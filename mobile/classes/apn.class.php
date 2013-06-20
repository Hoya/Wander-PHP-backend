<?PHP
#################################################################################
## Developed by Manifest Interactive, LLC                                      ##
## http://www.manifestinteractive.com                                          ##
## ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ ##
##                                                                             ##
## THIS SOFTWARE IS PROVIDED BY MANIFEST INTERACTIVE 'AS IS' AND ANY           ##
## EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE         ##
## IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR          ##
## PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL MANIFEST INTERACTIVE BE          ##
## LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR         ##
## CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF        ##
## SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR             ##
## BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,       ##
## WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE        ##
## OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,           ##
## EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                          ##
## ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ ##
## Author of file: Peter Schmalfeldt                                           ##
#################################################################################

/**
 * @category Apple Push Notification Service using PHP & MySQL
 * @package EasyAPNs
 * @author Peter Schmalfeldt <manifestinteractive@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link http://code.google.com/p/easyapns/
 */

/**
 * Begin Document
 */

class APN extends YongoPal
{
	
	/**
	* Connection to MySQL
	*
	* @var string
	* @access private
	*/
	protected $db;
	
	/**
	* Array of APN Connection Settings
	*
	* @var array
	* @access private
	*/
	private $apnData;
	
	/**
	* Whether to trigger errors
	*
	* @var bool
	* @access private
	*/
	private $showErrors = false;
	
	/**
	* Whether APNS should log errors
	*
	* @var bool
	* @access private
	*/
	private $logErrors = true;
	
	/**
	* Log path for APNS errors
	*
	* @var string
	* @access private
	*/
	private $logPath = '/var/log/apn/apn.log';
	
	/**
	* Max files size of log before it is truncated. 1048576 = 1MB.  Added incase you do not add to a log
	* rotator so this script will not accidently make gigs of error logs if there are issues with install
	*
	* @var int
	* @access private
	*/
	private $logMaxSize = 1048576; // max log size before it is truncated
	
	/**
	* Absolute path to your Production Certificate
	*
	* @var string
	* @access private
	*/
	private $devCertificate = '/etc/ssl/certs/apn-dev.pem';
	private $betaDevCertificate = '/etc/ssl/certs/apn-dev.pem';
	private $betaCertificate = '/etc/ssl/certs/apn-production.pem';
	private $productionCertificate = '/etc/ssl/certs/apn-production.pem';
	
	/**
	* Apples Production APNS Gateway
	*
	* @var string
	* @access private
	*/
	private $ssl = 'ssl://gateway.push.apple.com:2195';
	
	/**
	* Apples Production APNS Feedback Service
	*
	* @var string
	* @access private
	*/
	private $feedback = 'ssl://feedback.push.apple.com:2196';

	/**
	* Apples Sandbox APNS Gateway
	*
	* @var string
	* @access private
	*/
	private $sandboxSsl = 'ssl://gateway.sandbox.push.apple.com:2195';
	
	/**
	* Apples Sandbox APNS Feedback Service
	*
	* @var string
	* @access private
	*/
	private $sandboxFeedback = 'ssl://feedback.sandbox.push.apple.com:2196';
	
	/**
	* Message to push to user
	*
	* @var string
	* @access private
	*/
	private $queueData;
	private $sound;
	private $badge;
	private $message;
	private $extraParams;
	
	private $retryCount = 0;
	private $retryLimit = 2;

	function __construct($args=NULL)
	{		
		$instance = parent::getInstance();

		$this->db = $instance->db;
		$this->checkSetup();
		$this->apnData = array
		(
			'release'=>array
			(
				'certificate'=>$this->productionCertificate, 
				'ssl'=>$this->ssl, 
				'feedback'=>$this->feedback
			),
			'releaseDebug'=>array
			(
				'certificate'=>$this->devCertificate, 
				'ssl'=>$this->sandboxSsl, 
				'feedback'=>$this->sandboxFeedback
			),
			'beta'=>array
			(
				'certificate'=>$this->betaCertificate, 
				'ssl'=>$this->ssl, 
				'feedback'=>$this->feedback
			),
			'betaDebug'=>array
			(
				'certificate'=>$this->betaDevCertificate, 
				'ssl'=>$this->sandboxSsl, 
				'feedback'=>$this->sandboxFeedback
			),
			'debug'=>array
			(
				'certificate'=>$this->devCertificate, 
				'ssl'=>$this->sandboxSsl, 
				'feedback'=>$this->sandboxFeedback
			)
		);
	}
	
	/**
	 * Check Setup
	 *
	 * Check to make sure that the certificates are available and also provide a notice if they are not as secure as they could be.
	 *
     * @access private
     */	
	private function checkSetup()
	{
		if(!file_exists($this->productionCertificate)) $this->_triggerError('Missing Production Certificate.', E_USER_ERROR);
		if(!file_exists($this->betaCertificate)) $this->_triggerError('Missing Beta Certificate.', E_USER_ERROR);
		if(!file_exists($this->betaDevCertificate)) $this->_triggerError('Missing Beta Dev Certificate.', E_USER_ERROR);
		if(!file_exists($this->devCertificate)) $this->_triggerError('Missing Sandbox Certificate.', E_USER_ERROR);

		clearstatcache();
		$productionCertificateMod = substr(sprintf('%o', fileperms($this->productionCertificate)), -3);
		$betaCertificateMod = substr(sprintf('%o', fileperms($this->betaCertificate)), -3);
		$betaDevCertificateMod = substr(sprintf('%o', fileperms($this->betaCertificate)), -3);
		$devCertificateMod = substr(sprintf('%o', fileperms($this->devCertificate)), -3); 
		
		if($productionCertificateMod>644)  $this->_triggerError('Production Certificate is insecure! Suggest chmod 644.');
		if($betaCertificateMod>644)  $this->_triggerError('Beta Certificate is insecure! Suggest chmod 644.');
		if($betaDevCertificateMod>644)  $this->_triggerError('Beta Dev Certificate is insecure! Suggest chmod 644.');
		if($devCertificateMod>644)  $this->_triggerError('Debug Certificate is insecure! Suggest chmod 644.');
	}

	public function _registerDevice($deviceData)
	{
		if(isset($deviceData->appName)) $appName = $deviceData->appName;
		else $appName = null;
		if(isset($deviceData->appVersion)) $appVersion = $deviceData->appVersion;
		else $appVersion = null;
		if(isset($deviceData->deviceUdid)) $deviceUdid = $deviceData->deviceUdid;
		else $deviceUdid = null;
		if(isset($deviceData->deviceToken)) $deviceToken = $deviceData->deviceToken;
		else $deviceToken = null;
		if(isset($deviceData->deviceName)) $deviceName = $deviceData->deviceName;
		else $deviceName = null;
		if(isset($deviceData->deviceModel)) $deviceModel = $deviceData->deviceModel;
		else $deviceModel = null;
		if(isset($deviceData->deviceVersion)) $deviceVersion = $deviceData->deviceVersion;	
		else $deviceVersion = null;
		if(isset($deviceData->pushBadge)) $pushBadge = $deviceData->pushBadge;
		else $pushBadge = null;
		if(isset($deviceData->pushAlert)) $pushAlert = $deviceData->pushAlert;
		else $pushAlert = null;
		if(isset($deviceData->pushSound)) $pushSound = $deviceData->pushSound;
		else $pushSound = null;

		if(strlen($appName)==0) $this->_triggerError('Application Name must not be blank.', E_USER_ERROR);
		else if(strlen($appVersion)==0) $this->_triggerError('Application Version must not be blank.', E_USER_ERROR);
		else if(strlen($deviceUdid)!=40) $this->_triggerError('Device ID must be 40 characters in length.', E_USER_ERROR);
		else if(strlen($deviceToken)!=64) $this->_triggerError('Device Token must be 64 characters in length.', E_USER_ERROR);
		else if(strlen($deviceName)==0) $this->_triggerError('Device Name must not be blank.', E_USER_ERROR);
		else if(strlen($deviceModel)==0) $this->_triggerError('Device Model must not be blank.', E_USER_ERROR);
		else if(strlen($deviceVersion)==0) $this->_triggerError('Device Version must not be blank.', E_USER_ERROR);
		else if($pushBadge!='disabled' && $pushBadge!='enabled') $this->_triggerError('Push Badge must be either Enabled or Disabled.', E_USER_ERROR);
		else if($pushAlert!='disabled' && $pushAlert!='enabled') $this->_triggerError('Push Alert must be either Enabled or Disabled.', E_USER_ERROR);
		else if($pushSound!='disabled' && $pushSound!='enabled') $this->_triggerError('Push Sount must be either Enabled or Disabled.', E_USER_ERROR);

		$appName = $this->db->prepare($appName);
		$appVersion = $this->db->prepare($appVersion);
		$deviceUdid = $this->db->prepare($deviceUdid);
		$deviceToken = $this->db->prepare($deviceToken);
		$deviceName = rawurldecode($this->db->prepare($deviceName));
		$deviceModel = rawurldecode($this->db->prepare($deviceModel));
		$deviceVersion = $this->db->prepare($deviceVersion);
		$pushBadge = $this->db->prepare($pushBadge);
		$pushAlert = $this->db->prepare($pushAlert);
		$pushSound = $this->db->prepare($pushSound);
		$instance = parent::getInstance();
		$development = $this->db->prepare($instance->development);

		// store device for push notifications
		$this->db->query("SET NAMES 'utf8';"); // force utf8 encoding if not your default
		$sql = "INSERT INTO `apnDevices` 
				VALUES (
					NULL, 
					'{$appName}', 
					{$appVersion}, 
					'{$deviceUdid}', 
					'{$deviceToken}', 
					'{$deviceName}',
					'{$deviceModel}',
					'{$deviceVersion}',
					'{$pushBadge}',
					'{$pushAlert}',
					'{$pushSound}',
					'{$development}',
					'active',
					'N', 
					NOW(), 
					NOW()
				) 
				ON DUPLICATE KEY UPDATE 
				`deviceNo`= LAST_INSERT_ID(deviceNo),
				`deviceToken`='{$deviceToken}', 
				`deviceName`='{$deviceName}', 
				`deviceModel`='{$deviceModel}', 
				`deviceVersion`='{$deviceVersion}', 
				`pushBadge`='{$pushBadge}', 
				`pushAlert`='{$pushAlert}', 
				`pushSound`='{$pushSound}', 
				`development`='{$development}', 
				`status`='active', 
				`modified`=NOW();";	
		$this->db->query($sql);
		
		$deviceNo = $this->db->insert_id;
		
		return $deviceNo;
	}
	
	/**
	 * Unregister Apple device
	 *
	 * This gets called automatically when Apple's Feedback Service responds with an invalid token.
	 *
	 * @param sting $token 64 character unique device token tied to device id
     * @access private
     */	
	private function _unregisterDevice($token){
		$sql = "UPDATE `apnDevices` 
				SET `status`='uninstalled' 
				WHERE `devicetoken`='{$token}' 
				LIMIT 1;";
		$this->db->query($sql);
	}

	public function _fetchMessages($memberNo)
	{
		$queueQuery = $this->db->query("CALL getQueuedNotifications(".$memberNo.")");
		$queueData = array();
		while($row = $queueQuery->fetch_assoc())
		{
			$queueData[] = $row;
		}
		$queueQuery->close();
		$this->db->next_result();

		if(count($queueData > 0))
		{
			foreach($queueData as $push)
			{
				$queueNo = $push['queueNo'];
				$deviceNo = $push['deviceNo'];
				$deviceToken = $push['deviceToken'];
				$type = $push['pushType'];
				$badge = $push['badge']; 
				$extraParams = json_decode($push['extraParams']);
				$development = $push['development'];
				$newMatchAlert = $push['newMatchAlert'];
				$newMissionAlert = $push['newMissionAlert'];
				$newMessageAlert = $push['newMessageAlert'];
				
				switch($type)
				{
					// new match alert
					case 1:
					{
						if($newMatchAlert == 'N')
						{
							$this->_pushFailed($queueNo);
							continue;
						}
						break;
					}
					// new message alert
					case 3:
					{
						if($newMessageAlert == 'N')
						{
							$this->_pushFailed($queueNo);
							continue;
						}
						break;
					}
					// missions alert
					case 5:
					{
						if($newMissionAlert == 'N')
						{
							$this->_pushFailed($queueNo);
							continue;
						}
						break;
					}

				}

				$payload = array();
				$payload['aps'] = array();
				
				// don't send if all notifications are disabled
				if($push['pushBadge'] == 'disabled' && $push['pushAlert'] == 'disabled' && $push['pushSound'] == 'disabled')
				{
					$this->_pushFailed($queueNo);
					continue;
				}
				else
				{
					if($push['pushBadge'] == 'enabled') $payload['aps']['badge'] = intval($badge);
					if($push['pushSound'] == 'enabled' && isset($push['sound']) && $push['sound'] != '') $payload['aps']['sound'] = $push['sound'];
					if($type == 0)
					{
						$payload['aps']['alert']["action-loc-key"] = null;
						if($push['pushAlert'] == 'enabled' && isset($push['message']))
						{
							if($push['message'] != '') $payload['aps']['alert']['body'] = $push['message'];
						}
					}
					else if($push['pushAlert'] == 'enabled' && isset($push['message']))
					{
						if($push['message'] != '') $payload['aps']['alert'] = $push['message'];
					}

					if(count($extraParams) > 0)
					{
						foreach($extraParams as $key => $value)
						{
							$payload[$key] = $value;
						}
					}
					$jsonPayload = json_encode($payload);
	
					$deviceNo = $this->db->prepare($deviceNo);
					$message = stripslashes($this->db->prepare($jsonPayload));
					$deviceToken = $this->db->prepare($deviceToken);
					exec("nohup /usr/bin/php ".__DIR__."/../jobs/pushMessage.php ".$queueNo." ".$deviceNo." ".base64_encode($message)." ".$deviceToken." ".$development." &");
				}
			}
		}
	}

	public function _pushMessage($queueNo, $deviceNo, $message, $token, $development)
	{
		if(strlen($deviceNo)==0) $this->_triggerError('Missing message deviceNo.', E_USER_ERROR);
		if(strlen($message)==0) $this->_triggerError('Missing message.', E_USER_ERROR);
		else $message = base64_decode($message);
		if(strlen($token)==0) $this->_triggerError('Missing message token.', E_USER_ERROR);

		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->apnData[$development]['certificate']);
		$fp = stream_socket_client($this->apnData[$development]['ssl'], $error, $errorString, 60, STREAM_CLIENT_CONNECT, $ctx);

		if(!$fp)
		{
			if($this->retryLimit > $this->retryCount)
			{
				// sleep for 2 seconds then retry to connect
				$this->writeLog("Failed to connect to APN, retrying #{$this->retryCount}");
				sleep(2);
				$this->_pushMessage($queueNo, $deviceNo, $message, $token);
				$this->retryCount++;
			}
			else
			{
				$this->_pushFailed($queueNo);
				$this->_triggerError("Failed to connect to APN: {$error} {$errorString}.");
				$this->retryCount = 0;
			}
		}
		else
		{
			$msg = chr(0).pack("n",32).pack('H*',$token).pack("n",strlen($message)).$message;
			$fwrite = fwrite($fp, $msg);
			if(!$fwrite)
			{
				$this->_pushFailed($queueNo);
				$this->_triggerError("Failed writing to stream.", E_USER_ERROR);
			}
			else
			{
				$this->_pushSuccess($queueNo);	
			}
		}
		fclose($fp);

		$this->_checkFeedback($development);
	}

	private function _checkFeedback($development)
	{
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->apnData[$development]['certificate']);
		stream_context_set_option($ctx, 'ssl', 'verify_peer', false);
		$fp = stream_socket_client($this->apnData[$development]['feedback'], $error, $errorString, 60, STREAM_CLIENT_CONNECT, $ctx);

		if(!$fp) $this->_triggerError("Failed to connect to device: {$error} {$errorString}.");
		while ($devcon = fread($fp, 38))
		{
			$arr = unpack("H*", $devcon);
			$rawhex = trim(implode("", $arr));
			$token = substr($rawhex, 12, 64);
			if(!empty($token))
			{
				$this->_unregisterDevice($token);
				$this->_triggerError("Unregistering Device Token: {$token}.");
			}
		}
		fclose($fp);
	}

	private function _pushSuccess($queueNo)
	{
		$this->db->query("CALL setPushSuccess(".$queueNo.")");
	}

	private function _pushFailed($queueNo)
	{
		$this->db->query("UPDATE apnQueue SET pushDatetime = UTC_TIMESTAMP(), status = 'F' WHERE queueNo = ".$queueNo);
	}

	private function writeLog($log)
	{
		$backtrace = debug_backtrace();
		$backtrace = array_reverse($backtrace);
		$log .= " ".gmdate("Y-m-d H:i:s", time())."\n";
		$i=1;
		foreach($backtrace as $debugcode)
		{
			$file = ($debugcode['file']!='') ? "-> File: ".basename($debugcode['file'])." (line ".$debugcode['line'].")":"";
			$log .= "\n\t".$i.") ".$debugcode['class']."::".$debugcode['function']." {$file}";
			$i++;
		}
		$log .= "\n\n";

		if($this->logErrors && file_exists($this->logPath))
		{
			if(filesize($this->logPath) > $this->logMaxSize) $fh = fopen($this->logPath, 'w');
			else $fh = fopen($this->logPath, 'a');
			fwrite($fh, $log);
			fclose($fh);
		}

		return $log;
	}

	private function _triggerError($error, $type=E_USER_NOTICE)
	{
		$error = $this->writeLog($error);
		if($this->showErrors) trigger_error($error, $type);
        else exit(1);
	}

	public function newMessage($sender, $receiver, $type, $delivery=NULL)
	{
		if(strlen($sender)==0) $this->_triggerError('ERROR: Missing message sender.', E_USER_ERROR);
		if(strlen($receiver)==0) $this->_triggerError('ERROR: Missing message receiver.', E_USER_ERROR);

        if(isset($this->queueData))
        {
			unset($this->queueData);
			$this->_triggerError('NOTICE: An existing message already created but not delivered. The previous message has been removed. Use queueMessage() to complete a message.');
        }

		$this->queueData = array();
		$this->queueData['send']['to'] = $receiver;
		$this->queueData['send']['from'] = $sender;
		$this->queueData['send']['type'] = $type;
		$this->queueData['send']['when'] = $delivery;

		$this->extraParams = array();
	}

	public function queueMessage()
	{
		// check to make sure a message was created
		if(!isset($this->queueData)) $this->_triggerError('You cannot Queue a message that has not been created. Use newMessage() to create a new message.');

		// loop through possible users
		$to = $this->queueData['send']['to'];
		$from = $this->queueData['send']['from'];
		$type = intval($this->queueData['send']['type']);
		$when = $this->queueData['send']['when'];
		$receiverList = (is_array($to)) ? $to:array($to);
		$senderList = (is_array($from)) ? $to:array($from);
		unset($this->queueData['send']);

		for($i=0; $i<count($receiverList); $i++)
		{
			$sender = intval($senderList[$i]);
			$receiver = intval($receiverList[$i]);

			$sender = $this->db->prepare($sender);
			$receiver = $this->db->prepare($receiver);
			if(isset($this->message)) $pushMessage = $this->db->prepare($this->message);
			else $pushMessage = '';
			if(isset($this->sound)) $pushSound = $this->db->prepare($this->sound);
			else $pushSound = '';
			if(isset($this->badge)) $pushBadge = $this->db->prepare($this->badge);
			else $pushBadge = 0;
			if(isset($this->extraParams))
			{
				$extraParams = json_encode($this->extraParams);
				$extraParams = $this->db->prepare($extraParams);
			}
			else
			{
				$extraParams = '';
			}

			$this->db->query("CALL addApnQueue({$sender}, {$receiver}, {$type}, {$pushBadge}, '{$pushSound}', '{$pushMessage}', '{$extraParams}')");
		}
		unset($this->queueData);
	}

	public function addMessageAlert($alert=NULL)
	{
		if(!$this->queueData) $this->_triggerError('Must use newMessage() before calling this method.', E_USER_ERROR);
		if(isset($this->message))
		{
			unset($this->message);
			return false;
		}

		if(!empty($alert))
		{
			if(!is_string($alert)) $this->_triggerError('Invalid Alert Format. See documentation for correct procedure.', E_USER_ERROR);
			$this->message = (string)$alert;
		}
	}

	public function addMessageBadge($number=NULL)
	{
		if(!$this->queueData) $this->_triggerError('Must use newMessage() before calling this method.', E_USER_ERROR);
		if($number)
		{
			$this->badge = (int)$number;
		}
	}

	public function addMessageCustom($key=NULL, $value=NULL)
	{
		if(!$this->queueData) $this->_triggerError('Must use newMessage() before calling this method.', E_USER_ERROR);
		if(!empty($key) && !empty($value))
		{
			if(isset($this->extraParams[$key]))
			{
				unset($this->extraParams[$key]);
				return false;
			}
			if(!is_string($key)) $this->_triggerError('Invalid Key Format. Key must be a string. See documentation for correct procedure.', E_USER_ERROR);
			$this->extraParams[$key] = $value;
		}
	}

	public function addMessageSound($sound=NULL)
	{
		if(!$this->queueData) $this->_triggerError('Must use newMessage() before calling this method.', E_USER_ERROR);
		if($sound)
		{
			$this->sound = (string)$sound;
		}
	}

	public function processQueue($sender)
	{
		if(!$sender) $this->_triggerError('ERROR: Missing message sender.', E_USER_ERROR);
		$this->_fetchMessages($sender);
	}
}
?>