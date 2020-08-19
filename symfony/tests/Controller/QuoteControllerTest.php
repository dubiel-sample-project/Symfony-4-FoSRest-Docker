<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
  * Class QuoteControllerTest
  * @package App\Tests\Controller
  * @author Matt Dubiel <munich55555@gmail.com>
  */
class QuoteControllerTest extends WebTestCase
{
    /**
     * @test
     * @dataProvider validPathProvider
     */
    public function fetchQuoteIsSuccessful($path)
    {
        $client = self::createClient();
        $client->request('GET', $path);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @test
     * @dataProvider invalidPathProvider
     */
    public function fetchQuoteIsUnsuccessful($path)
    {
        $client = self::createClient();
        $client->request('GET', $path);

        $this->assertFalse($client->getResponse()->isSuccessful());
    }
    
    /**
     * @test
     */
    public function fetchQuoteLimitOutOfBounds()
    {
        $client = self::createClient();
        $client->request('GET', '/shout/steve-jobs?limit=100');
        
        $content = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertEquals(400, $content['code']);
        $this->assertEquals('Limit must be 10 or less.', $content['message']);
    }
    
    /**
     * @test
     */
    public function fetchQuoteAuthorNotFound()
    {
        $client = self::createClient();
        $client->request('GET', '/shout/donald-duck');
        
        $content = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertEquals(404, $content['code']);
        $this->assertEquals('Author donald-duck not found.', $content['message']);
    }
    
    public function validPathProvider()
    {
        yield ['/shout/steve-jobs'];
        yield ['/shout/helen-keller?limit=1'];
        yield ['/shout/ancient-indian-proverb'];
        yield ['/shout/w-clement-stone'];
        yield ['/shout/joshua-j-marine?limit=5'];
    }
    
    public function invalidPathProvider()
    {
        yield ['/shout/mickey-mouse'];
        yield ['/shout/helen-keller?limit=11'];
        yield ['/shout/-ancient-indian-proverb'];
        yield ['/shout/joshua-j.-marine?limit=5'];
    }
}