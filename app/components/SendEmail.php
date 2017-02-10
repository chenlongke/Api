<?php
include REALPATH.'/app/library/class.phpmailer.php';
include REALPATH.'/app/library/class.smtp.php';
class SendEmail{
	public static function send($html="<h1>我是标题</h1>",$toEmail){
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = "smtp.163.com";//必填，设置SMTP服务器 QQ邮箱是smtp.qq.com ，QQ邮箱默认未开启，请在邮箱里设置开通。网易的是 smtp.163.com 或 smtp.126.com
		$mail->Username = "tanquyu@163.com";
		$mail->Password = "***"; //必填， 以上邮箱对应的密码
		$mail->CharSet="UTF8";
		$mail->From = "tanquyu@163.com";;
		$mail->FromName = "【邮箱注册校验】";
		$mail->Subject = "【邮箱注册校验】";
		$mail->AddAddress($toEmail,"我是测试");
		$mail->MsgHTML($html);
		$mail->IsHTML(true);
		if(!$mail->Send()) {
			return array("code"=>0,"msg"=>$mail->ErrorInfo);
		} else {
			return array("code"=>1,"msg"=>"success");
		}
	}
}
