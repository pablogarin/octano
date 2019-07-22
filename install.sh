#!/bin/bash
echo "deb https://download.virtualbox.org/virtualbox/debian $(cat /etc/os-release|grep VERSION=|cut -d'=' -f2|cut -d'(' -f2|cut -d')' -f1) contrib" | sudo tee -a /etc/apt/sources.list.d/virtualbox.list
wget -q https://www.virtualbox.org/download/oracle_vbox_2016.asc -O- | sudo apt-key add -
wget -q https://www.virtualbox.org/download/oracle_vbox.asc -O- | sudo apt-key add -
sudo apt-get -y -qq update
sudo apt-get -y -qq upgrade
sudo apt-get install vagrant virtualbox-6.0