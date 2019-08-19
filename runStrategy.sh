#!/bin/sh

userId=$1
sessionId=$2

coresendmsg exec --session=$sessionId node=$(($userId*5-4)) num=250 cmd="/tmp/micromouse/$userId/demo_core.py"
coresendmsg exec --session=$sessionId node=$(($userId*5-3)) num=250 cmd="/tmp/micromouse/$userId/demo_core.py"
coresendmsg exec --session=$sessionId node=$(($userId*5-2)) num=250 cmd="/tmp/micromouse/$userId/demo_core.py"
coresendmsg exec --session=$sessionId node=$(($userId*5-1)) num=250 cmd="/tmp/micromouse/$userId/demo_core.py"
