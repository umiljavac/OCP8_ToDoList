<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 23/06/2018
 * Time: 14:48
 */
namespace App\Controller;

use App\Entity\Task;
use App\Service\EntityManager\TaskManager;
use App\Service\FlashMessage\FlashMessage;
use App\Service\Session\SessionManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TaskController
 */
class TaskController extends Controller
{
    /**
     * @Route("/tasks/done", name="task_done")
     *
     * @param TaskManager $taskManager
     *
     * @return Response
     */
    public function listTaskDoneAction(TaskManager $taskManager)
    {
        return $this->render(
            'views/task/list.html.twig',
            [
                'tasks' => $taskManager->listUserTasksDone($this->getUser()),
            ]
        );
    }

    /**
     * @Route("/tasks", name="task_list")
     *
     * @param TaskManager $taskManager
     *
     * @return Response
     */
    public function listTaskToDoAction(TaskManager $taskManager)
    {
        return $this->render(
            'views/task/list.html.twig',
            [
                'tasks' => $taskManager->listUserTasksToDo($this->getUser()),
            ]
        );
    }

    /**
     * @Route("/tasks/create", name="task_create")
     *
     * @param Request     $request
     * @param TaskManager $taskManager
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, TaskManager $taskManager)
    {
        $managerResult = $taskManager->createTask($request, $this->getUser());

        if (is_array($managerResult)) {
            return $this->redirectToListOfTasks(
                $managerResult['message'],
                $managerResult['task']
            );
        }

        return $this->render(
            'views/task/create.html.twig',
            ['form' => $managerResult]
        );
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     *
     * @param Task           $task
     * @param Request        $request
     * @param TaskManager    $taskManager
     * @param SessionManager $sessionManager
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Task $task, Request $request, TaskManager $taskManager, SessionManager $sessionManager)
    {
        $managerResult = $taskManager->editTask($request, $task);

        if ($managerResult instanceof FlashMessage) {
            return $this->redirectAfterTaskEdit(
                $managerResult,
                $task,
                $sessionManager
            );
        }

        return $this->render('views/task/edit.html.twig', [
            'form' => $managerResult,
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     *
     * @param Task        $task
     * @param Request     $request
     * @param TaskManager $taskManager
     *
     * @return RedirectResponse
     */
    public function toggleTaskAction(Task $task, Request $request, TaskManager $taskManager)
    {
        $managerResult = $taskManager->toggleTask($task);

        return $this->redirectToReferer($managerResult, $task, $request);
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     *
     * @param Task        $task
     * @param Request     $request
     * @param TaskManager $taskManager
     *
     * @return RedirectResponse
     */
    public function deleteTaskAction(Task $task, Request $request, TaskManager $taskManager)
    {
        $managerResult = $taskManager->deleteTask($task);

        return $this->redirectToReferer($managerResult, $task, $request);
    }

    /**
     * @param FlashMessage $flashMessage
     * @param Task         $task
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToListOfTasks(FlashMessage $flashMessage, Task $task)
    {
        $this->addFlash(
            $flashMessage->getType(),
            sprintf($flashMessage->getMessage(), $task->getTitle())
        );

        return $this->redirectToRoute('task_list');
    }

    /**
     * @param FlashMessage $flashMessage
     * @param Task         $task
     * @param Request      $request
     *
     * @return RedirectResponse
     */
    public function redirectToReferer(FlashMessage $flashMessage, Task $task, Request $request)
    {
        $this->addFlash(
            $flashMessage->getType(),
            sprintf($flashMessage->getMessage(), $task->getTitle())
        );

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param FlashMessage   $flashMessage
     * @param Task           $task
     * @param SessionManager $sessionManager
     *
     * @return RedirectResponse
     */
    public function redirectAfterTaskEdit(FlashMessage $flashMessage, Task $task, SessionManager $sessionManager)
    {
        $this->addFlash(
            $flashMessage->getType(),
            sprintf($flashMessage->getMessage(), $task->getTitle())
        );

        return $this->redirect($sessionManager->getEditRedirection());
    }
}
