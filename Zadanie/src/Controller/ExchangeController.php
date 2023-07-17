<?php

namespace App\Controller;

use App\Entity\History;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/exchange/values", methods={"POST"})
     */
    public function exchangeValues(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $first = $data['first'] ?? null;
        $second = $data['second'] ?? null;

        if ($first === null || $second === null) {
            return new JsonResponse(['error' => 'Invalid input data'], Response::HTTP_BAD_REQUEST);
        }

        $history = new History();
        $history->setFirstIn($first);
        $history->setSecondIn($second);
        $history->setCreatedAt(new \DateTime());
        $history->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($history);
        $this->entityManager->flush();

        $first = $first ^ $second;
        $second = $first ^ $second;
        $first = $first ^ $second;

        $history->setFirstOut($first);
        $history->setSecondOut($second);
        $history->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

       
        return new JsonResponse(['first' => $first, 'second' => $second]);
    }
}
