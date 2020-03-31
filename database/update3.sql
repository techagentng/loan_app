-- Dumping structure for table kloans.kpos_guarantee
DROP TABLE IF EXISTS `kpos_guarantee`;
CREATE TABLE IF NOT EXISTS `kpos_guarantee` (
  `guarantee_id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(300) NOT NULL,
  `type` varchar(300) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `images` varchar(1000) NOT NULL,
  `observations` varchar(1000) NOT NULL,
  PRIMARY KEY (`guarantee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;