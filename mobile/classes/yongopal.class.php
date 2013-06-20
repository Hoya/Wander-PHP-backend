<?

class YongoPal
{
	private static $instance;
	protected $db;
	protected $result = array();
	public $currentVersion = "124";

	public $bitlyKey = "";
	public $bingKey = "";
	public $yahooAppId = "";

	public $development;
	public $recentReqeust;
	public $recentTask;
	public $recentData;
	public $appVersion;

	private function __construct()
	{
		require_once("classes/DbConnect.class.php");

        if(function_exists('newrelic_set_appname'))
        {
            newrelic_set_appname('', '');
        }

		$this->result['error'] = array();
		$this->result['version'] = $this->currentVersion;
	}
	
	public static function getInstance()
	{
		if (!isset(self::$instance))
		{
			$c = __CLASS__;
			self::$instance = new $c();
        }

		return self::$instance;
	}
	
	public function initDatabase($development = null)
	{
		$this->development = $development;
		// open database connection
		$this->db = DbConnect::getInstance($development);
		$this->db->show_errors();
	}
	
	public function getDatabaseInstance()
	{
		return $this->db;
	}
	
	public function setResult($result)
	{
		if($result)
		{
			$this->result['result'] = $result;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function setError($error)
	{
		if($error)
		{
			$this->result['error'] = $error;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function getResults()
	{
		$this->result['appVersion'] = $this->appVersion;
		$this->result['request'] = $this->recentReqeust;
		$this->result['task'] = $this->recentTask;

		return $this->result;
	}
	
	public function getJsonResults()
	{
		$this->result['appVersion'] = $this->appVersion;
		$this->result['request'] = $this->recentReqeust;
		$this->result['task'] = $this->recentTask;

		return json_encode($this->result);
	}
	
	public function printJsonResults()
	{
		$this->result['appVersion'] = $this->appVersion;
		$this->result['request'] = $this->recentReqeust;
		$this->result['task'] = $this->recentTask;
                $json = json_encode($this->result);

		header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header('Content-type: application/json');
                ob_clean();
		echo $json;
		ob_flush();
		flush();
	}
	
	public function getVersion()
	{
		return $this->currentVersion;
	}
	
	public function getServerLocation()
	{
		if(isset($_SERVER['SERVER_ADDR']))
		{
			if($_SERVER['SERVER_ADDR'] == '')
			{
				return "KR";
			}
			else
			{
				return "US";
			}
		}
	}
	
	public function checkMemberNo($memberNo)
	{
		$query = sprintf("SELECT * FROM members WHERE memberNo = %d", $memberNo);
		$memberQuery = $this->db->query($query);
		if($memberQuery->num_rows == 0 && $this->development != "release")
		{
			trigger_error("The beta server has been reset, please logout and reregister", E_USER_WARNING);
		}
	}

	public function checkBlacklist($memberNo)
	{
		$query = sprintf("select ad.deviceUdid from members m join apnDevices ad on m.deviceNo = ad.deviceNo join blacklist bl on ad.deviceUdid = bl.deviceUdid where m.memberNo = %d", $memberNo);
		$blacklistQuery = $this->db->query($query);
		$blacklisted = $blacklistQuery->num_rows;
		if($blacklisted == 1)
		{
			trigger_error("This device has been blocked due to a violation of our terms of use. Please contact support@wanderwith.us for more information.", E_USER_WARNING);
			exit();
		}
	}

	public function clearServerData()
	{
		$this->db->query("DELETE FROM crossPostLog");
		$this->db->query("DELETE FROM cacheRecentMessage");
		$this->db->query("DELETE FROM feedbackOther");
		$this->db->query("DELETE FROM feedback");
		$this->db->query("DELETE FROM matchMissionLog");
		$this->db->query("DELETE FROM fileUrls");
		$this->db->query("DELETE FROM chatData");
		$this->db->query("DELETE FROM fileMetaData");
		$this->db->query("DELETE FROM files");
		$this->db->query("DELETE FROM quickMatchLog");
		$this->db->query("DELETE FROM matchMemberLog");
		$this->db->query("DELETE FROM matchMissions");
		$this->db->query("DELETE FROM matchSessionMembers");
		$this->db->query("DELETE FROM matchSessions");
		$this->db->query("DELETE FROM matchPool");
		$this->db->query("DELETE FROM apnQueue");
		$this->db->query("DELETE FROM apnQueueCache");
		$this->db->query("DELETE FROM sessionLogs");
		$this->db->query("DELETE FROM memberAccessCodes");
		$this->db->query("DELETE FROM memberPrivileges");
		$this->db->query("DELETE FROM members");
		$this->db->query("DELETE FROM instances");
		$this->db->query("DELETE FROM apnBadgeCount");
		$this->db->query("DELETE FROM apnDeviceHistory");
		$this->db->query("DELETE FROM apnDevices");

		$this->db->query("ALTER TABLE crossPostLog AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE feedbackOther AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE feedback AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE matchMissionLog AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE fileUrls AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE chatData AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE fileMetaData AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE files AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE quickMatchLog AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE matchMemberLog AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE matchPool AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE matchSessionMembers AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE matchSessions AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE apnQueue AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE apnQueueCache AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE sessionLogs AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE memberAccessCodes AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE memberPrivileges AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE members AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE instances AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE apnDeviceHistory AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE apnBadgeCount AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE apnDevices AUTO_INCREMENT=1");
		
		$this->db->query("CALL refreshApnQueue()");
		
		exec("rm -f cache/*");

		return true;
	}

	public function clearMatchData()
	{
		$this->db->query("DELETE FROM crossPostLog");
		$this->db->query("DELETE FROM cacheRecentMessage");
		$this->db->query("DELETE FROM apnQueue");
		$this->db->query("DELETE FROM apnQueueCache");
		$this->db->query("DELETE FROM feedbackOther");
		$this->db->query("DELETE FROM feedback");
		$this->db->query("DELETE FROM fileUrls");	
		$this->db->query("DELETE FROM chatData");
		$this->db->query("DELETE FROM fileMetaData");
		$this->db->query("DELETE FROM quickMatchLog");
		$this->db->query("DELETE FROM matchMemberLog");
		$this->db->query("DELETE FROM matchMissionLog");
		$this->db->query("DELETE FROM matchMissions");
		$this->db->query("DELETE FROM matchSessionMembers");
		$this->db->query("DELETE FROM matchSessions");	
		$this->db->query("DELETE FROM matchPool");

		$this->db->query("ALTER TABLE crossPostLog AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE apnQueue AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE apnQueueCache AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE feedbackOther AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE feedback AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE fileUrls AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE chatData AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE fileMetaData AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE quickMatchLog AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE matchMemberLog AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE matchMissionLog AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE matchSessionMembers AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE matchSessions AUTO_INCREMENT=1");
		$this->db->query("ALTER TABLE matchPool AUTO_INCREMENT=1");

		$this->db->query("UPDATE members SET lastMatchDatetime = NULL");
		$this->db->query("INSERT INTO matchPool select memberNo, NULL, 'P', 0, 0, 0, 0, UTC_TIMESTAMP(), NULL from members");
		
		exec("rm -f cache/*");
		
		return true;
	}
	
	static function flipImage($src, $type)
	{
		$imgsrc = imagecreatefromjpeg($src);
		$width = imagesx($imgsrc);
		$height = imagesy($imgsrc);
		$imgdest = imagecreatetruecolor($width, $height);

		for ($x=0 ; $x<$width ; $x++)
		{
			for ($y=0 ; $y<$height ; $y++)
			{
				if ($type == 1) imagecopy($imgdest, $imgsrc, $width-$x-1, $y, $x, $y, 1, 1);
				if ($type == 2) imagecopy($imgdest, $imgsrc, $x, $height-$y-1, $x, $y, 1, 1);
				if ($type == 3) imagecopy($imgdest, $imgsrc, $width-$x-1, $height-$y-1, $x, $y, 1, 1);
			}
		}

		imagedestroy($imgsrc);
		return $imgdest;
	}

	// error handlers
	public static function handleError($errno, $errstr, $errfile, $errline, $errcontext)
	{
		switch($errno)
		{
			case 1: $error['type'] = "ERROR"; break;
			case 2: $error['type'] = "WARNING"; break;
			case 4: $error['type'] = "PARSE"; break;
			case 8: $error['type'] = "NOTICE"; break;
			case 16: $error['type'] = "ERROR"; break;
			case 32: $error['type'] = "WARNING"; break;
            case 64: $error['type'] = "ERROR"; break;
            case 128: $error['type'] = "WARNING"; break;
            case 256: $error['type'] = "ERROR"; break;
            case 512: $error['type'] = "WARNING"; break;
            case 1024: $error['type'] = "NOTICE"; break;
            case 4096: $error['type'] = "ERROR"; break;
            case 8192: $error['type'] = "DEPRECATED"; break;
            case 16384: $error['type'] = "DEPRECATED"; break;
			default: $error['type'] = "ERROR";
		}
		if (!error_reporting()) return;

		$error['errorNo'] = $errno;
		if(self::$instance->development != "release") $error['description'] = $errstr;
		if(self::$instance->development != "release") $error['file'] = $errfile;
		if(self::$instance->development != "release") $error['line'] = $errline;
		$error['task'] = self::$instance->recentTask;
		$error['data'] = self::$instance->recentData;

		// log error to file
		error_log(sprintf("%s: %s in %s on line %d", $error['type'], $errstr, $errfile, $errline));
		
		// new relic log
		if(function_exists('newrelic_notice_error'))
		{
		    if($error['type'] == 'ERROR' || $error['type'] == 'WARNING')
            {
                newrelic_notice_error($errno, $errstr, $errfile, $errline, $errcontext);
            }
		}

		self::$instance->result['error'] = $error;
		header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header('Content-type: application/json');
		echo json_encode(self::$instance->result);
		die();
    }
    
    public static function handleException($exception)
	{
		$error['trace'] = $exception->getTrace();
		$error['description'] = $exception->getMessage();
		if(self::$instance->development != "release") $error['file'] = $exception->getFile();
        if(self::$instance->development != "release") $error['line'] = $exception->getLine();
        $error['task'] = self::$instance->recentTask;
		$error['data'] = self::$instance->recentData;

		// log exception to file
		error_log(sprintf("Exception: %s, Task: %s, Request: %s, Backtrace: %s", $error['description'], $error['task'], $error['data'], $error['trace']));

		// new relic log
		if(function_exists('newrelic_notice_error'))
		{
			newrelic_notice_error($exception->getMessage(), $exception);
		}

		self::$instance->result['error'] = $error;
		header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header('Content-type: application/json');
		echo json_encode(self::$instance->result);
		die();
	}
	
	public static function setErrorHanders()
    {
    	error_reporting(E_ALL);
        set_error_handler(array(__CLASS__, 'handleError'));
        set_exception_handler(array(__CLASS__, 'handleException'));
    }
}

?>
