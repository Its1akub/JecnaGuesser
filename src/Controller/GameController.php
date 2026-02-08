<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    #[Route('/game/{difficulty}', name: 'app_game')]
    public function play(string $difficulty): Response
    {
        $validModes = ['easy', 'medium', 'hard'];
        if (!in_array(strtolower($difficulty), $validModes))
        {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('game/game.html.twig', ['difficulty' => ucfirst($difficulty)]);
    }
}
