#!/bin/sh

codePath=/tmp/micromouse/$1

clsName=`cat $codePath/strategy_test.py | grep class | cut -d' ' -f2 | cut -d'(' -f1`

sed -i 's/strategy_multidfs/strategy_test/' $codePath/demo_core.py

sed -i "s/StrategyMultiDFS/$clsName/" $codePath/demo_core.py

sed -i "s/maze.txt/$1\/maze.txt/" $codePath/demo_core.py

sed -i "s/sid/$1/" $codePath/demo_core.py

sed -i "s/broadcastAddr = .*/broadcastAddr = \'10.0.$(($1-1)).255\'/" $codePath/strategy_test.py

#controlNetIndex=$(($1 % 256))

#sed -i "s/10.0.0/10.0.$controlNetIndex/" $codePath/maze.imn

#core-gui --batch $codePath/maze.imn -p $1 > $codePath/run.log 2>&1

#cat $codePath/run.log | grep Session | cut -d' ' -f6  | cut -d'.' -f1 > $codePath/session$1.txt
