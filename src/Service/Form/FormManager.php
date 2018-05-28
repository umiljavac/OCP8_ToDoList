<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 14/05/2018
 * Time: 13:26
 */

namespace App\Service\Form;


use App\Entity\Task;
use App\Form\Type\TaskType;
use Symfony\Component\Form\FormFactoryInterface;

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
     * @param $type
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
