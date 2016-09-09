#!/bin/bash
mysqldump -u myschool_ro -pNewcastle44 -h cirdan.brentwood.bc.ca MYSCHOOL_LOCAL contacts | mysql -u root -pNewcastle22 noto_brentwood
mysql -u root -pNewcastle22 noto_brentwood -e "ALTER TABLE noto_brentwood.contacts CHANGE COLUMN user_id id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT"
