<?

class Feedback extends YongoPal
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
				case "getFeedbackList":
				{
					$result = $this->getFeedbackList();
					break;
				}
				case "sendFeedback":
				{
					$result = $this->sendFeedback($args['data']);
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

	private function getFeedbackList()
	{
		$query = sprintf("select * from feedbackAnswers where active = 'Y' order by listOrder");
		$answerQuery = $this->db->query($query);

		$results = array();
		while($row = $answerQuery->fetch_assoc())
		{
			$results[] = $row;
		}

		return $results;
	}

	public function sendFeedback($requestData)
	{
		$matchNo = intval($requestData->matchNo);
		$memberNo = intval($requestData->memberNo);
		$answerNo = intval($requestData->answerNo);
		if(isset($requestData->otherAnswer)) $otherAnswer = addslashes($requestData->otherAnswer);
		else $otherAnswer = null;
		
		$query = sprintf("select matchedMemberNo from matchMemberLog where matchNo = %d and memberNo = %d", $matchNo, $memberNo);
		$matchQuery = $this->db->query($query);
		$matchData = $matchQuery->fetch_assoc();
		$partnerNo = $matchData['matchedMemberNo'];

		$query = sprintf("select * from feedbackAnswers where answerNo = %d", $answerNo);
		$answerQuery = $this->db->query($query);
		$answerData = $answerQuery->fetch_assoc();
		
		if($answerData['pointsForUser'] != 0)
		{
			$query = sprintf("update members set matchPriority = (matchPriority + %d) where memberNo = %d", $answerData['pointsForUser'], $memberNo);
			$this->db->query($query);
		}

		if($answerData['pointsForPartner'] != 0)
		{
			$query = sprintf("update members set matchPriority = (matchPriority + %d) where memberNo = %d", $answerData['pointsForPartner'], $partnerNo);
			$this->db->query($query);
		}

		$query = sprintf("insert into feedback (matchNo, memberNo, answerNo, regDatetime) values (%d, %d, %d, UTC_TIMESTAMP()) on duplicate key update answerNo = %d, regDatetime = UTC_TIMESTAMP()", $matchNo, $memberNo, $answerNo, $answerNo);
		$this->db->query($query);
		$feedbackNo = $this->db->insert_id;

		if($otherAnswer)
		{
			$query = sprintf("insert into feedbackOther (feedbackNo, otherAnswer) values (%d, '%s')", $feedbackNo, $otherAnswer);
			$this->db->query($query);
		}
		
		// count number of times user has been flagged
		$query = sprintf("SELECT msm.memberNo, m.email, COUNT(f.feedbackNo) AS flagCount
					FROM matchSessionMembers msm
					JOIN matchSessionMembers msm2
					ON msm.memberNo = msm2.memberNo
					AND msm2.matchNo = %d
					AND msm2.memberNo != %d
					JOIN feedback f
					ON msm.matchNo = f.matchNo
					AND f.answerNo = 4
					JOIN members m
					ON msm.memberNo = m.memberNo
					GROUP BY msm.memberNo", $matchNo, $memberNo);
		$flagCountQuery = $this->db->query($query);
		$flagCountData = $flagCountQuery->fetch_assoc();

		// send alert email if user has been flagged 3 times or more
		if($flagCountData['flagCount'] >= 1)
		{
			$this->sendAlertEmail($flagCountData['email'], $flagCountData['memberNo']);
		}

		$results = array();
		$results['feedbackNo'] = $feedbackNo;
		return $results;
	}

	private function sendAlertEmail($email, $memberNo)
	{
		require("phpmailer/class.phpmailer.php");
		require("crypto.class.php");

		$query = sprintf("SELECT *
					FROM chatData cd
					LEFT JOIN files f
					ON cd.imageFileNo = f.fileNo
					WHERE cd.sender = %d
					ORDER BY cd.messageNo DESC
					LIMIT 100", $memberNo);
		$chatDataQuery = $this->db->query($query);
		
		$htmlReport = "<h1>Offender Alert</h1>\n";
		$htmlReport .= "<h3>Offender Info</h3>\n";
		$htmlReport .= "<ul>\n";
		$htmlReport .= "<li>MemberNo: ".$memberNo."</li>\n";
		$htmlReport .= "<li>Email: ".$email."</li>\n";
		$htmlReport .= "</ul>\n";

		$htmlReport .= "<h3>Offender Chat Log</h3>\n";
		$htmlReport .= "<ul>\n";
		while($row = $chatDataQuery->fetch_assoc())
		{
			if($row['imageFileNo'] != 0)
			{
				$htmlReport .= "<li><img src='http://wanderwith.us/viewPhoto/downloadImage/".$row['imageFileNo']."' width='200'></li>\n";
			}
			else
			{
				$htmlReport .= "<li>".$row['message']."</li>\n";
			}
		}
		$htmlReport .= "</ul>";
		
		$crypto = new Crypto();
		$key = $crypto->encrypt256($memberNo, '');

		$htmlReport .= "<a href='http://wanderwith.us/admin/banUser/".$key."'>click to ban user</a>";
		
		$mail = new PHPMailer(false);
		$mail->IsSMTP();
		$mail->SMTPDebug = false;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "ssl";
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465;
		$mail->Username = "";
		$mail->Password = "";

		$mail->SetFrom('support@wanderwith.us', 'Wander Support');
		$mail->AddAddress('hello@wanderwith.us', 'Wander Team');

		$mail->Subject = "*** Wander Offender Alert ***";			
		$mail->MsgHTML($htmlReport);
		$mail->AltBody = strip_tags($htmlReport);

		if(!$mail->Send())
		{
			trigger_error($mail->ErrorInfo, E_USER_ERROR);
		}
	}
}
?>