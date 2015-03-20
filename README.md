WebTest selenium, Using phpunit/selenium and php-webdriver facebook
===================================================================

##  Using

* Use php-webdriver facebook

    https://github.com/facebook/php-webdriver

* Use phpunit/selenium

    https://github.com/giorgiosironi/phpunit-selenium    


### Download and Install

Download the composer.phar

    curl -sS https://getcomposer.org/installer | php

Install the composer.phar
    
    php composer.phar install

* You can just move composer.phar to /usr/local/bin/composer (on Mac) and then use:

    composer install

* If you want to use some new packages, just add then to your composer.json ,and then use:

    composer update  

## RUN UNIT TESTS

For runnig selenium, you also should install java.

launch selenium RC server 

    java -jar selenium-server-standalone-x.xx.x.jar -role hub

If you need to use chrome browser, you have to download chromedriver, and then

    java -Dwebdriver.chrome.driver=/path/to/chromedriver -jar selenium-server-standalone-x.xx.x.jar -role hub

Register a testing into hub

    java -jar selenium-server-standalone-x.xx.x.jar -role node -hub http://localhost:4444/grid/register

Use chromedriver
    
    java -Dwebdriver.chrome.driver=/path/to/chromedriver -jar selenium-server-standalone-x.xx.x.jar -role node -hub http://localhost:4444/grid/register 

You also can :

* register many nodes into hub, just add -port port_number to the command

* default session is five, you can use -maxSession session_number to set the custom session number

To run unit tests, first launch selenium, and then just run:

    ./vendor/bin/phpunit -c ./tests

For getting the testing reports in file formats, you can also use:

    ./vendor/bin/phpunit --log-junit <file> PHPUNIT ./tests
    ./vendor/bin/phpunit --log-tap <file> PHPUNIT ./tests
    ./vendor/bin/phpunit --log-json <file> PHPUNIT ./tests
    ./vendor/bin/phpunit --testdox-html <file> PHPUNIT ./tests
    ./vendor/bin/phpunit --testdox-text <file> PHPUNIT ./tests

See the grid console on:

    http://localhost:4444/grid/console

## Description


For php-webdriver facebook cannot launch multiple browsers in a test running, so another use phpunit-selenium which supports multiple browsers running. Browsers can be announced in a public static array.

php-webdriver facebook is close to Java, .NET, Python and Ruby bindings for WebDriver.

phpunit-selenium has some problems in javascript executing for web elements.