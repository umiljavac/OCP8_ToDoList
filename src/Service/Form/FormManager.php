<?php
/**
 * This file is a part of the ToDoList project of Openclassrooms PHP/Symfony
 * development course.
 *
 * (c) Sarah Khalil
 * (c) Ulrich Miljavac
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Form;

use App\Entity\Task;
use App\Entity\User;
use App\Form\Type\TaskType;
use App\Form\Type\UserType;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class FormManager
 */
class FormManager
{
    private $formFactory;

    /**
     * FormManager constructor.
     *
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param Task $task
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTaskForm(Task $task)
    {
        return  $this->createForm(TaskType::class, $task);
    }

    /**
     * @param User $user
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUserRegistrationForm(User $user)
    {
        return $this->createForm(UserType::class, $user);
    }

    /**
     * @param mixed $type
     * @param null  $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createForm($type, $data = null, array $options = array())
    {
        return $this->formFactory->create($type, $data, $options);
    }
}
