<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface;

use App\Exception as AppException;
use DateInterval;

/**
  * Class QuoteService
  * @package App\Service
  */
class QuoteService
{
    /**
      * @var string Time period to cache items
      */
    const CACHE_PERIOD = 'PT300S';
    
    /**
      * @var array Contains associative array of authors and their quotes
      */
    private array $quotes = [];
    
    /**
      * @var string Root directory of project
      */
    private string $projectDir;
    
    /**
      * @var AdapterInterface Interface to caching implementation
      */
    private AdapterInterface $cache;
    
    /**
     * @param string $projectDir
     * @param AdapterInterface $cache
     * @return void
     */
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
    
    /**
     * @param string $author
     * @param mixed $limit
     * @return array
     */
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
    
    /**
     * @param string $quote
     * @return string
     */
    public function transformQuote(string $quote) : string
    {
        return strtoupper($quote).'!';
    }
    
    /**
     * @param string $author
     * @return string
     */
    public function normalizeAuthor(string $author) : string
    {
        $author = preg_replace('/[^\w\s]+/', '', trim($author));
        return strtolower(str_replace(' ', '-', $author));
    }
    
    /**
     * @param string $author
     * @return string
     */
    public function normalizeQuote(string $quote) : string
    {
        return preg_replace(['/\s{2}/', '/[\.!]$/'], [' ', ''], trim($quote));
    }
    
    /**
     * @param mixed $limit
     * @return void
     * @throws App\Exception\LimitOutOfBoundsException
     */
    private function validateLimit($limit) : void
    {
        if(($limit && !is_numeric($limit)) || $limit > 10)
        {
            throw new AppException\LimitOutOfBoundsException('Limit must be 10 or less.');
        }
    }
    
    /**
     * @param string $author
     * @return void
     * @throws App\Exception\AuthorNotFoundException
     */
    private function validateAuthor(string $author) : void
    {
        if(!isset($this->quotes[$author]))
        {
            throw new AppException\AuthorNotFoundException("Author $author not found.");
        }
    }
}