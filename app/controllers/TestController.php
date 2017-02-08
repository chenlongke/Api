<?php
	class TestController extends BaseController {
		public function indexAction(){
			//echo time();
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