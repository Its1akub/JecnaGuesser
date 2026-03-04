<?php

namespace App\EventSubscriber;

use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RequestStack;

class UtmSubscriber implements EventSubscriberInterface
{
    private array $allowedSources = ['qr_plakat', 'discord', 'instagram'];
    private array $allowedMediums = ['qr', 'social', 'direct'];
    private array $allowedCampaigns = ['spse2026'];

    public function __construct(
        private EntityManagerInterface $em,
        private readonly RequestStack $requestStack
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $session = $this->requestStack->getSession();
        if (!$event->isMainRequest()) {
            return;
        }

        if ($session->has('utm_tracked')) {
            return;
        }

        $request = $event->getRequest();

        $utmSource = $request->query->get('utm_source');
        $utmMedium = $request->query->get('utm_medium');
        $utmCampaign = $request->query->get('utm_campaign');

        if ($utmSource || $utmMedium || $utmCampaign) {

            $visit = new Visit();
            if (
                in_array($utmSource, $this->allowedSources, true) &&
                in_array($utmMedium, $this->allowedMediums, true) &&
                in_array($utmCampaign, $this->allowedCampaigns, true)
            ) {
                $visit
                    ->setUtmSource($utmSource)
                    ->setUtmMedium($utmMedium)
                    ->setUtmCampaign($utmCampaign)
                    ->setVisitedAt(new \DateTimeImmutable())
                    ->setSessionId($this->requestStack->getSession()->getId());

                $this->em->persist($visit);
                $this->em->flush();
            }


            $session->set('utm_tracked', true);

            $cleanUrl = $request->getSchemeAndHttpHost() . $request->getPathInfo();
            $event->setResponse(new RedirectResponse($cleanUrl));
        }
    }
}