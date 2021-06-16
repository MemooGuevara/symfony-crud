<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function index(): Response
    {
        return $this->render('welcome/index.html.twig');
    }
}
