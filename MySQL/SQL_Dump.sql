DROP TABLE items;

CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemtype_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `attributes` json DEFAULT NULL,
  `visible` bit(1) NOT NULL DEFAULT b'1',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `itemType_id_idx` (`itemtype_id`),
  KEY `location_id_idx` (`location_id`),
  CONSTRAINT `itemtype_id` FOREIGN KEY (`itemtype_id`) REFERENCES `itemtypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `location_id` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

INSERT INTO items VALUES("1","4","10","{\"s\": \"s\", \"test\": \"test\", \"fdzut\": \"fezeafe\", \"test2\": \"test2\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("2","2","2","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("3","2","3","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("4","4","10","{\"a\": \"test\", \"b\": \"b\", \"z\": \"test\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("5","2","2","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("6","3","2","[]","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("7","3","2","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("8","3","5","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("9","2","3","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("10","2","4","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("11","1","7","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("13","1","2","{\"Attr\": \"Stoel\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("15","4","10","[]","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("16","3","10","{\"Merk\": \"Merk A\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("17","4","10","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("18","4","10","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("19","4","10","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("20","4","10","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("21","4","10","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("22","4","10","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("23","4","10","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("24","4","10","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("25","4","10","","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("26","4","10","[]","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("27","4","10","{\"Test\": \"Test\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("28","4","10","{\"Nog\": \"Nog\", \"Test\": \"Test\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("29","4","10","{\"zafe zfe zfe\": \"zafe zfe zfe\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("30","4","10","{\"deazdeza ezd dez d\": \"deazdeza ezd dez d\", \"ezfqeqzfez fez fez fezf ez\": \"ezfqeqzfez fez fez fezf ez\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("31","4","10","{\"Test\": \"Test\", \"test\": \"test\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("32","4","10","{\"Test\": \"nog een test\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("33","4","10","[]","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("34","4","10","{\"Merk\": \"Merk B\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("35","4","10","[]","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("36","3","10","{\"Merk\": \"Philips\"}","1","2017-02-13 14:40:19");
INSERT INTO items VALUES("37","4","10","{\"\": \"\"}","1","2017-02-13 16:18:31");



DROP TABLE itemtypes;

CREATE TABLE `itemtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO itemtypes VALUES("4","Bank");
INSERT INTO itemtypes VALUES("3","Scherm");
INSERT INTO itemtypes VALUES("1","Stoel");
INSERT INTO itemtypes VALUES("2","Tafel");



DROP TABLE locations;

CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `naam_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

INSERT INTO locations VALUES("10","AB");
INSERT INTO locations VALUES("13","ABB");
INSERT INTO locations VALUES("12","ABC");
INSERT INTO locations VALUES("15","ABCB");
INSERT INTO locations VALUES("14","ABCZ");
INSERT INTO locations VALUES("11","CB");
INSERT INTO locations VALUES("2","Location1");
INSERT INTO locations VALUES("3","Location2");
INSERT INTO locations VALUES("4","Location3");
INSERT INTO locations VALUES("5","Location4");
INSERT INTO locations VALUES("6","Location5");
INSERT INTO locations VALUES("7","Location6");
INSERT INTO locations VALUES("8","Location7");
INSERT INTO locations VALUES("9","Location8");
INSERT INTO locations VALUES("16","test");



DROP TABLE usernotes;

CREATE TABLE `usernotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `text` varchar(1024) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id_idx` (`item_id`),
  CONSTRAINT `item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




