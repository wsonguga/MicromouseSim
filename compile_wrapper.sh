gcc wrapper.c -o php_root
sudo chown root php_root
sudo chmod u=rwx,go=xr,+s php_root
sudo cp php_root /tmp/micromouse/

