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
use Symfony\Component\Filesystem\Filesystem;

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

        return $this->render('game/game.html.twig', [
            'difficulty' => ucfirst($difficulty),
            'location_path' => $gameLocations[0]->getImagePath()
            //'location_path' => 'L' . strval(73) . ".jpg"
        ]);
    }

    #[Route('/game/guess', name: 'game_guess', methods: ['POST'])]
    public function guess(Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $locationIds = $session->get('game_locations', []);
        $currentRound = $session->get('current_round', 0);

        if (!isset($locationIds[$currentRound])) {
            return $this->json(['error' => 'Game finished'], 400);
        }

        $location = $em->getRepository(GameLocation::class)->find($locationIds[$currentRound]);
        if (!$location) {
            return $this->json(['error' => 'Invalid location'], 400);
        }

        // Odhadnutý X/Y od frontendu
        $xGuess = (float)$request->request->get('x');
        $yGuess = (float)$request->request->get('y');
        $floorGuess = (float)$request->request->get('floor');

        //$fs = new Filesystem();
        //$fs->appendToFile("logs.txt", sprintf("(%f, %f, %u, '%s', 'easy'),\n", $xGuess, $yGuess, $floorGuess, "L" . strval($currentRound + 73) . ".jpg"));



        $distance = sqrt(pow($xGuess - $location->getX(), 2) + pow($yGuess - $location->getY(), 2));
        $floorDistance = abs($floorGuess - $location->getFloor());


        $points = $this->calculateScore($distance, $floorDistance);

        $totalScore = $session->get('total_score', 0) + $points;
        $session->set('total_score', $totalScore);

        $session->set('current_round', $currentRound + 1);

        // Konec hry po 5 kolech
        $isFinished = ($currentRound + 1 >= count($locationIds));

        $nextLocationPath = null;
        if (!$isFinished) {
            $nextLocation = $em->getRepository(GameLocation::class)->find($locationIds[$currentRound + 1]);
            $nextLocationPath = $nextLocation ? $nextLocation->getImagePath() : null;
        }

        return $this->json([
            'points' => $points,
            'total_score' => $totalScore,
            'next_round' => $currentRound + 1,
            'is_finished' => $isFinished,
            'redirect_url' => $this->generateUrl('game_finish'),
            'actual_x' => $location->getX(),
            'actual_y' => $location->getY(),
            'actual_floor' => $location->getFloor(),
            'next_location_path' => $nextLocationPath
        ]);
    }

    public function calculateScore($distance, $floorDistance) {
        $maxPoints = 5000 - ($floorDistance * 1000);
        $lowerLimit = 1.5;
        $upperLimit = 55.0;

        if ($distance <= $lowerLimit) {
            return $maxPoints;
        }
        if ($distance >= $upperLimit) {
            return 0;
        }

        $normalizedDist = ($distance - $lowerLimit) / ($upperLimit - $lowerLimit);

        /**
         * Exponential Decay Formula: Score = Max * e^(-k * dist)
         * We adjust it so it hits exactly 0 at the upper limit.
         * Higher 'k' = steeper drop. 2.0 is a "slight" decay.
         */
        $k = 0.8;
        return (int)round($maxPoints * (exp(-$k * $normalizedDist) - exp(-$k)) / (1 - exp(-$k)));
    }

    #[Route('/game/save', name: 'game_save', methods: ['POST'])]
    public function save(Request $request, EntityManagerInterface $em, SessionInterface $session): Response
    {
        $name = trim($request->request->get('playerName'));
        $score = $session->get('total_score', 0);

        $time = $session->get('total_time', 0);
        $difficulty = $session->get('difficulty', 'N/A');

        if (!$name || strlen($name) > 100) {
            return new Response('Invalid name', 400);
        }
        if (!$score || $score <= 0) {
            return new Response('No points', 400);
        }

        $gameScore = new GameScore();
        $gameScore
            ->setPlayerName($name)
            ->setScore($score)
            ->setTime($time)
            ->setDifficulty($difficulty)
            ->setPlayedAt(new \DateTimeImmutable());

        $em->persist($gameScore);
        $em->flush();

        $session->invalidate();

        return $this->redirectToRoute('app_leaderboard', ['difficulty' => $difficulty]);
    }

}
