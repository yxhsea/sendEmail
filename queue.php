<?php
	require './PHPMailer/PHPMailerAutoload.php';

	function sendMail($host,$fromEmail,$fromPwd,$fromName,$toEmail,$toName,$subject,$content){
		//实例化
		$mail = new PHPMailer;

		//设置邮件使用SMTP
		$mail->isSMTP();
		
		//邮件服务器地址
		$mail->Host = $host;

		//启用SMTP身份认证
		$mail->SMTPAuth = true;  

		//设置邮件编码
		$mail->Charset = "UTF-8";

		//使用base64加密邮箱和密码
		$mail->Username = $fromEmail;
		$mail->Password = $fromPwd;

		//发件人邮箱地址
		$mail->From = $fromEmail;

		//发件人名称
		$mail->FromName = $fromName;

		//添加接受者
		$mail->addAddress($toEmail,$toName);

		//设置邮件格式为HTML
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->msgHTML($content);
		return $mail->send();
	}

	//实例pdo对象，连接数据库
	$db = new PDO('mysql:host=localhost;dbname=test','root','1234',array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"));
	while(true){
		//查询sql
		$sql = 'SELECT * FROM `task_list` WHERE `status` = 0 LIMIT 5';

		//预处理
		$stmt = $db->prepare($sql);

		//执行操作
		$stmt->execute();

		//获取数据
		$data = $stmt->fetchAll();

		if(!empty($data)){
			foreach ($data as $value) {
				//发送邮件
				$mail = sendMail('smtp.163.com','18373****691@163.com','*************','18373288691@163.com',$value['user_email'],'email','主题','内容');
				if($mail){
					//发送后，更新status为1
					$sql_update = 'UPDATE `task_list` SET `status` = 1 WHERE `task_id` = :task_id';

					//预处理
					$upd = $db->prepare($sql_update);

					//绑定变量
					$upd->bindParam(':task_id',$value['task_id']);

					//执行操作
					$upd->execute();
				}
			}
			sleep(3);
		}else{
			break;
		}
	}
