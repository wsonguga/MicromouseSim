kill $(ps aux | grep python | head -n 1 | awk '{print $2}')
