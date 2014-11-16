--
-- Database: `cs351`
--

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `staff` (`staff`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `timestamp`, `user`, `staff`, `value`) VALUES
(1, '2014-11-07 05:40:53', 6, 1, 3),
(2, '2014-11-07 05:41:03', 6, 1, -2),
(3, '2014-11-07 07:43:34', 6, 5, 2),
(4, '2014-11-07 09:16:58', 4, 1, 5),
(5, '2014-11-07 09:17:08', 4, 1, -2),
(6, '2014-11-07 09:47:16', 4, 8, 5),
(7, '2014-11-12 04:48:19', 6, 9, 2),
(8, '2014-11-12 08:45:10', 4, 1, 1),
(9, '2014-11-12 09:42:52', 4, 6, 2),
(10, '2014-11-12 19:39:03', 4, 6, 2),
(11, '2014-11-13 05:00:36', 6, 4, 2),
(12, '2014-11-13 05:01:36', 6, 4, 1),
(13, '2014-11-13 05:02:43', 4, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rollno` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hashed_pass` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `blocked` tinyint(1) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `rollno`, `name`, `email`, `hashed_pass`, `type`, `balance`, `blocked`, `approved`) VALUES
(1, '', 'Murali', 'm', '6f8f57715090da2632453988d9a1501b', 1, 5, 0, 1),
(2, '', 'Chaitanya', 'c', '4a8a08f09d37b73795649038408b5f33', 2, 5, 0, 0),
(3, '', 'Vidyasai', 'vidyaranya.ns@gmail.com', '9e3669d19b675bd57058fd4664205d2a', 0, 5, 0, 0),
(4, '', 'Harsh Mohan', 'h', '2510c39011c5be704182423e3a695e91', 0, 6, 1, 0),
(5, '', 'Vidyasai', 'v', '9e3669d19b675bd57058fd4664205d2a', 1, 5, 0, 1),
(6, '', 'Surya Teja Thogaru', 'a', '0cc175b9c0f1b6a831c399e269772661', 0, 5, 0, 1),
(7, '', 'Bharath Subramanyan', 's', '03c7c0ace395d80182db07ae2c30f034', 1, 5, 0, 1),
(8, '', 'Anmol', 'a@a', '0cc175b9c0f1b6a831c399e269772661', 1, 5, 0, 1),
(9, '', 'j', 'j', '363b122c528f54df4a0446b6bab05515', 1, 5, 0, 1),
(10, '', 'Ashley Phillips', 'aphillips0@soup.io', '', 1, 0, 0, 1),
(11, '', 'Dennis Ford', 'dford1@who.int', '', 2, 0, 0, 0),
(12, '', 'Randy Harris', 'rharris2@miitbeian.gov.cn', '', 2, 0, 0, 0),
(13, '', 'Mildred Kelly', 'mkelly3@domainmarket.com', '', 2, 0, 0, 0),
(14, '', 'Larry Johnson', 'ljohnson4@tinypic.com', '', 1, 0, 0, 1),
(15, '', 'Sean Mason', 'smason5@columbia.edu', '', 2, 0, 0, 0),
(16, '', 'Lawrence Ellis', 'lellis6@scribd.com', '', 2, 0, 0, 0),
(17, '', 'Nicole Walker', 'nwalker7@gravatar.com', '', 1, 0, 0, 0),
(18, '', 'Brian Dixon', 'bdixon8@ed.gov', '', 0, 0, 0, 0),
(19, '', 'Julia Chavez', 'jchavez9@reddit.com', '', 0, 0, 0, 0),
(20, '', 'Denise Stewart', 'dstewarta@qq.com', '', 0, 0, 0, 0),
(21, '', 'Johnny Allen', 'jallenb@umn.edu', '', 1, 0, 0, 1),
(22, '', 'Cynthia Kelley', 'ckelleyc@mashable.com', '', 2, 0, 0, 0),
(23, '', 'Barbara Montgomery', 'bmontgomeryd@sciencedaily.com', '', 0, 0, 0, 0),
(24, '', 'Doris Lee', 'dleee@ycombinator.com', '', 1, 0, 0, 0),
(25, '', 'Ruby Campbell', 'rcampbellf@posterous.com', '', 1, 0, 0, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`staff`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`user`) REFERENCES `user` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `MONTHLY_COUPONS` ON SCHEDULE EVERY 1 MONTH STARTS '2014-11-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE user SET balance=balance+60 WHERE type=0$$

DELIMITER ;

