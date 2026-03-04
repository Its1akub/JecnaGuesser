<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/stats', name: 'admin_stats')]
    public function stats(EntityManagerInterface $em): Response
    {
        $stats = $em->createQuery("
            SELECT v.utmSource, v.utmMedium, v.utmCampaign, COUNT(v.id) as visits
            FROM App\Entity\Visit v
            GROUP BY v.utmSource, v.utmMedium, v.utmCampaign
            ORDER BY visits DESC
        ")->getResult();

        return $this->render('admin/stats.html.twig', [
            'stats' => $stats
        ]);
    }
}