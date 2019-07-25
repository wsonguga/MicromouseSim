#!/bin/sh

clsName=`cat /tmp/micromouse/strategy_test.py | grep class | cut -d' ' -f2 | cut -d'(' -f1`

sed -i 's/strategy_multidfs/strategy_test/' /tmp/micromouse/demo_core.py

sed -i "s/StrategyMultiDFS/$clsName/" /tmp/micromouse/demo_core.py

core-gui --batch /tmp/micromouse/maze.imn > /tmp/micromouse/run.log 2>&1

cat /tmp/micromouse/run.log | grep Session | cut -d' ' -f6  | cut -d'.' -f1 > /tmp/micromouse/sessionId.txt
