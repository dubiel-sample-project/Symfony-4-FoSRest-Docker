<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\QuoteService;

class QuoteServiceTest extends KernelTestCase
{
	private QuoteService $quoteService;
	
	public function setUp()
	{
		self::bootKernel();
		//var_dump(self::$kernel->getProjectdir());
		$this->quoteService = self::$container->get('App\Service\QuoteService');
		//$this->quoteService = new QuoteService(self::$kernel->getProjectdir(), );
	}
	
	/**
	 * @test
	 */
    public function normalizeQuoteWithTrailingPeriod()
    {
		$quote = 'If you do what you’ve always done, you’ll get what you’ve always gotten.';
		$expected = 'If you do what you’ve always done, you’ll get what you’ve always gotten';
		
		$actual = $this->quoteService->normalizeQuote($quote);

        $this->assertSame($expected, $actual);
	}
	
	/**
	 * @test
	 */
	public function normalizeQuoteWithoutTrailingPeriod()
    {
		$quote = 'Nothing is impossible, the word itself says, “I’m possible!”';
		$expected = 'Nothing is impossible, the word itself says, “I’m possible!”';
		
		$actual = $this->quoteService->normalizeQuote($quote);

        $this->assertSame($expected, $actual);
	}
	
	/**
	 * @test
	 */
	public function transformQuote()
    {
		$quote = 'You can’t use up creativity.  The more you use, the more you have.';
		$expected = 'YOU CAN’T USE UP CREATIVITY.  THE MORE YOU USE, THE MORE YOU HAVE.!';
		
		$actual = $this->quoteService->transformQuote($quote);

        $this->assertSame($expected, $actual);
	}
	
	/**
	 * @test
	 */
	public function normalizeTransformQuote()
    {
		$quote = 'You can’t fall if you don’t climb.  But there’s no joy in living your whole life on the ground.';
		$expected = 'YOU CAN’T FALL IF YOU DON’T CLIMB. BUT THERE’S NO JOY IN LIVING YOUR WHOLE LIFE ON THE GROUND!';
		
		$actual = $this->quoteService->normalizeQuote($quote);
		$actual = $this->quoteService->transformQuote($actual);

        $this->assertSame($expected, $actual);
	}
	
	/**
	 * @test
     * @dataProvider quotesProvider
     */
	public function fetchQuoteSuccess($author, $expected)
	{
		$actual = $this->quoteService->fetchQuote($author);
		$this->assertSame($expected, $actual);
	}
	
	/**
	 * @test
     * @expectedException App\Exception\LimitOutOfBoundsException
     */
	public function fetchQuoteLimitOutOfBoundsExceptionInteger()
	{
		$actual = $this->quoteService->fetchQuote('unknown', 11);
		$this->assertSame($expected, $actual);
	}
	
	/**
	 * @test
     * @expectedException App\Exception\LimitOutOfBoundsException
     */
	public function fetchQuoteLimitOutOfBoundsExceptionString()
	{
		$actual = $this->quoteService->fetchQuote('unknown', 'abc');
		$this->assertSame($expected, $actual);
	}
	
	/**
	 * @test
     * @expectedException App\Exception\AuthorNotFoundException
     */
	public function fetchQuoteAuthorNotFoundException()
	{
		$actual = $this->quoteService->fetchQuote('mickey-mouse');
		$this->assertSame($expected, $actual);
	}
	
	/**
	 * @test
     * @dataProvider authorProvider
     */
	public function normalizeAuthor($author, $expected)
	{
		$actual = $this->quoteService->normalizeAuthor($author);	
		$this->assertSame($expected, $actual);
	}
	
	public function authorProvider()
    {
		yield ['Unknown', 'unknown'];
        yield ['Steve Jobs', 'steve-jobs'];
		yield ['–Audrey Hepburn', 'audrey-hepburn'];
		yield ['Sir Claus Moser', 'sir-claus-moser'];
		yield ['Booker T. Washington', 'booker-t-washington'];
		yield ['Martin Luther King Jr.', 'martin-luther-king-jr'];
    }
	
	public function quotesProvider()
	{
		yield ['marie-curie', 
			['WE MUST BELIEVE THAT WE ARE GIFTED FOR SOMETHING, AND THAT THIS THING, AT WHATEVER COST, MUST BE ATTAINED!']];
		yield ['leonardo-da-vinci', 
			['I HAVE BEEN IMPRESSED WITH THE URGENCY OF DOING. KNOWING IS NOT ENOUGH; WE MUST APPLY. BEING WILLING IS NOT ENOUGH; WE MUST DO!']];
		yield ['steve-jobs', 
			['YOUR TIME IS LIMITED, SO DON’T WASTE IT LIVING SOMEONE ELSE’S LIFE!',
			'THE ONLY WAY TO DO GREAT WORK IS TO LOVE WHAT YOU DO!']];
		yield ['maya-angelou', 
			['I’VE LEARNED THAT PEOPLE WILL FORGET WHAT YOU SAID, PEOPLE WILL FORGET WHAT YOU DID, BUT PEOPLE WILL NEVER FORGET HOW YOU MADE THEM FEEL!',
			'LIFE IS NOT MEASURED BY THE NUMBER OF BREATHS WE TAKE, BUT BY THE MOMENTS THAT TAKE OUR BREATH AWAY!',
			'YOU CAN’T USE UP CREATIVITY. THE MORE YOU USE, THE MORE YOU HAVE!']];			
	}
}
