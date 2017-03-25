<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"> 
	<title>用户注册</title>
	<link rel="stylesheet" href="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">  
	<script src="https://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<?php
	if(!empty($_POST)){
		//接受post数据
		$user_email =  htmlspecialchars($_POST['user_email']);
		$user_password = htmlspecialchars($_POST['user_password']);

		//实例化pdo，连接数据库
		$db = new PDO('mysql:host=localhost;dbname=test','root','1234',array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"));

		//sql查询
		$sql = 'SELECT * FROM `users` WHERE `user_email` = :user_email';

		//预处理
		$stmt = $db->prepare($sql);

		//绑定变量
		$stmt->bindParam(':user_email',$user_email);

		//执行操作
		$stmt->execute();

		//获取结果集
		$res = $stmt->fetchAll();

		if($res){
			echo "<script type='text/javascript'>alert('该用户已经存在!');</script>";
		}else{
			//新增sql
			$sql_inserts = 'INSERT INTO `users`(`user_email`,`user_password`) VALUES(:user_email,:user_password)';

			//预处理
			$inserts = $db->prepare($sql_inserts);

			//绑定变量
			$inserts->bindParam(':user_email',$user_email);
			$inserts->bindParam(':user_password',$user_password);

			//执行操作
			$inserts->execute();

			if($inserts){
				//获取时间
				$create_time = date("Y-m-d H:i:s");
				$update_time = date("Y-m-d H:i:s");

				//新增task sql
				$sql_task = 'INSERT INTO `task_list`(`user_email`,`status`,`create_time`,`update_time`) VALUES(:user_email,0,:create_time,:update_time)';

				//预处理
				$tasks = $db->prepare($sql_task);

				//绑定变量
				$tasks->bindParam(':user_email',$user_email);
				$tasks->bindParam(':create_time',$create_time);
				$tasks->bindParam(':update_time',$update_time);

				//执行操作
				$tasks->execute();
				if($tasks){
					echo "<script type='text/javascript'>$.post('do_queue.php');alert('注册成功!');</script>";
				}
			}else{
				echo "<script type='text/javascript'>alert('注册失败!');</script>";
			}
		}
	}
?>
<form class="form-horizontal" role="form" style="width: 40%;margin-top: 10px;" action="" method="post">
	<div class="form-group">
		<label for="firstname" class="col-sm-2 control-label">用户邮箱</label>
		<div class="col-sm-10">
			<input type="text" name="user_email" class="form-control" id="user_email" placeholder="请输入邮箱">
		</div>
	</div>
	<div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">用户密码</label>
		<div class="col-sm-10">
			<input type="password" name="user_password" class="form-control" id="user_password" placeholder="请输入密码">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default" id="btn">注册</button>
		</div>
	</div>
</form>
<script type="text/javascript">
	$("#btn").click(function(){
		var user_email = $("#user_email").val();
		var user_password = $("#user_password").val();
		if(user_email == ''){
			alert('邮箱不能为空!');
			return false;
		}
		if(user_password == ''){
			alert('密码不能为空!');
			return false;
		}
	});
</script>
</body>
</html>