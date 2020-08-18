# ip_backend_test #

### Shout Api for fetching quotes based on Authors. ###

The relevant classes for the Api are:

* App\Controller\QuoteController
* App\Service\QuoteService
* App\Command\ShoutCommand

The Api can be called in the following manner /shout/{author} or /shout/{author}?limit=#. The limit parameter is optional.
If the author was successfully found, a list of quotes will be returned based on the limit. If no limit is given, all quotes are returned.

There is also the ability to fetch quotes over the console using the ShoutCommand. 
Execute `bin/console app:shout {author} [limit]` from the root directory to run the command.

Tests can be found under the symfony/tests directory and can be executed using `php bin/phpunit` from the root directory:

* App\Tests\Controller\QuoteControllerTest
* App\Tests\Service\QuoteServiceTest



 
