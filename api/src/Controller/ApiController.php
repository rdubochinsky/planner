<?php

declare(strict_types=1);

namespace App\Controller;

use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends AbstractController
{
    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $data
     *
     * @return string
     */
    protected function serialize($data): string
    {
        return $this->serializer->serialize($data, 'json');
    }

    /**
     * @param string $content
     *
     * @return array
     */
    protected function deserialize(string $content): array
    {
        return (array) json_decode($content, true);
    }

    /**
     * @param mixed $content
     * @param int $status
     *
     * @return Response
     */
    protected function getResponse($content, int $status = Response::HTTP_OK): Response
    {
        return new Response($this->serialize($content), $status);
    }
}
