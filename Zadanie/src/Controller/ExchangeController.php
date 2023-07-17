<?php

namespace App\Controller;

use App\Entity\History;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
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

    /**
     * @Route("/history", methods={"GET", "POST"})
     */
    public function history(Request $request, PaginatorInterface $paginator): JsonResponse
    {
        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);

            $first = $data['first'];
            $second = $data['second'];

            $temp = $first;
            $first = $second;
            $second = $temp;

            $now = new \DateTime();

            $history = new History();
            $history->setFirstIn($first);
            $history->setSecondIn($second);
            $history->setFirstOut($second);
            $history->setSecondOut($first);
            $history->setCreatedAt($now);
            $history->setUpdatedAt($now);

            $this->entityManager->persist($history);
            $this->entityManager->flush();

            return new JsonResponse(['message' => 'Data saved successfully']);
        }

        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $sortColumn = $request->query->get('sort_column', 'id');
        $sortOrder = $request->query->get('sort_order', 'asc');

        $historyRepository = $this->entityManager->getRepository(History::class);
        $queryBuilder = $historyRepository->createQueryBuilder('h')
            ->orderBy('h.' . $sortColumn, $sortOrder);

        $pagination = $paginator->paginate($queryBuilder, $page, $limit);

        $data = [];
        foreach ($pagination as $record) {
            $data[] = [
                'id' => $record->getId(),
                'first_in' => $record->getFirstIn(),
                'second_in' => $record->getSecondIn(),
                'first_out' => $record->getFirstOut(),
                'second_out' => $record->getSecondOut(),
                'created_at' => $record->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $record->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse([
            'data' => $data,
            'total_items' => $pagination->getTotalItemCount(),
            'current_page' => $pagination->getCurrentPageNumber(),
            'items_per_page' => $pagination->getItemNumberPerPage(),
        ]);
    }
}
