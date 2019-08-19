#!/usr/bin/env python3

#Author: Zhiwei Luo

from strategy import Strategy
from time import sleep
from socket import *
import _pickle as pickle
import threading

class StrategyMultiDFS(Strategy):
	mouse = None
	isVisited = []
	path = []
	isBack = False

	udpPort = 6666
	broadcastAddr = '10.0.0.255'
	bufferList = []

	def __init__(self, mouse):
		self.mouse = mouse
		self.isVisited = [[0 for i in range(self.mouse.mazeMap.width)] for j in range(self.mouse.mazeMap.height)]
		self.isVisited[self.mouse.x][self.mouse.y] = 1
		self.socketUdp = socket(AF_INET, SOCK_DGRAM)
		self.socketUdp.bind(('', self.udpPort))
		self.socketUdp.setsockopt(SOL_SOCKET, SO_BROADCAST, 1)
		self.threadReceive = threading.Thread(name='receive', target=self.receiveDataThread)
		self.threadReceive.setDaemon(True)
		self.threadReceive.start()

	def checkFinished(self):
		return self.isBack

	def go(self):
		self.mouse.senseWalls()
		sendData = {}
		sendData['x'] = self.mouse.x
		sendData['y'] = self.mouse.y
		sendData['up'] = not self.mouse.canGoUp()
		sendData['down'] = not self.mouse.canGoDown()
		sendData['left'] = not self.mouse.canGoLeft()
		sendData['right'] = not self.mouse.canGoRight()
		self.socketUdp.sendto(pickle.dumps(sendData), (self.broadcastAddr, self.udpPort))
		# Update local map from the information sent by other robots
		while len(self.bufferList) > 0:
			recvData = pickle.loads(self.bufferList[0])
			cell = self.mouse.mazeMap.getCell(recvData['x'], recvData['y'])
			self.isVisited[recvData['x']][recvData['y']] = 1
			if recvData['up']: self.mouse.mazeMap.setCellUpAsWall(cell)
			if recvData['down']: self.mouse.mazeMap.setCellDownAsWall(cell)
			if recvData['left']: self.mouse.mazeMap.setCellLeftAsWall(cell)
			if recvData['right']: self.mouse.mazeMap.setCellRightAsWall(cell)
			self.bufferList = self.bufferList[1:]

		if self.mouse.canGoLeft() and not self.isVisited[self.mouse.x-1][self.mouse.y]:
			self.path.append([self.mouse.x, self.mouse.y])
			self.isVisited[self.mouse.x-1][self.mouse.y] = 1
			self.mouse.goLeft()
		elif self.mouse.canGoUp() and not self.isVisited[self.mouse.x][self.mouse.y-1]:
			self.path.append([self.mouse.x, self.mouse.y])
			self.isVisited[self.mouse.x][self.mouse.y-1] = 1
			self.mouse.goUp()
		elif self.mouse.canGoRight() and not self.isVisited[self.mouse.x+1][self.mouse.y]:
			self.path.append([self.mouse.x, self.mouse.y])
			self.isVisited[self.mouse.x+1][self.mouse.y] = 1
			self.mouse.goRight()
		elif self.mouse.canGoDown() and not self.isVisited[self.mouse.x][self.mouse.y+1]:
			self.path.append([self.mouse.x, self.mouse.y])
			self.isVisited[self.mouse.x][self.mouse.y+1] = 1
			self.mouse.goDown()
		else:
			if len(self.path) != 0:
				x, y = self.path.pop()
				if x < self.mouse.x:
					self.mouse.goLeft()
				elif x > self.mouse.x:
					self.mouse.goRight()
				elif y < self.mouse.y:
					self.mouse.goUp()
				elif y > self.mouse.y:
					self.mouse.goDown()
			else:
				self.isBack = True

		sleep(0.5)

	def receiveDataThread(self):
		while True:
			dataRecv, addr = self.socketUdp.recvfrom(1000)
			self.bufferList.append(dataRecv)