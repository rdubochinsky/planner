<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\TaskSearchType;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Services\TaskSearchProcessor;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends ApiController
{
    private const NOT_FOUND_MESSAGE = 'Task is not found.';

    private TaskRepository $repository;

    /**
     * @param SerializerInterface $serializer
     * @param TaskRepository $repository
     */
    public function __construct(
        SerializerInterface $serializer,
        TaskRepository $repository
    ) {
        $this->repository = $repository;

        parent::__construct($serializer);
    }

    /**
     * @param int $id
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     *
     * @Route("/api/tasks/{id}", methods={"GET"}, format="json", requirements={"id"="\d+"})
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="Task id."
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns task by specified id.",
     *     @Model(type="Task::class")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when task is not found."
     * )
     */
    public function getOne(int $id): Response
    {
        $task = $this->repository->getOne($id);

        if ($task === null) {
            throw new NotFoundHttpException(self::NOT_FOUND_MESSAGE);
        }

        return $this->getResponse($task);
    }

    /**
     * @param Request $request
     * @param TaskSearchProcessor $searchProcessor
     *
     * @return Response
     *
     * @throws BadRequestHttpException
     *
     * @Route("/api/tasks", methods={"GET"}, format="json")
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     type="string",
     *     description="Task name.",
     *     required=false,
     *     @SWG\Schema(
     *         type="string"
     *     )
     * )
     * @SWG\Parameter(
     *     name="labels",
     *     in="query",
     *     type="array",
     *     @SWG\Items(type="integer"),
     *     description="Task labels.",
     *     required=false,
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(
     *             type="integer"
     *         )
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns list of tasks.",
     *     @Model(type="Task::class")
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Returned when search form criteria are invalid."
     * )
     */
    public function getList(
        Request $request,
        TaskSearchProcessor $searchProcessor
    ): Response {
        $searchCriteria = $request->query->all();
        $searchForm = $this->createForm(TaskSearchType::class);
        $searchForm->submit($searchCriteria);

        if (!$searchForm->isValid()) {
            throw new BadRequestHttpException((string) $searchForm->getErrors(true));
        }

        return $this->getResponse(
            $searchProcessor->getResult($searchCriteria)
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws BadRequestHttpException
     *
     * @Route("/api/tasks", methods={"POST"}, format="json")
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     description="Task name.",
     *     required=true,
     *     @SWG\Schema(
     *         type="string"
     *     )
     * )
     * @SWG\Parameter(
     *     name="date",
     *     in="body",
     *     description="Task date.",
     *     required=false,
     *     @SWG\Schema(
     *         type="date"
     *     )
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns created task.",
     *     @Model(type="Task::class")
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Returned when request data is invalid."
     * )
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(TaskType::class);
        $form->submit($this->deserialize($request->getContent()));

        if (!$form->isValid()) {
            throw new BadRequestHttpException((string) $form->getErrors(true));
        }

        $task = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();

        return $this->getResponse($task, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws BadRequestHttpException|NotFoundHttpException
     *
     * @Route("/api/tasks/{id}", methods={"PUT"}, format="json", requirements={"id"="\d+"})
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="Task id."
     * )
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     description="Task name.",
     *     required=true,
     *     @SWG\Schema(
     *         type="string"
     *     )
     * )
     * @SWG\Parameter(
     *     name="date",
     *     in="body",
     *     description="Task date.",
     *     required=false,
     *     @SWG\Schema(
     *         type="date"
     *     )
     * )
     * @SWG\Parameter(
     *     name="isCompleted",
     *     in="body",
     *     description="Is task completed?",
     *     required=false,
     *     @SWG\Schema(
     *         type="integer"
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns updated task.",
     *     @Model(type="Task::class")
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Returned when request data is invalid."
     * )
     */
    public function update(Request $request): Response
    {
        $task = $this->repository->getOne((int) $request->get('id'));

        if ($task === null) {
            throw new NotFoundHttpException(self::NOT_FOUND_MESSAGE);
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->submit($this->deserialize($request->getContent()));

        if (!$form->isValid()) {
            throw new BadRequestHttpException((string) $form->getErrors(true));
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->getResponse($task);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     *
     * @Route("/api/tasks/{id}", methods={"DELETE"}, format="json", requirements={"id"="\d+"})
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="integer",
     *     description="Task id."
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns when task is deleted.",
     *     @Model(type="Task::class")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when task is not found."
     * )
     */
    public function delete(Request $request): Response
    {
        $task = $this->repository->getOne((int) $request->get('id'));

        if ($task === null) {
            throw new NotFoundHttpException(self::NOT_FOUND_MESSAGE);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        return $this->getResponse('Resource has been deleted.');
    }
}
