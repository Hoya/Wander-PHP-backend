<?
        set_time_limit(0);
        set_include_path(dirname($_SERVER['SCRIPT_NAME']).'/../');
        require_once("classes/DbConnect.class.php");
        require_once("classes/yongopal.class.php");
        require_once("classes/apn.class.php");

        $queueNo = $argv[1];
        $deviceNo = $argv[2];
        $message = $argv[3];
        $token = $argv[4];
        $development = $argv[5];

        // initiate YongoPal API class
        $yongopal = YongoPal::getInstance();
        $yongopal->initDatabase($development);
        $db = $yongopal->getDatabaseInstance();

        $apn = NEW APN();
        $apn->_pushMessage($queueNo, $deviceNo, $message, $token, $development);
?>