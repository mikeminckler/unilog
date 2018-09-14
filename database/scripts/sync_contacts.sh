#!/bin/bash
mysqldump -u noto_user -pNewcastle22 -h moodle.brentwood.bc.ca myschool_brentwood contacts | mysql -u noto_user -pNewcastle22 noto_brentwood

#mysql -u noto_user -pNewcastle22 -h moodle.brentwood.bc.ca noto_brentwood -e 'ALTER TABLE noto_brentwood.contacts CHANGE COLUMN id id_old BINARY(16) NOT NULL , CHANGE COLUMN user_id id MEDIUMINT(8) UNSIGNED NOT NULL;'

mysql -u noto_user -pNewcastle22 -h moodle.brentwood.bc.ca noto_brentwood -e 'ALTER TABLE noto_brentwood.contacts  CHANGE COLUMN `id` `id_old` BINARY(16) NOT NULL , CHANGE COLUMN `user_id` `id` MEDIUMINT(8) UNSIGNED NOT NULL;'
