#!/bin/sh

cd $(cd `dirname $0`; pwd)

num=1
while [ $num -le 10 ]
do
	dist='../../hotel'$num'/cron'
#	echo $dist
	cd $dist
	/usr/local/bin/php midnight_mail.php >> ../../madmin/cron/mail.log
	cd ../../madmin/cron
	num=`expr $num + 1`
done
