# Installing

### Running on OSX
Setting up the API takes approximately 30 minutes. If you don't already have [git]('https://git-scm.com/book/en/v2/Getting-Started-Installing-Git') or [homebrew]('https://brew.sh/') installed. You will want install those now.

##### Install and/or update Homebrew to the latest version using brew update
`/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"`

`brew update`

##### Install PHP 7.2 using Homebrew via brew install homebrew/core/php
`brew install homebrew/core/php`

`brew install mysql`

##### Install Valet with Composer via composer global require laravel/valet
`composer global require laravel/valet`

##### Add `~/.composer/vendor/bin` to paths
`sudo nano /etc/paths`

##### Set up valet
`valet install`

`mkdir ~/Sites`

`cd ~/Sites`

`valet park`

##### Install Repo
`git clone git@bitbucket.org:confirmed/dbp.git`

`cd dbp`

`composer install`

##### Need to set up a valid .env file rename the sample and fill out the fields.
`mv "env-sample.txt" ".env"`

##### import a copy of the live database using your preferred method: phpMyAdmin, Sequel pro, ect.


### Running on Windows
##### (Coming soon)

### Running on Linux
##### (Coming soon)