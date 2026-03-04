<?php

namespace App\Controller;

use App\Entity\GameScore;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LeaderboardController extends AbstractController
{
    #[Route('/leaderboard/{difficulty}', name: 'app_leaderboard')]
    public function index(string $difficulty, Request $request, EntityManagerInterface $em): Response
    {
        $validModes = ['easy', 'medium', 'hard'];
        if (!in_array(strtolower($difficulty), $validModes))
        {
            return $this->redirectToRoute('app_home');
        }

        $criteria = [];
        $criteria['difficulty'] = $difficulty;

        $scores = $em->getRepository(GameScore::class)->findBy(
            $criteria,
            ['Score' => 'DESC', 'time' => 'ASC']
        );

        return $this->render('leaderboard/leaderboard.html.twig', [
            'scores' => $scores,
            'difficulty' => $difficulty,
        ]);
    }
}
