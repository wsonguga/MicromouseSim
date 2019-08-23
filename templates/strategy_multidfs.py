#!/usr/bin/env python3

#Author: Zhiwei Luo

from strategy import Strategy
from time import sleep
from socket import *
import _pickle as pickle
import threading

isVisited = {}
path = []
isBack = False

udpPort = 6666
broadcastAddr = '10.0.0.255'
bufferList = []

class StrategyMultiDFS(Strategy):
	def __init__(self, _mouse):
		global mouse
		mouse = _mouse
		for i in range(mouse.mazeMap.width):
			for j in range(mouse.mazeMap.height):
				isVisited[(j, i)] = 0
		isVisited[(mouse.x, mouse.y)] = 1
		global socketUdp
		socketUdp = socket(AF_INET, SOCK_DGRAM)
		socketUdp.bind(('', udpPort))
		socketUdp.setsockopt(SOL_SOCKET, SO_BROADCAST, 1)
		threadReceive = threading.Thread(name='receive', target=self.receiveDataThread)
		threadReceive.setDaemon(True)
		threadReceive.start()

	def checkFinished(self):
		return isBack

	def go(self):
		global mouse
		mouse.senseWalls()
		sendData = {}
		sendData['x'] = mouse.x
		sendData['y'] = mouse.y
		sendData['up'] = not mouse.canGoUp()
		sendData['down'] = not mouse.canGoDown()
		sendData['left'] = not mouse.canGoLeft()
		sendData['right'] = not mouse.canGoRight()
		socketUdp.sendto(pickle.dumps(sendData), (broadcastAddr, udpPort))
		# Update local map from the information sent by other robots
		global bufferList
		while len(bufferList) > 0:
			recvData = pickle.loads(bufferList[0])
			maze = mouse.mazeMap
			cell = maze.getCell(recvData['x'], recvData['y'])
			isVisited[(recvData['x'], recvData['y'])] = 1
			if recvData['up']: maze.setCellUpAsWall(cell)
			if recvData['down']: maze.setCellDownAsWall(cell)
			if recvData['left']: maze.setCellLeftAsWall(cell)
			if recvData['right']: maze.setCellRightAsWall(cell)
			bufferList = bufferList[1:]

		if mouse.canGoLeft() and not isVisited[(mouse.x-1, mouse.y)]:
			path.append([mouse.x, mouse.y])
			isVisited[(mouse.x-1, mouse.y)] = 1
			mouse.goLeft()
		elif mouse.canGoUp() and not isVisited[(mouse.x, mouse.y-1)]:
			path.append([mouse.x, mouse.y])
			isVisited[(mouse.x, mouse.y-1)] = 1
			mouse.goUp()
		elif mouse.canGoRight() and not isVisited[(mouse.x+1, mouse.y)]:
			path.append([mouse.x, mouse.y])
			isVisited[(mouse.x+1, mouse.y)] = 1
			mouse.goRight()
		elif mouse.canGoDown() and not isVisited[(mouse.x, mouse.y+1)]:
			path.append([mouse.x, mouse.y])
			isVisited[(mouse.x, mouse.y+1)] = 1
			mouse.goDown()
		else:
			if len(path) != 0:
				x, y = path.pop()
				if x < mouse.x:
					mouse.goLeft()
				elif x > mouse.x:
					mouse.goRight()
				elif y < mouse.y:
					mouse.goUp()
				elif y > mouse.y:
					mouse.goDown()
			else:
				isBack = True

		sleep(0.2)

	def receiveDataThread(self):
		global socketUdp
		global bufferList
		while True:
			dataRecv, addr = socketUdp.recvfrom(1000)
			bufferList.append(dataRecv)