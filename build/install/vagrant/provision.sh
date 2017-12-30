#!/bin/bash

# check that we are root
if [[ $EUID -ne 0 ]]; then
echo "This script must be run as root"
exit 1
fi

# setup if running as script
cd ~/
set -ex
CURRENT_DIR=$1
DOMAIN=$2
ADMIN_EMAIL=$3
MYSQL_ROOT_PASSWORD="rota"

# install prerequisites
add-apt-repository ppa:certbot/certbot

apt-get update -qq
apt-get upgrade -qy
apt-get install -q -y nginx php-mysql php-fpm python-certbot-nginx php-curl php-xml php-mbstring zip unzip

sudo phpenmod pdo_mysql

curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

hostnamectl set-hostname rota
echo "127.0.1.1 rota rota" >> /etc/hosts

# firewall
sudo ufw allow 'Nginx Full'
sudo ufw delete allow 'Nginx HTTP'

# secure php
echo -e "cgi.fix_pathinfo=0" > /etc/php/7.0/fpm/php.ini

service php7.0-fpm restart

# mysql
sudo debconf-set-selections <<< 'mariadb-server mariadb-server/root_password password $MYSQL_ROOT_PASSWORD'
sudo debconf-set-selections <<< 'mariadb-server mariadb-server/root_password_again password $MYSQL_ROOT_PASSWORD'
sudo apt-get -y install mariadb-server

mysql_secure_installation <<EOF

y
$MYSQL_ROOT_PASSWORD
$MYSQL_ROOT_PASSWORD
y
y
y
y
EOF

sudo mysql -uroot -e "CREATE DATABASE rota;"
sudo mysql -uroot -e "CREATE USER 'rota_user'@'localhost' IDENTIFIED BY 'rota_password';"
sudo mysql -uroot -e "GRANT ALL ON rota.* TO rota_user@localhost; FLUSH PRIVILEGES;"

# setup nginx
cat > /etc/nginx/sites-available/rota <<EOF
server {
    listen 80;
    listen [::]:80;

    root $CURRENT_DIR/public;
    index index.php index.html index.htm;

    server_name $DOMAIN;

    location / {
        try_files \$uri /index.php\$is_args\$args;
    }

    location ~ \.php {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param SCRIPT_NAME \$fastcgi_script_name;
        fastcgi_index index.php;

        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

rm /etc/nginx/sites-enabled/default
ln -s /etc/nginx/sites-{available,enabled}/rota

# test nginx config
sudo nginx -t

service nginx reload

# Certbox SSL certificate
# sudo certbot --nginx -d $DOMAIN --email $ADMIN_EMAIL --agree-tos

# cron
# crontab -l | { cat; echo "30 6 * * 0 $CURRENT_DIR/cron/daily.sh https://$DOMAIN/job/daily/$TOKEN"; } | crontab -

# log out of root
exit
