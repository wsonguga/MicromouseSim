#!/usr/bin/env python3

from task import Strategy
from time import sleep

class StrategyBlank(Strategy):
	mouse = None

	def __init__(self, mouse):
		self.mouse = mouse

	def checkFinished(self):
		return False

	def go(self):
		sleep(0.5)