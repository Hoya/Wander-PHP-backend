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
if(!empty($_GET))
{
	$yongopal->initDatabase($_GET['development']);

	$args = array();
	if(isset($_GET['type'])) $requestType = $_GET['type'];
	else $requestType = null;
	if(isset($_GET['task'])) $args['task'] = $_GET['task'];
	else $args['task'] = null;
	if(isset($_GET['data'])) $args['data'] = json_decode($_GET['data']);
	else $args['data'] = null;

	$yongopal->recentReqeust = $requestType;
	$yongopal->recentTask = $args['task'];
	$yongopal->recentData = $args['data'];
}
else
{
	$requestType = null;
}

// load classes
if(!empty($requestType))
{
	require_once('classes/'.$requestType.'.class.php');
}

switch($requestType)
{
	case "member":
	{
		$member = new Member();
		$member->request($args);
		break;
	}
	case "chat":
	{
		$chat = new Chat();
		$chat->request($args);
		break;	
	}
	default:
	{	
		if(isset($_GET['messageNo']))
		{
			require_once('classes/chat.class.php');

			$args['task'] = "downloadPhoto";
			$args['data'] = new stdClass;
			$args['data']->messageNo = $_GET['messageNo'];
			$args['data']->width = 0;
			$args['data']->height = 0;

			$chat = new Chat();
			$chat->request($args);
			break;
		}
	}
}
?>