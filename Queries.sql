-- Active: 1716923648200@@talsprddb02.int.its.rmit.edu.au@3306@COSC3046_2402_G3

-- ALL SQL QUERIES USED TO ACCESS/CREATE DATABASE
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `theme` enum('light','dark') DEFAULT 'light',
  `profile_image` varchar(255) DEFAULT NULL,
  `failed_attempts` int(11) DEFAULT '0',
  `last_failed_login` timestamp NULL DEFAULT NULL,
  `archived` tinyint(1) DEFAULT '0',
  `isAdmin` tinyint(1) DEFAULT '0',
  `isTutor` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1

ALTER TABLE `users`
MODIFY COLUMN `last_failed_login` DATETIME NULL DEFAULT NULL;

-- THIS CODE IS TO HARD RESET THE TIMER
UPDATE users SET failed_attempts = 0, last_failed_login = NULL WHERE username = 'testuser';


CREATE TABLE `threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_path` varchar(255) DEFAULT NULL,
  `archived` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1
ALTER TABLE `threads` ADD FULLTEXT(`title`, `content`);

CREATE TABLE `replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archived` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `thread_id` (`thread_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`),
  CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1

ALTER TABLE `threads` ADD FULLTEXT(`title`);
  
CREATE TABLE whiteboard_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_data TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    tutor_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tutor_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE CouponCodes (
    CouponCode VARCHAR(50) PRIMARY KEY,
    ExpirationDate DATE NOT NULL,
    ActivationDate DATE NOT NULL
);
ALTER TABLE CouponCodes
ADD COLUMN DiscountPercentage DECIMAL(5, 2) NOT NULL DEFAULT 0.00;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orderid VARCHAR(255) NOT NULL,
    userid VARCHAR(255) NOT NULL,
    items_ordered JSON NOT NULL,
    total_cost DECIMAL(10, 2) NOT NULL,
    purchase_date DATETIME NOT NULL
);

