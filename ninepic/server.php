<?php 
/**
* 处理拼图游戏类
*/
class ninepic
{
	private $_db = null;

	public function __construct()
	{
		$this->_db = new PDO("mysql:host=;dbname=lalala;","root","");
	}

	public function index()
	{
		
		if (empty($_GET['inAjax']) || empty($_GET['do']) || empty($_POST['token'])) {
			return FALSE;
		} 

		$token = $this->getToken();
		if ($_POST['token'] != $token) {
			return FALSE;
		}

		if ($_GET['inAjax']==1 && $_GET['do']=='getUserInfo') {
			return $this->getUserInfo();
		}

		if ($_GET['inAjax']==1 && $_GET['do']=='submitUserInfo') {
			if ( empty($_POST['username']) || empty($_POST['steps']) || empty($_POST['usetime'])) {
				return FALSE;
			} else {
				$username = trim($_POST['username']);
				$steps = trim($_POST['steps']);
				$usetime = trim($_POST['usetime']);
				$bool = $this->submitUserInfo($username,$usetime,$steps);
				if ($bool) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		}
	}

	/*取出*/
	public function getUserInfo()
	{
		$time = date("Y-m-d h:i:s",time()-604800);
		$sql = "
		SELECT `username`,`usetime`,`steps` FROM `ninepic` WHERE `time` > ? ORDER BY `steps` LIMIT 0,20 ;
		";
		$query = $this->_db->prepare($sql);
		$query->execute( array($time) );
		$count = $query->fetchAll(constant("PDO::FETCH_ASSOC"));
		return json_encode($count);
	}

	/*录入*/
	public function submitUserInfo($username,$usetime,$steps)
	{
		$sql = "
		INSERT INTO `ninepic` (`username`, `usetime`, `steps`, `time`) VALUES (?,?,?,?);
		";
		$query = $this->_db->prepare($sql);
		$ret = $query->execute( array(htmlspecialchars($username),htmlspecialchars($usetime),$steps,date('Y-m-d H:i:s')) );
		if ($ret) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function getToken()
	{
		return $token = md5(date("Y-m-d H")."token");
	}
}

$a = new ninepic();
echo $a->index();