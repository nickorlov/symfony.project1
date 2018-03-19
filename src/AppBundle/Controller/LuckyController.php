<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Psr\Log\LoggerInterface;

class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number/{max}")
     */
    public function numberAction($max, LoggerInterface $logger)
    {
        $logger->info('We are logging!');
        $number = mt_rand(0, $max);

        //throw $this->createNotFoundException();

        return $this->render('lucky/number.html.twig', array(
            'number' => $number
        ));
        //return $this->redirect('http://google.com/');
    }
}