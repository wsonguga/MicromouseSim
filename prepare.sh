#!/bin/sh

if [ `whoami` != "root" ];
then
	echo "use sudo to run."
	exit 1 
fi
if [ ! -d "/tmp/micromouse" ];
then
	mkdir /tmp/micromouse/
fi
chmod a+w /tmp/micromouse
cp maze.sh php_root runStrategy.sh stopStrategy.sh killp.sh /tmp/micromouse/
core-gui --batch sessions.imn | grep Session | awk '{print $6}' > /tmp/micromouse/sessionId.txt
cd /tmp/micromouse/
chmod a+x maze.sh runStrategy.sh stopStrategy.sh killp.sh
chmod u=rwx,go=xr,+s php_root

