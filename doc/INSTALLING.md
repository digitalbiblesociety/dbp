# Installing

#### Dependencies

- PHP >= 7.1
- PECL
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Composer

### Running on OSX (Local)
Setting up the API takes approximately 30 minutes. If you don't already have [git]('https://git-scm.com/book/en/v2/Getting-Started-Installing-Git') or [homebrew]('https://brew.sh/') installed. You will want to install those now.

##### Install and/or update Homebrew to the latest version using brew update
`/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"`

`brew update`

##### Install PHP 7.2 using Homebrew via brew install homebrew/core/php
`brew install homebrew/core/php && brew install composer && brew install mysql`

##### Install Valet with Composer via composer global require laravel/valet
`composer global require laravel/valet`

##### Add `~/.composer/vendor/bin` to paths and restart terminal
`sudo nano /etc/paths`

##### Set up valet
If this is your first time working with Valet, set it up following these instructions: https://laravel.com/docs/valet

Then ensure you've set your sites directory up something like this:

`valet install && mkdir ~/Sites && cd ~/Sites && valet park`

##### Install Repo
` git clone https://github.com/digitalbiblesociety/dbp.git `

cd down into the directory

`cd dbp`

and Run composer install

`composer install`

##### Set up a valid local .env file
`cp .env.example .env`

##### Generate a new application key:

`php artisan key:generate`

##### Link the API subdomain
`valet link api.dbp`

##### Secure the Valet domains
`valet secure`

##### Install Node and run npm install
`brew install node && npm install`

##### Import a copy of the live database using your preferred method: phpMyAdmin, Sequel pro, etc.

### Running on Windows
##### (Coming soon)

#### Installing on Ubuntu 18

##### Install Packages
```bash
apt-get install software-properties-common
add-apt-repository -y 'ppa:ondrej/php'
add-apt-repository -y 'ppa:ondrej/nginx'
apt-get update
sudo apt-get -y install gcc npm curl gzip git tar software-properties-common nginx composer
sudo apt-get -y install php7.2-fpm php7.2-xml php7.2-bz2 php7.2-zip php7.2-mysql php7.2-intl php7.2-gd php7.2-curl php7.2-soap php7.2-mbstring php7.2-memcached
```
##### Configure PHP
```bash
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 20M/g' /etc/php/7.2/fpm/php.ini
sed -i 's/max_execution_time = 30/max_execution_time = 300/g' /etc/php/7.2/fpm/php.ini
sed -i 's/max_input_time = 60/max_input_time = 300/g' /etc/php/7.2/fpm/php.ini
sed -i 's/post_max_size = 8M/post_max_size = 28M/g' /etc/php/7.2/fpm/php.ini
sed -i 's/memory_limit = 128M/memory_limit = 512M/g' /etc/php/7.2/fpm/php.ini
service php7.2-fpm restart
```
##### Create a Site
```bash
git clone https://github.com/AfzalH/lara-server.git && cd lara-server
echo 'Enter Site Domain [dbp4.org]:' && read site_com
cp nginx/srizon.com /etc/nginx/sites-available/$site_com
sed -i "s/srizon.com/${site_com}/g" /etc/nginx/sites-available/$site_com
mkdir /var/www/$site_com
mkdir /var/www/$site_com/public
touch /var/www/$site_com/public/index.php
ln -s /etc/nginx/sites-available/$site_com /etc/nginx/sites-enabled/$site_com
service nginx reload
```
##### Edit to test
```bash
nano /var/www/$site_com/public/index.php
```
##### Remove test
```bash
rm -rf /var/www/$site_com/public
```
##### Clone Repo
```bash
git clone https://github.com/digitalbiblesociety/dbp.git /var/www/$site_com
cd /var/www/$site_com
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
npm install
```

#### Permissions
```bash
sudo find $site_com/ -type d -exec chmod 755 {} ;
sudo find $site_com/ -type d -exec chmod ug+s {} ;
sudo find $site_com/ -type f -exec chmod 644 {} ;
sudo chown -R www-data:www-data $site_com
sudo chmod -R 755 $site_com/storage
sudo chmod -R 755 $site_com/bootstrap/cache/
```