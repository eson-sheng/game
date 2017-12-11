<?php 
class PV 
{
	private $_db = null;

	function __construct()
	{
		$this->_db = new PDO("mysql:host=172.31.51.104;dbname=lalala;","root","Eson112923!");
	}

	function index()
	{
		if (!empty($_POST['ip']) && !empty($_POST['address']) && !empty($_POST['url'])) {
			$ip = str_replace("'",'', trim($_POST['ip']));
			$address = str_replace("'",'', trim($_POST['address']));
			$url = urldecode(trim($_POST['url']));
		} else {
			return FALSE;
		}
		$bool = $this->yw($ip,$address,$url);
		if ($bool) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function yw($ip,$address,$url)
	{
		$sql = "SELECT * FROM `pv` WHERE ip=?;";
		$query = $this->_db->prepare($sql);
		$query->execute(array($ip));
		$count = $query->fetch();
		if ($count) {
			$num = $count['number'];
			$pv_id = $count['id'];
			$a = $this->update_pv($ip,$num);
			$b = $this->update_pvurl($pv_id,$url);
			if ($a&&$b) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			$a = $this->save_pv($ip,$address);
			$b = $this->save_pvurl($ip,$url);
			if ($a&&$b) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	function save_pv($ip,$address)
	{
		$sql = "
		INSERT INTO `pv` (`ip`, `address`, `number`, `createtime`, `status`) VALUES (?,?,?,?,?);
		";
		$query = $this->_db->prepare($sql);
		$ret = $query->execute( array($ip,$address,1,date('Y-m-d H:i:s'),1) );
		if ($ret) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function update_pv($ip,$num)
	{
		$num += 1;
		$sql = "
		UPDATE `pv` SET `number` = ? WHERE `ip` = ?;
		";
		$query = $this->_db->prepare($sql);
		$ret = $query->execute( array($num,$ip) );
		if ($ret) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function save_pvurl($ip,$url)
	{
		$sql = "
		SELECT `id` FROM `pv` WHERE ip = ? ;
		";
		$query = $this->_db->prepare($sql);
		$query->execute(array($ip));
		$data = $query->fetch();
		$pv_id = $data['id'];

		$sql = "
		INSERT INTO `pv_url` (`pv_id`, `url`, `number`, `createtime`, `status`) VALUES (?,?,?,?,?);
		";
		$query = $this->_db->prepare($sql);
		$ret = $query->execute( array($pv_id,$url,1,date('Y-m-d H:i:s'),1) );
		if ($ret) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function update_pvurl($pv_id,$url)
	{
		$sql = "
		SELECT `number` FROM `pv_url` WHERE `pv_id` = ? AND `url` = ? ;
		";
		$query = $this->_db->prepare($sql);
		$query->execute(array($pv_id,$url));
		$data = $query->fetch();
		if ($data) {
			$num = $data['number'] + 1;			
		} else {
			$sql = "
			INSERT INTO `pv_url` (`pv_id`, `url`, `number`, `createtime`, `status`) VALUES (?,?,?,?,?);
			";
			$query = $this->_db->prepare($sql);
			$ret = $query->execute( array($pv_id,$url,1,date('Y-m-d H:i:s'),1) );
			if ($ret) {
				return TRUE;
			} else {
				return FALSE;
			}
		}

		$sql = "
		UPDATE `pv_url` SET `number` = ? WHERE `pv_id` = ? AND `url` = ?;
		";
		$query = $this->_db->prepare($sql);
		$ret = $query->execute( array($num,$pv_id,$url) );
		if ($ret) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

$pv = new PV();
$bool = $pv->index(); 
echo json_encode(array('status'=>$bool));