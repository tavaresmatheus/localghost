<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'api_test')]
    public function test()
    {
        return $this->json(['teste' => 'Esse é um teste']);
    }
}