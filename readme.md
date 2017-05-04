MailMan

Requirements:

* PHP 7.0
* php7.0-curl
* PHP 7.0 Zip Extension
* PHPUnit 6.1: https://phpunit.de/manual/current/en/installation.html
* Redis (recommended): https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-redis-on-ubuntu-16-04
* Supervisor: https://laravel.com/docs/5.4/queues#supervisor-configuration
* Email Driver: I use Sparkpost and Mailgun, you can use any of L5.4's supported email drivers however. Please don't be a dummy and send mass mail from SMTP.

Configuration
* Add artisan schedule:run to root crontab
* uncomment extension: php_curl.dll in php.ini
* Create a supervisor file to run laravel queue - https://laravel.com/docs/5.4/queues#supervisor-configuration
* composer install

NPM Configuration - You need to change this by hand on each installation. I have included dev.package.json and prod.package.json to display the necessary changes for NPM workers. Make sure to update only the "scripts" block, and keep any packages that are required in package.json


* npm install (must run with --no-bin-links command on Homestead)
* bower install
	* On homestead this appears to require changing ownership to vagrant for directories ~/.cache and ~/.config
* npm run dev
* Set up webhooks for email provider if desired.

Changelog

1.1
* Added MG email validation for imported entries.
