<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class FinishController extends AbstractController
{
    #[Route('/game/finish', name: 'game_finish')]
    public function index(Request $request, SessionInterface $session): Response
    {
        $score = $session->get('total_score', 0);
        $difficulty = $session->get('difficulty', 'N/A');
        $time = $session->get('total_time', 'N/A');

        return $this->render('game/finish.html.twig', [
            'score' => $score,
            'time' => $time,
            'difficulty' => $difficulty
        ]);
    }
}
