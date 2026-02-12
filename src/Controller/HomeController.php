<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        if ($request->isMethod('POST'))
        {
            $difficulty = $request->request->get('difficulty');
            if ($difficulty)
            {
                return $this->redirectToRoute('app_game', ['difficulty' => $difficulty]);
            }
        }

        return $this->render('home/index.html.twig');
    }
}
