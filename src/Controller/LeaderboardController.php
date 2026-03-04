<?php

namespace App\Controller;

use App\Entity\GameScore;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LeaderboardController extends AbstractController
{
    #[Route('/leaderboard', name: 'app_leaderboard')]
    public function index(EntityManagerInterface $em): Response
    {
        $scores = $em->getRepository(GameScore::class)->findBy(
            [],
            ['Score' => 'DESC', 'time' => 'ASC'],
            25
        );

        return $this->render('leaderboard/leaderboard.html.twig', [
            'scores' => $scores,
        ]);
    }
}
