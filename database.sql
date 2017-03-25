 create table users (
         user_id int(5) not null auto_increment,
         user_email varchar(40) not null,
         user_password char(32) not null,
         primary key(user_id)
 )engine=myisam default charset=utf8;

create table task_list (
	task_id int(5) not null auto_increment,
	user_email varchar(40) not null,
	status int(2) not null,
	create_time datetime not null,
	update_time datetime not null,
	primary key(task_id)
)engine=myisam default charset utf8;



insert into task_list(user_email,status,create_time,update_time)
	VALUES( 'phpjiaoxuedev@sina.com', 0, now(), now() );
insert into task_list(user_email,status,create_time,update_time)
        VALUES( 'phpjiaoxuedev1@sina.com', 0, now(), now() );
