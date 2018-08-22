#!/bin/bash
mysqldump -u noto_user -pNewcastle22 -h moodle.brentwood.bc.ca myschool_brentwood contacts | mysql -u noto_user -pNewcastle22 noto_brentwood
