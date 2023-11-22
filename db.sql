CREATE DATABASE `chatapp`;

CREATE TABLE `chatapp`.`messages` (
`msg_id` INT(11) NOT NULL auto_increment,
`incoming_msg_id` int(255) not null,
`outgoing_msg_id` int(255) not null,
`msg` varchar(1000) not null,
`msg_status` varchar(255) not null,
primary key (`msg_id`)
) ENGINE=InnoDB default charset=utf8mb4;

CREATE TABLE `chatapp`.`users` (
  `user_id` int(11) NOT NULL auto_increment,
  `unique_id` int(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  primary key(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;