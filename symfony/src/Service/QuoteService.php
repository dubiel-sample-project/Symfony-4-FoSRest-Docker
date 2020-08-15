<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface;

use App\Exception as AppException;
use DateInterval;

class QuoteService
{
	private array $quotes = [];
	private string $projectDir;
	private AdapterInterface $cache;
	
	public function __construct(string $projectDir, AdapterInterface $cache)
	{
		$this->projectDir = $projectDir;
		$this->cache = $cache;
		
		$quotes = json_decode(file_get_contents($this->projectDir.'/resources/quotes.json'), true);
		
		foreach($quotes['quotes'] as $quote) {
			$this->quotes[strtolower(str_replace(['-', ' '], ['', '-'], $quote['author']))][] = $quote['quote'];
		}
	}
	
	public function fetchQuote(string $author, $limit = 10) : array
	{
		if($limit > 10)
		{
			throw new AppException\LimitOutOfBoundsException('Limit must be 10 or less.');
		}
		
		$item = $this->cache->getItem($author);
		if (!$item->isHit()) {
			if(!isset($this->quotes[$author]))
			{
				throw new AppException\NotFoundException('Author not found.');
			}
			var_dump(__LINE__);
			var_dump(get_class($this->cache));
            $item->set(array_slice($this->quotes[$author], 0, $limit));
            $item->expiresAfter(new DateInterval('PT300S'));
            $this->cache->save($item);
        }
		
		return $item->get();
	}
}