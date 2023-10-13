create table users (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `first_name` varchar(255) not null,
    `last_name` varchar(255) not null,
    `birthday` DATETIME(6) default null,
    `city` varchar(255) default null,
    `age` int(11) not null,
    `active` tinyint(1) not null default 1,
    `some_float_val` float(10,2) not null,
    `admin` tinyint(1) not null default 0,
    PRIMARY KEY (`id`)
);
INSERT INTO users (`id`, `first_name`, `last_name`, `age`, `some_float_val`) VALUES
(1, 'user1', 'user1', 5, 10.1),
(2, 'user2', 'user2', 6, 10.1),
(3, 'user3', 'user3', 7, 10.1),
(4, 'user4', 'user4', 8, 10.1),
(5, 'user5', 'user5', 9, 10.1);
