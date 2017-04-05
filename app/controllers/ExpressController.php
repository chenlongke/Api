<?php

	class ExpressController extends Phalcon\Mvc\Controller {
		
		private $log = null;

		public function initialize(){
			$this->log = $this->di->get('logger');
		}


		public function pushAction(){
			$kd = new Express();			
			$kdRs = $kd->poll("yuantong" ,"884525123079468982" ,"" ,"type=sb&id=1993528");
			$this->log->info("推送结果|||".json_encode($kdRs));
			var_dump($kd);
		}

		public function CallbackAction(){
			$data["p"] = json_encode($_POST);
			$data['g'] = json_encode($_GET);
			$this->log->info(json_encode($data));
		}
	}