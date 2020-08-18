# ip_backend_test #

### Shout Api for fetching quotes based on Authors. ###

The relevant classes for the Api are:

* App\Controller\QuoteController
* App\Service\QuoteService
* App\Command\ShoutCommand

The Api can be called in the following manner **/shout/{author}** or **/shout/{author}?limit=#**. The limit parameter is optional.
If the author was successfully found, a list of quotes will be returned based on the limit. If no limit is given, all quotes are returned.

There is also the ability to fetch quotes over the console using the ShoutCommand. 
Execute `bin/console app:shout {author} [limit]` from the root directory to run the command.

Unit and functional tests can be found under the symfony/tests directory and can be executed using `php bin/phpunit` from the root directory:

* App\Tests\Controller\QuoteControllerTest
* App\Tests\Service\QuoteServiceTest

The Api architecture consists of three services (nginx, php and redis) all managed by Docker. 
After checking out the repository, run `docker-compose up -d --build` to create the service containers.
After successfully building, the api should be available from port 8001.

Using the command `docker exec -it {php_container_name} exec bash` one can log into the application and run either the command line interface or the tests as described above.
The redis cache is also available using the command `docker exec -it {redis_container_name} redis-cli`

 
