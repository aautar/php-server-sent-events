#!/usr/bin/env bash

SITE_DOMAIN="localdev.com"
WILDCARD_CERT="*.$SITE_DOMAIN"
CERT_FOLDER="_.$SITE_DOMAIN";
CA_CERT="/vagrant/localdev/secrets/minica.pem"
CA_KEY="/vagrant/localdev/secrets/minica-key.pem"

sudo apt-get -y update

apt-get install -y apache2
if ! [ -L /var/www/html ]; then
  rm -rf /var/www/html
  ln -fs /vagrant/public /var/www/html
fi

# Install PHP
sudo apt-get -y install php8.2 php8.2-fpm php8.2-curl php8.2-mbstring

# HTTP2 support
sudo a2dismod mpm_prefork
sudo a2enmod mpm_event
sudo a2enmod http2
sudo cp /vagrant/localdev/http2.conf /etc/apache2/conf-available/http2.conf
sudo a2enconf http2
sudo a2enmod ssl

# Rewrite support
sudo a2enmod rewrite

# Apache PHP support via FPM
sudo a2enmod proxy_fcgi setenvif
sudo a2enconf php8.2-fpm
sudo cp /vagrant/localdev/vagrant.apache.default.vhost /etc/apache2/sites-available/000-default.conf

sudo service apache2 reload
sudo service apache2 restart

# Setup minica
sudo apt-get -y install git
sudo apt-get -y install golang-go

sudo mkdir /certs
sudo mkdir /tools
cd /tools
git clone https://github.com/jsha/minica.git
cd /tools/minica
go env -w GO111MODULE=auto
go build -buildvcs=false

# Generate wildcard cert
./minica --ca-cert "$CA_CERT" --ca-key "$CA_KEY" --domains "$WILDCARD_CERT"
cp -R "/tools/minica/$CERT_FOLDER" "/certs/$CERT_FOLDER"
