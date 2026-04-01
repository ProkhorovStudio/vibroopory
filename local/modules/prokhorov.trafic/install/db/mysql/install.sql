CREATE TABLE IF NOT EXISTS `prohorov_ip_list` (
    `ID` int(11) NOT NULL auto_increment PRIMARY KEY,
    `NAME` varchar(100) NOT NULL,
    `DATE_CREATE_FIRST` timestamp NOT NULL default CURRENT_TIMESTAMP,
    `DATE_CREATE_LAST` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    `TYPE_RULES` int(11),
    `ID_RULES` int(11)
    );

CREATE TABLE IF NOT EXISTS `prohorov_ip_black_list` (
    `ID` int(11) NOT NULL auto_increment PRIMARY KEY,
    `NAME` varchar(100) NOT NULL,
    `DATE_CREATE` timestamp NOT NULL default CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `prohorov_ip_grey_list` (
    `ID` int(11) NOT NULL auto_increment PRIMARY KEY,
    `NAME` varchar(100) NOT NULL,
    `DATE_CREATE` timestamp NOT NULL default CURRENT_TIMESTAMP
);