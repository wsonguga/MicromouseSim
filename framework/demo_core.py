#!/usr/bin/env python3

#Author: Zhiwei Luo

from map import Map
from mouse import Micromouse
from strategy_multidfs import StrategyMultiDFS
from controller_core import COREServerController
from socket import gethostname

mazeMap = Map(16, 16)
mazeMap.readFromFile('/tmp/micromouse/maze.txt')
micromouse = Micromouse(mazeMap)
index = str(int(gethostname()[1:]) % 5)
initPoint = {'1':(0,0), '2':(15,0), '3':(0,15), '4':(15,15)}
micromouse.setMotorController(COREServerController(index, initPoint[index], sessionId='sid', controlNet='172.16.255.254'))
micromouse.setInitPoint(initPoint[index][0], initPoint[index][1])
micromouse.addTask(StrategyMultiDFS(micromouse))
micromouse.run()
