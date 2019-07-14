#!/usr/bin/env python3

#Author: Simon Luo

import mysql.connector as cli
import time

import random

cnx = cli.connect(	host='127.0.0.1', user='root', 
					passwd='luoeniac43', database='test_db')
cursor = cnx.cursor()

count = 0
while count < 100:
	for i in range(4):
		sql = "UPDATE test_table SET robot_x = %d, robot_y = %d WHERE robot_id = %d;" % (random.randint(0, 15), random.randint(0, 15), (i+1))
		print(sql)

		cursor.execute(sql)
		cnx.commit()
	count = count + 1
	time.sleep(1)

cursor.close()
cnx.close()

