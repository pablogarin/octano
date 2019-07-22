#!/bin/bash
# Author: 		Pablo Garin
# Description: 	Bash file; configures a vagrant server for a laravel project.
# Details: 		Sets a LAMP server (Linux, Apache, MySQL & PHP), with PHP 7.2, MariaDB 5.5 and Apache 2.2,
# 				and install all the modules and set all the permissions necessary for laravel to work.
# 				It also uses the .env file to create the database user with its password, and creates the
#				virtual host for apache and a soft link to the project folder. 

# Define Variables
APPNAME=$(cat /vagrant/.env|grep APP_NAME|cut -d'=' -f 2|tr '[:upper:]' '[:lower:]'|tr ' ' '-')
SITENAME=$APPNAME.test
CONFFILE=$SITENAME.conf
DBUSER=$(cat /vagrant/.env|grep DB_USERNAME|cut -d'=' -f 2)
DBPASS=$(cat /vagrant/.env|grep DB_PASSWORD|cut -d'=' -f 2)
DBNAME=$(cat /vagrant/.env|grep DB_DATABASE|cut -d'=' -f 2)

echo "App: $APPNAME; Username: $DBUSER; Password: $DBPASS"

# Install server dependencies
echo "### INSTALL SERVER DEPENDENCIES"
echo "This might take some time..."
echo "Updating and upgrading aptitude packages references..."
sudo apt-get -y -qq update && sudo apt-get -y -qq upgrade
echo "Update done!!"
echo "Intsalling pre-dependencies..."
sudo apt-get -y -qq install apt-transport-https lsb-release ca-certificates curl software-properties-common gcc g++ make
echo "Dependencies installed!"
echo "Installing necessary PPA..."
wget -q https://packages.sury.org/php/apt.gpg -O- | sudo apt-key add -
echo "deb https://packages.sury.org/php/ stretch main" | sudo tee /etc/apt/sources.list.d/php.list
curl -sL https://deb.nodesource.com/setup_10.x | sudo bash -
echo "PPA added!"
echo "Updating aptitude..."
sudo apt-get -y -qq update
echo "Update done!!"
echo "Setting database configuration prompts..."
sudo debconf-set-selections <<< "mysql-server-5.5 mysql-server/root_password password $DBPASS"
sudo debconf-set-selections <<< "mysql-server-5.5 mysql-server/root_password_again password $DBPASS"
echo "Installing Laravel dependencies..."
sudo apt-get -y -qq install apache2 mysql-server php7.2 php7.2-bcmath php7.2-json php7.2-mbstring php7.2-xml php7.2-mysql php7.2-common php7.2-opcache php7.2-curl libapache2-mod-php7.2 nodejs vim
echo "Laravel dependencies installed!"

# Configure MySQL
echo "### CONFIGURE MYSQL"
sudo sed -i 's/bind-address/#bind-address/g' /etc/mysql/mariadb.conf.d/50-server.cnf 
echo "Binding address for server changed to accept remote connections"
sudo mysql -uroot -e "CREATE USER $DBUSER@'%' IDENTIFIED BY '$DBPASS'; CREATE DATABASE $DBNAME; USE $DBNAME; GRANT ALL PRIVILEGES ON *.* TO '$DBUSER'@'%' WITH GRANT OPTION; FLUSH PRIVILEGES;"
echo "Created database and user"
echo "Attempting database server restart..."
sudo /etc/init.d/mysql restart

# Configure Laravel Project
echo "### CONFIGURE LARAVEL PROJECT"
sudo ln -s /vagrant /var/www/
echo "Created symlink /vagrant -> /var/www/vagrant"
sudo chmod -R 777 /vagrant/storage
sudo chmod -R 777 /vagrant/bootstrap/cache
echo "Changed permissions to public folders to read and write"
echo "### NODE: " $(node --version) " - NPM:" $(npm --version)
echo "Installing node modules and running development script..."
cd /vagrant
npm config set loglevel silent
npm install &>/dev/null
npm run dev &>/dev/null
php artisan migrate
php artisan db:seed --class=MovesTableSeeder
echo "Node mudules done!"

# Configure Apache
echo "### CONFIGURE APACHE"
echo "Enabling rewrite mode"
sudo a2enmod rewrite
sudo sed -i 's/www-data/vagrant/g' /etc/apache2/envvars
TEMPLATE=$(cat /etc/apache2/sites-available/000-default.conf)
TEMPLATE="${TEMPLATE//\/var\/www\/html/\/var\/www\/vagrant\/public}"
TEMPLATE="${TEMPLATE//\#ServerName www.example.com/ServerName $SITENAME}"
echo "$TEMPLATE" > $HOME/$CONFFILE
sed "13a\
	<Directory /var/www/vagrant/public>
		AllowOverride all
	</Directory>
" $HOME/$CONFFILE > $HOME/$CONFFILE
sudo mv $HOME/$CONFFILE /etc/apache2/sites-available/$CONFFILE
echo "Created config file pointing to $SITENAME"
sudo a2ensite $CONFFILE
echo "Site enabled on apache2"
sudo systemctl reload apache2
echo "Reloading apache"

# Finish
echo "### INITIAL SETUP DONE!"