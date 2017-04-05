<?php
	class Express{

	protected $key = '';			# key 
	
	protected $schema = '';			# 返回数据格式
	
	protected $domain = '';			# 快递API请求地址

	protected $callback = '';		# 回调地址
	
	protected $salt = '';			# 签名

	protected $error = '';			# 错误信息

	protected $status;				# 单号监控状态

	protected $status_message = ''; # 监控状态解释

	protected $state;				# 单号配送状态

	protected $state_message = '';  # 配送状态解释

	/**
	 * __construct 
	 * 初始化函数
	 * @access public
	 * @return void
	 */

	public function __construct($callback = "https://test.odamiao.com/Express/callback",$key="TztPXJoZ1747",$schema = 'json',$domain = "http://www.kuaidi100.com/poll") {
		#读取配置 初始化类属性
		$this->key = $key;
		$this->schema = $schema;
		$this->domain = $domain;
		$this->callback = $callback;
	}


	/**
	 * poll 
	 * 发动请求
	 * @param mixed $code  快递代码
	 * @param mixed $number 快递单号
	 * @param mixed $to 快递送达地址
	 * @param str   $arguments 附加参数
	 * @access public 
	 * @return void 返回请求结果
	 */
	
	public function poll($code ,$number ,$to ,$arguments) {
		$post_data["schema"] = $this->schema;

		# 拼装参数
		$callback = $this->callback;
		$callback .= '?'.$arguments;
		$post_data["param"] = 
			'{"company":"'.$code
			.'", "number":"'.$number
			.'","to":"'.$to
			.'", "key":"'.$this->key
			.'", "parameters":{"callbackurl":"'.$callback
			.'"}}';
		$url = $this->domain;
		
		# curl post数据
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		$result = curl_exec($ch);
		curl_close($ch);
		# 转换为标准数组
		if(!$result){
			$this->error = '数据推送失败!请联系网站技术人员解决!';
			return 0;
		}
		$result = json_decode($result,true);
		
		# 判断并返回结果
		return $this->judge($result);
	}	

	/**
	 * dispose_back 
	 * 解析callback回调参数
	 * @param mixed $request 
	 * @access public
	 * @return void
	 * 返回数据结构
	 * array(
	 * 'status'=>0,
	 * 'status_message'=>"123",
	 * 'state' =>'1',
	 * 'state_message'=> '123',
	 *	... ...
	 * )
	 */
	public function dispose_back($request) {
		# 转换为标准数组
		$request = object_array(json_decode($request['param']));
		#签名判断

//		$request_md5 = $request['signValue'];
//		$md5 = md5($request['param'].$this->sign);
//		if($request_md5 != $md5) {
//			return 0;
//		}
		# 解析单号监控状态
		$this->dispose_status($request['status'], $request['message']);

		# 解析单号运送状态
		$this->dispose_state($request['lastResult']['state']);
		
		# 拼凑数组
		$data = null;
		$data['push_status'] = $this->status;
		$data['push_message'] = $this->status_message;
		$data['com'] = $request['lastResult']['com'];
		$data['invoice_no'] = $request['lastResult']['nu'];
		if($data['push_status'] != 2){
			$data['data_info'] = json_encode($request['lastResult']['data']);
			$data['express_status'] = $this->state;
			$data['express_message'] = $this->state_message;
		}elseif($data['push_status'] == 3 ){
			$express_info = json_decode($request['lastResult']['data']);
			$data['sign_time'] = $express_info[0][time];
		}else{
			$data['express_status'] = 5;
			$data['express_message'] = "快递单号异常!";
		}
		return $data;
	}

	/**
	 * dispose_state 
	 * 单号配送状态解析
	 * 0在途中、1已揽收、2疑难、3已签收 4退签、5同城派送中、6退回、7转单
	 * @param mixed $code 
	 * @access private
	 * @return void 返回 0 异常状态 1 正常状态
	 */
	private function dispose_state($code) {
		switch($code) {
			case '0':
				$this->state = 0;
				$this->state_message = '运送途中...';
				return 1;
			case '1':
				$this->state = 1;
				$this->state_message = '已经揽收';
				return 1;
			case '2':
				$this->state = 2;
				$this->state_message = '疑难件';
				return 0;
			case '3':
				$this->state = 3;
				$this->state_message = '已签收';
				return 1;
			case '4':
				$this->state = 4;
				$this->state_message = '退签';
				return 1;
			case '5':
				$this->state = 9;
				$this->state_message = '同城派送中';
				return 1;
			case '6':
				$this->state = 6;
				$this->state_message = '退回';
				return 1;
			case '7':
				$this->state = 7;
				$this->state_message = '转单';
				return 1;
			default:
				$this->state = 8;
				$this->state_message = '错误!未知状态';
				return 0;
		}
	}

	/**
	 * dispose_status 
	 * 单号监控状态解析
	 * polling:监控中，shutdown:结束，abort:中止，updateall：重新推送
	 * @param mixed $code 
	 * @param mixed $message 
	 * @access private
	 * @return void 返回0 异常状态 1正常
	 */
	private function dispose_status($code, $message) {
		switch($code) {
			case 'polling':
				$this->status = 0;
				$this->status_message = '单号监控中...';
				return 1;
			case 'shutdown':
				$this->status = 1;
				$this->status_message = '监控结束';
				return 1;
			case 'abort':
				$this->status = 2;
				$this->status_message = '监控异常: '.$message;
				return 0;
			case 'updateall':
				$this->status = 3;
				$this->status_message = '重新推送中';
				return 1;
			default:
				$this->status = 4;
				$this->status_message = '未知状态';
				return 0;
		}
	}

	/**
	 * judge 
	 * 错误代码判断
	 * @param mixed $data
	 * @access private
	 * @return void 失败返回0 成功返回1 
	 */
	private function judge($data) {
		$return_code = $data['returnCode'];
		switch($return_code) {
			case '200':         # 订阅成功
				return 1;
			case '701':
				$this->error = '拒绝订阅的快递公司!';
				return 0;
			case '700':
				$this->error = '不支持的快递公司!';
				return 0;
			case '600':
				$this->error = '您不是合法的订阅者!';
				return 0;
			case '500':
				$this->error = '服务器错误(提交数据有误,或请求服务器出错!)';
				return 0;
			case '501':
				$this->error = '重复订阅!';
				return 2;
			default:
				$this->error = '未知错误!请联系网站技术人员!';
				return 0;
		}
	}

	/**
	 * getError 
	 * 返回错误信息
	 * @access public
	 * @return void
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * request 
	 * 返回接收结果
	 * @param mixed $flag true=>成功 false=>失败
	 * @access public
	 * @return void
	 */
	public function request($flag) {
		if($flag) {
			echo  '{"result":"true","returnCode":"200","message":"成功"}';
			exit;
		}else{
			# 返回该结果后30分钟会再次收到推送请求
			echo  '{"result":"false",	"returnCode":"500","message":"失败"}';
			exit;
		}
	}
}