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
elseif($_POST['development'] != '' || $_SERVER['PHP_AUTH_USER'] != "" || $_SERVER['PHP_AUTH_PW'] != "")
{
	trigger_error("Access denied", E_USER_ERROR);
	exit();
}

$admins = array();
// Darien's iPhone
$admins[] = "";

// Jiho's iPhone and iPod
$admins[] = "";
$admins[] = "";
$admins[] = "";
$admins[] = "";

// Daron's iPhone
$admins[] = "";

// simulator
$admins[] = "simulator1230";

// get request data
if(!empty($_POST))
{
	$yongopal->initDatabase($_POST['development']);

	if(isset($_POST['udid']))
	{
		$udid = $_POST['udid'];
	}

	if(isset($_POST['task']))
	{
		$task = $_POST['task'];
		$yongopal->recentTask = $_POST['task'];
	}

	if(isset($_POST['data']))
	{
		$data = json_decode($_POST['data']);
		$yongopal->recentData = $_POST['data'];
	}
}

if(empty($udid) || !in_array($udid, $admins))
{
	trigger_error("Permission Denied", E_USER_ERROR);
	exit();
}

// process request
switch($task)
{
	case "clearMatchData":
	{
		$yongopal->clearMatchData();
		break;
	}
	case "clearServerData":
	{
		$yongopal->clearServerData();
		break;
	}
}

$yongopal->printJsonResults();

?>