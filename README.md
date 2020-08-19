# ip_backend_test #

### Shout Api for fetching quotes based on Authors. ###

## Overview

The relevant classes for the api are:

* App\Controller\QuoteController (/src/Controller)
* App\Service\QuoteService (/src/Service)
* App\Command\ShoutCommand (/src/Command)

The api can be called in the following manner **/shout/{author}** or **/shout/{author}?limit=#**. The limit parameter is optional.
If the author was successfully found, a list of quotes will be returned based on the limit. If no limit is given, all quotes are returned.

There is also the ability to fetch quotes over the console using the ShoutCommand. 
Execute `php bin/console app:shout {author} [limit]` from the root directory (/var/www/symfony) to run the command.

Unit and functional tests can be found under the tests directory and can be executed using `php bin/phpunit` from the root directory.
PHPUnit may need to installed before the tests are run. Simply run the command `php bin/phpunit` to install it.

Test classes can be found here:

* App\Tests\Controller\QuoteControllerTest (/tests/Controller)
* App\Tests\Service\QuoteServiceTest (/tests/Service)

## Installation

The api architecture consists of three services (nginx, php and redis) all managed by Docker. 

1. After checking out the repository, run `docker-compose up -d --build` to create the service containers.
2. After successfully building, log into the php container using `docker exec -it {php_container_name} bash`
3. From the root directory (/var/www/symfony) run `composer install` to install packages from the composer.lock file.
4. After installation, the api should be available from port 8001 either at localhost or the ip address of the docker machine. Run `docker-machine ip` to obtain the ip address.

After logging into the application, you can also run either the command line interface or the tests as described above.
The redis cache is also available using the command `docker exec -it {redis_container_name} redis-cli`

 
