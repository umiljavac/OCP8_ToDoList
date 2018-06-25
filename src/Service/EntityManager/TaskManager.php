<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 14/05/2018
 * Time: 12:59
 */

namespace App\Service\EntityManager;

use App\Entity\Task;
use App\Entity\User;
use App\Service\Form\FormManager;
use App\Service\Session\SessionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FlashMessage\FlashMessage;

/**
 * Class TaskManager
 */
class TaskManager
{
    private $em;
    private $formManager;
    private $sessionManager;

    /**
     * TaskManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param FormManager            $formManager
     * @param SessionManager         $sessionManager
     */
    public function __construct(EntityManagerInterface $em, FormManager $formManager, SessionManager $sessionManager)
    {
        $this->em = $em;
        $this->formManager = $formManager;
        $this->sessionManager = $sessionManager;
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function listUserTasksToDo(User $user)
    {
        return $this->em->getRepository(Task::class)->tasksToDo($user);
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function listUserTasksDone(User $user)
    {
        return $this->em->getRepository(Task::class)->tasksDone($user);
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function createTask(Request $request, User $user)
    {
        $task = new Task();
        $form = $this->formManager->createTaskForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setAuthor($user);
            $this->em->persist($task);
            $this->em->flush();

            $flashMessage = new FlashMessage(
                FlashMessage::TYPE_SUCCESS,
                FlashMessage::MESSAGE_TASK_ADDED
            );
            $managerResult['message'] = $flashMessage;
            $managerResult['task'] = $task;

            return $managerResult;
        }

        return $form->createView();
    }

    /**
     * @param Request $request
     * @param Task    $task
     *
     * @return FlashMessage|\Symfony\Component\Form\FormView
     */
    public function editTask(Request $request, Task $task)
    {
        $form = $this->formManager->createTaskForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return new FlashMessage(
                FlashMessage::TYPE_SUCCESS,
                FlashMessage::MESSAGE_TASK_EDITED
            );
        }
        $this->sessionManager->setEditRedirection($request);

        return $form->createView();
    }

    /**
     * @param Task $task
     *
     * @return FlashMessage
     */
    public function deleteTask(Task $task)
    {
        $this->em->remove($task);
        $this->em->flush();

        return new FlashMessage(
            FlashMessage::TYPE_SUCCESS,
            FlashMessage::MESSAGE_TASK_REMOVED
        );
    }

    /**
     * @param Task $task
     *
     * @return FlashMessage
     */
    public function toggleTask(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->em->flush();
        $task->isDone() ? $msg = FlashMessage::MESSAGE_TASK_DONE :
            $msg = FlashMessage::MESSAGE_TASK_TODO
        ;

        return new FlashMessage(
            FlashMessage::TYPE_SUCCESS,
            $msg
        );
    }
}
