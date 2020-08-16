<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface;

use App\Exception as AppException;
use DateInterval;

final class QuoteService
{
	const CACHE_PERIOD = 'PT300S';
	
	private array $quotes = [];
	private string $projectDir;
	private AdapterInterface $cache;
	
	public function __construct(string $projectDir, AdapterInterface $cache)
	{
		$this->projectDir = $projectDir;
		$this->cache = $cache;
		
		$quotes = json_decode(file_get_contents($this->projectDir.'/resources/quotes.json'), true);
		foreach($quotes['quotes'] as $quote) 
		{
			$this->quotes[$this->normalizeAuthor($quote['author'])][] = 
				$this->transformQuote($this->normalizeQuote($quote['quote']));
		}
	}
	
	public function fetchQuote(string $author, $limit = 10) : array
	{
		$this->validateLimit($limit);
		
		$item = $this->cache->getItem($author);
		if (!$item->isHit())
		{
			$this->validateAuthor($author);
            $item->set($this->quotes[$author]);
            $item->expiresAfter(new DateInterval(self::CACHE_PERIOD));
            $this->cache->save($item);
        }
		
		return array_slice($item->get(), 0, $limit);
	}
	
	public function transformQuote(string $quote) : string
	{
		return strtoupper($quote).'!';
	}
	
	public function normalizeAuthor(string $author) : string
	{
		$author = trim(preg_replace('/[^\w\s]+/', '', $author));
		return strtolower(str_replace(' ', '-', $author));
	}
	
	public function normalizeQuote(string $quote) : string
	{
		return trim(preg_replace(['/\s{2}/', '/[\.!]$/'], [' ', ''], $quote));
	}
	
	private function validateLimit($limit) : void
	{
		if(($limit && !is_numeric($limit)) || $limit > 10)
		{
			throw new AppException\LimitOutOfBoundsException('Limit must be 10 or less.');
		}
	}
	
	private function validateAuthor(string $author) : void
	{
		if(!isset($this->quotes[$author]))
		{
			throw new AppException\AuthorNotFoundException("Author $author not found.");
		}
	}
}