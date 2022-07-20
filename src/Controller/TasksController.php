<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TaskType;

class TasksController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/tasks', name: 'app_tasks')]
    public function index(): Response
    {
        return $this->render('tasks/index.html.twig', [
            'controller_name' => 'TasksController',
        ]);
    }

    #[Route('/tasks/add', name: 'app_tasks_add')]
    public function add(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $task = $form->getData();

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_tasks__add_successfully');
        }

        

        return $this->renderForm('tasks/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/tasks/add/successfully', name: 'app_tasks__add_successfully')]
    public function addedSuccessfully()
    {
        return $this->render('tasks/add_successfully.html.twig');
    }
}
