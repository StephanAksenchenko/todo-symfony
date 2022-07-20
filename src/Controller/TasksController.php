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
        $tasks= $this->doctrine->getRepository(Task::class)->findAll();


        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasks
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
            $task->setCreatedAt(new \DateTime('now'));

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

    #[Route('tasks/info/{id}', name: 'app_tasks_info')]
    public function info(int $id)
    {
        $task= $this->doctrine->getRepository(Task::class)->find($id);
        return $this->render('tasks/info.html.twig', [
            'task' => $task
        ]);
    }
}
