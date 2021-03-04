A Web-based Cooperative Micromouse Algorithm Simulator
============================

This web app can be used to test Micromouse algorithms in your
web browser.  It exposes a [Python Mouse
API](https://github.com/eniacluo/Micromouse/)
for controlling a simulated mouse running in a simulated maze.
You can type your mouse driver code directly in a textbox on the
web page.

There are a large number of mazes to test your mouse algorithm
.  These mazes have been used in Micromouse competitions.  The
source of these mazes are

[http://www.tcp4me.com/mmr/mazes/](http://www.tcp4me.com/mmr/mazes/)

Download and run locally
------------------------

You can download the latest source via

	git clone https://github.com/eniacluo/MicromouseSim.git

Open the file MicromouseSim/index.html in Firefox or Safari.
Chrome's security model won't allow this application run
locally only from a web server.

License
-------

This program is free software: you can redistribute it and/or
modify it under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of
the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public
License along with this program.  If not, see
[http://www.gnu.org/licenses/](http://www.gnu.org/licenses/)

enjoy,

Zhiwei Luo <eniacsimon@gmail.com>

Setting up MicromouseSim on Server
Tutorial for Ubuntu 14.04 LTS only.

Install packages.

	$ sudo apt-get update
	$ sudo apt-get install mysql-server

Enter the mysql root password: sensorweb!@#

	$ sudo apt-get install git apache2 php5 php5-mysqlnd python3-pip
	$ sudo pip3 install mysql-connector-python
	$ sudo service apache2 restart
	Deploy to the server.
	$ git clone https://github.com/eniacluo/MicromouseSim.git
	$ sudo cp -r MicromouseSim /var/www/html/

Configure the database.

	$ mysql -u root -p
Enter password: sensorweb!@#

mysql>

CREATE DATABASE micromouse;
USE micromouse;
CREATE TABLE sessions(
	id int auto_increment,
	userId varchar(100) NOT NULL,
	sessionId int,
	status varchar(20) DEFAULT 'dead',
	PRIMARY KEY (id)
);
CREATE TABLE robots(
	id int auto_increment,
	session_id int NOT NULL,
	robot_id int NOT NULL,
	robot_x int DEFAULT 0,
	robot_y int DEFAULT 0,
	direction varchar(10) DEFAULT 'up',
	PRIMARY KEY (id)
);
mysql > quit
Access database from outside the server (CORE)

	$ sudo nano /etc/mysql/my.cnf
Comment the line that begins with bind-address

	$ sudo /etc/init.d/mysql restart

	$ mysql -u root -p
Enter password: sensorweb!@#

mysql > grant all privileges on *.* to 'root'@'%' Identified by 'sensorweb!@#';

mysql > quit
Enable HTTPS for apache2
https://techexpert.tips/apache/enable-https-apache/ 
Run prepare.sh to start.

	$ sudo apt-get install core-network
	$ cd /var/www/html/MicromouseSim/
	$ sudo ./prepare.sh





