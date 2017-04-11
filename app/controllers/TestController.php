<?php

class TestController extends BaseController {

	public function indexAction(){
		$this->view->title = "测试"; 
		$order = $this->db->fetchAll("select * from `order` where status = ? ",Phalcon\Db::FETCH_ASSOC,['wait_pay']);
		$this->view->list = $order;	
	}

	public function buildOrderAction(){
		$orderNo = $this->_makeOrderNo();

		$createArray = [
			$orderNo,
			23.6,
			'wait_pay'
		];

		$this->db->execute("insert into `order` (`orderNo`,`amount`,`status`) values (?,?,?)",$createArray);

		$order = $this->db->fetchOne("select * from `order` where `orderNo` = ?",Phalcon\Db::FETCH_ASSOC,[$orderNo]);

		echo json_encode($order);
	}

	public function payAction(){
		$orderNo = isset($_POST['orderNo']) ? $_POST['orderNo'] : '';
		if(empty($orderNo)){
			echo "缺少参数";
			exit();
		}
		$html = '
			<!DOCTYPE html>
			<html>
			<head>
				<title></title>
			</head>
			<body>
				<form action="https://www.baidu.com/s" method="get" name="baidu">
					<input type="hidden" value="谭咏麟" name="word">
					</form>
					<script>
						baidu.submit();
					</script>
			</body>
			</html>
		';
		echo $html;
	}

	public function getPayAction(){
		$orderNo = isset($_POST['orderNo']) ? $_POST['orderNo'] : '';
		$result = ["code" => 0,"message"=>"{$orderNo}没有支付"];
		echo json_encode($result);
	}


	private function _makeOrderNo($uid = 52889 , $bizNo = 5){
		// time() + uid + 业务号
		$this->db->begin();
		$orderNo = false;
		try{
			$key = $this->db->query("SELECT `id` from bizno order by id desc limit 1;")->fetch(PDO::FETCH_ASSOC);
			$time = time();
			$this->db->execute("insert into `bizno` (`code`) values (?)",[$time]);
			$this->db->commit();
			$orderNo =  $key['id'] . $time . $uid . $bizNo;
		}catch(Eexception $e){
			$this->db->rollback ();
		}
		return $orderNo;			
	}
}