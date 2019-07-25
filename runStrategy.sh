#!/bin/sh

sessionId=$1

coresendmsg exec --session=$sessionId node=1 num=5 cmd="/tmp/micromouse/demo_core.py"
coresendmsg exec --session=$sessionId node=2 num=5 cmd="/tmp/micromouse/demo_core.py"
coresendmsg exec --session=$sessionId node=3 num=5 cmd="/tmp/micromouse/demo_core.py"
coresendmsg exec --session=$sessionId node=4 num=5 cmd="/tmp/micromouse/demo_core.py"
