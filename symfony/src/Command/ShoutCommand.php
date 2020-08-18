<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use App\Service\QuoteService;
use App\Exception as AppException;

/**
  * Class ShoutCommand
  * @package App\Command
  */
class ShoutCommand extends Command
{
    /**
      * @var string Time period to cache items
      */
    protected static $defaultName = 'app:shout';

    /**
      * @var string Time period to cache items
      */
    private QuoteService $quoteService;
    
    /**
     * @param QuoteService $quoteService
     * @return void
     */
    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
        
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
         $this->addArgument('author', InputArgument::REQUIRED, 'author');
         $this->addArgument('limit', InputArgument::OPTIONAL, 'limit');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $author = $input->getArgument('author');
        $limit = $input->getArgument('limit');
        
        try 
        {
            $quotes = $this->quoteService->fetchQuote($author, $limit);
            
            $output->writeln(['Quotes', '============']);
            foreach($quotes as $quote)
            {
                $output->writeln($quote);
            }
        } 
        catch(AppException\LimitOutOfBoundsException | AppException\AuthorNotFoundException $e)
        {
            $output->writeln(['Something went wrong!', '============']);
            $output->writeln($e->getMessage());
        }
        
        return 0;
    }
}