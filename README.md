### Instalation


## Dependecies

This project uses **Vagrant** with **VirtualBox** to setup a virtual environment to develop and test it.

- **Vagrant**: https://www.vagrantup.com/downloads.html
- **VirtualBox**: https://www.virtualbox.org/wiki/Downloads

After installing both, you just have to run vagrant up and the system will install a Debian virtual machine and the it will proceed to install all its dependencies and configure the server for the web page to work.

```bash
vagrant up
```

This process might take a couple of minutes, so be patient.

## Hosts File

After installing, you have to add a line to your hosts file to map the testing URL to the IP of the virtual machine:

```bash
echo "192.168.10.10   octano.test" | sudo tee -a /etc/hosts
```

## IP Address

The server is configred to run at IP 192.168.10.10.

## Bash Script

For an expedient installation, a bash file named **install.sh** has been included in the repository to install all the dependencies and initialize the virtual machine. However, this file will only work on Debian like systems.

```bash
bash install.sh
```