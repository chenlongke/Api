<?php
class IndexController extends BaseController{
	private $user_nick = null;
	private $gatewayUrl = "http://baidu.com";
	private $sdkVersion = "test-clk-xf9";
	private $secretKey = "sdfsdfsdfdsf46464";
	public function initialize(){
		$this->user_nick="chen923897754";		
	}
	public function indexAction(){
		/*$list = [["姓名","年龄","性别","出生日期","爱好","预留"],["张彬彬","25","男","2016年8月15日 18:49:46","女","SB"],["张彬彬","25","男","2016年8月15日 18:49:46","女","SB"],["张彬彬","25","男","2016年8月15日 18:49:46","女","SB"]];
		for($i=0;$i<count($list);$i++){
			$lie = $i+1;
			$data = $list[$i];
			for($j=0;$j<count($data);$j++ ) {
				$key = 65+$j;
				echo chr($key).$lie.$data[$j]."\t";
			}
			echo "<br>";
		}*/
		
		$method = "xh9.trade.getOrder";
		/*$session = "sdf464646546sf6ds4f6d4f";*/
		$apiParams = array(
			"fields"=>"tid,pay_time",
			"start_time"=>"2016-06-06 12:12:00",
			"end_time"=>"2016-08-06 12:12:00",
		);
		$this->test($method,$apiParams);
	}
}