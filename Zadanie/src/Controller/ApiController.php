<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/endpoint", methods={"POST"})
     */
    public function handleApiRequest(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $responseData = [
            'message' => 'Success',
            'data' => $data,
        ];

        $json = $this->serializer->serialize($responseData, JsonEncoder::FORMAT);

        return new Response($json, Response::HTTP_OK, [
            'Content-Type' => 'application/json',
        ]);
    }
}
