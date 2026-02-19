<?php

namespace App\Controller;

use App\Entity\GameLocation;
use App\Entity\GameScore;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    #[Route('/game/{difficulty}', name: 'app_game', methods: ['GET'])]
    public function play(
        string $difficulty,
        SessionInterface $session,
        EntityManagerInterface $em
    ): Response {
        $validModes = ['easy', 'medium', 'hard'];
        if (!in_array(strtolower($difficulty), $validModes))
        {
            return $this->redirectToRoute('app_home');
        }

        $session->set('game_start_time', time());
        $session->set('difficulty', strtolower($difficulty));
        $session->set('current_round', 0);
        $session->set('total_score', 0);

        $locationsForDifficulty = $em->getRepository(GameLocation::class)
            ->createQueryBuilder('g')
            ->where('g.difficulty = :diff')
            ->setParameter('diff', strtolower($difficulty))
            ->getQuery()
            ->getResult();

        shuffle($locationsForDifficulty);
        $gameLocations = array_slice($locationsForDifficulty, 0, 5);

        // Uložíme ID lokací do session
        $session->set('game_locations', array_map(fn($loc) => $loc->getId(), $gameLocations));



        return $this->render('game/game.html.twig', ['difficulty' => ucfirst($difficulty), 'locations' => $gameLocations]);
    }

    #[Route('/game/guess', name: 'game_guess', methods: ['POST'])]
    public function guess(Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $locationIds = $session->get('game_locations', []);
        $currentRound = $session->get('current_round', 0);

        if (!isset($locationIds[$currentRound])) {
            return new Response('Game finished', 400);
        }

        $location = $em->getRepository(GameLocation::class)->find($locationIds[$currentRound]);
        if (!$location) {
            return new Response('Invalid location', 400);
        }

        // Odhadnutý X/Y od frontendu
        $xGuess = (float)$request->request->get('x');
        $yGuess = (float)$request->request->get('y');


        $distance = sqrt(pow($xGuess - $location->getX(), 2) + pow($yGuess - $location->getY(), 2));

        // Max 5000 bodů, ztráta 2 bodů za 1 jednotku vzdálenosti
        $points = max(0, 5000 - ($distance * 2));
        $points = (int)min(5000, $points);

        $totalScore = $session->get('total_score', 0) + $points;
        $session->set('total_score', $totalScore);

        $session->set('current_round', $currentRound + 1);

        // Konec hry po 5 kolech
        if ($currentRound + 1 >= count($locationIds)) {
            return $this->redirectToRoute('game_finish');
        }

        return $this->json([
            'points' => $points,
            'total_score' => $totalScore,
            'next_round' => $currentRound + 1
        ]);
    }


    #[Route('/game/finish', name: 'game_finish')]
    public function finish(): Response
    {
        return $this->render('game/enter_name.html.twig');
    }

    #[Route('/game/save', name: 'game_save', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $name = trim($request->request->get('playerName'));
        $score = $session->get('total_score', 0);

        if (!$name || strlen($name) > 100) {
            return new Response('Invalid name', 400);
        }

        $gameScore = new GameScore();
        $gameScore
            ->setPlayerName($name)
            ->setScore($score)
            ->setPlayedAt(new DateTimeImmutable());

        $em->persist($gameScore);
        $em->flush();

        $session->invalidate();

        return $this->render('game/success.html.twig', [
            'score' => $score
        ]);
    }

}
