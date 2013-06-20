<?
require_once("classes/yongopal.class.php");

// initiate YongoPal API class
$yongopal = YongoPal::getInstance();

// set error handler
$yongopal->setErrorHanders();

if (!isset($_SERVER['PHP_AUTH_USER']))
{
	header('WWW-Authenticate: Basic realm="YongoPal Mobile API"');
	header('HTTP/1.0 401 Unauthorized');
	exit;
}
elseif($_SERVER['PHP_AUTH_USER'] != "" || $_SERVER['PHP_AUTH_PW'] != "")
{
	trigger_error("Access denied", E_USER_ERROR);
	exit();
}

// get request data
if(!empty($_POST))
{
	$yongopal->initDatabase($_POST['development']);

	$args = array();
	if(isset($_POST['type']))
	{
		$requestType = $_POST['type'];
		$yongopal->recentReqeust = $requestType;
	}
	else
	{
		$requestType = null;
	}

	if(isset($_POST['task']))
	{
		$args['task'] = $_POST['task'];
		$yongopal->recentTask = $_POST['task'];
	}

	if(isset($_POST['data']))
	{
		$args['data'] = json_decode($_POST['data']);
		$yongopal->recentData = $_POST['data'];
	}
	
	if(isset($_POST['appVersion']))
	{
		$yongopal->appVersion = intval($_POST['appVersion']);
	}
	else
	{
		$yongopal->appVersion = 1;
	}
}

if(!empty($_FILES))
{
	$args['data']->fileData = $_FILES;
}

// load classes
if(!empty($requestType))
{
	require_once('classes/'.$requestType.'.class.php');
}

// check member number for test versions
if(isset($args['data']->memberNo)) $memberNo = intval($args['data']->memberNo);
else $memberNo = 0;
if($memberNo != 0 && $yongopal->development != "release")
{
	$yongopal->checkMemberNo($args['data']->memberNo);
}

// check for blacklisted devices
if($memberNo != 0)
{
	$yongopal->checkBlacklist($memberNo);
}

// process request
switch($requestType)
{
	case "urbanairship":
	{
		$airship = new Urbanairship();
		$airship->request($args);

		break;
	}
	case "member":
	{
		$member = new Member();
		$member->request($args);

		break;
	}
	case "match":
	{
		$match = new Match();
		$match->request($args);
		
		break;
	}
	case "chat":
	{
		$chat = new Chat();
		$chat->request($args);
		
		break;
	}
	case "feedback":
	{
		$chat = new Feedback();
		$chat->request($args);
		
		break;
	}
	case "mission":
	{
		$chat = new Mission();
		$chat->request($args);
		
		break;
	}
	default:
	{
		sleep(2);
	}
}

$yongopal->printJsonResults();

?>