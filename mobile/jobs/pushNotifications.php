<?
	set_time_limit(0);
	set_include_path(dirname($_SERVER['SCRIPT_NAME']).'/../');
	require_once("classes/DbConnect.class.php");
	require_once("classes/yongopal.class.php");
	require_once("classes/apn.class.php");

	$development = $argv[1];
	if(isset($argv[2])) $memberNo = $argv[2];
	else $memberNo = null;

	// initiate YongoPal API class
	$yongopal = YongoPal::getInstance();
	$yongopal->initDatabase($development);
	$db = $yongopal->getDatabaseInstance();

	if($memberNo == 0)
	{
		$db->query("CALL refreshMatchPool()");
		$db->query("CALL refreshApnQueue()");
	}

	$apn = NEW APN();
	$apn->_fetchMessages($memberNo);
?>
