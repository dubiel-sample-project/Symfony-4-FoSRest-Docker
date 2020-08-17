<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Service\QuoteService;

/**
  * Class QuoteController
  * @package App\Controller
  */
class QuoteController extends AbstractFOSRestController {
	
    /**
     * @Rest\Get("/{author}")
	 * @param string $author
     * @param Request $request
	 * @param QuoteService $quoteService
     * @return View
     */
    public function getQuote(string $author, Request $request, QuoteService $quoteService): View
    {
		$quotes = $quoteService->fetchQuote($author, $request->query->get('limit'));
        return View::create($quotes, Response::HTTP_OK);
    }	
}