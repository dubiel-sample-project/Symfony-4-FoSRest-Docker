<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuoteControllerTest extends WebTestCase
{
    /**
	 * @test
     * @dataProvider validPathProvider
     */
    public function quoteIsSuccessful($path)
    {
        $client = self::createClient();
        $client->request('GET', $path);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

	/**
	 * @test
     * @dataProvider invalidPathProvider
     */
    public function quoteIsNotSuccessful($path)
    {
        $client = self::createClient();
        $client->request('GET', $path);

        $this->assertFalse($client->getResponse()->isSuccessful());
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