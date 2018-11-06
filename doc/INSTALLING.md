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

### Running on OSX
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
`valet install && mkdir ~/Sites && cd ~/Sites && valet park`

##### Install Repo
`git clone git@github.com:digitalbiblesociety/dbp.git && cd dbp && cp .env.example .env && composer install`

##### Install Node and run npm install
`brew install node && npm install`

##### import a copy of the live database using your preferred method: phpMyAdmin, Sequel pro, ect.

### Running on Windows
##### (Coming soon)

### Running on Linux
##### (Coming soon)
