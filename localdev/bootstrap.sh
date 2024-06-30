#!/usr/bin/env bash

sudo apt-get -y update

apt-get install -y apache2
if ! [ -L /var/www/html ]; then
  rm -rf /var/www/html
  ln -fs /vagrant/public /var/www/html
fi

sudo apt-get -y install php8.2 php8.2-fpm php8.2-cgi php8.2-mysql libapache2-mod-php8.2 php8.2-curl php8.2-mbstring

sudo a2enmod rewrite
sudo a2enmod php8.2
sudo a2enconf php8.2-cgi
sudo a2enconf php8.2-fpm
sudo cp /vagrant/localdev/vagrant.apache.default.vhost /etc/apache2/sites-available/000-default.conf
sudo service apache2 reload
sudo service apache2 restart
