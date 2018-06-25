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

namespace App\Service\FlashMessage;

/**
 * Class FlashMessage
 */
class FlashMessage
{
    private $type;
    private $message;

    const TYPE_SUCCESS = 'success';
    const MESSAGE_TASK_ADDED = 'La tâche "%s" a bien été ajoutée.';
    const MESSAGE_TASK_REMOVED = 'La tâche "%s" a bien été supprimée.';
    const MESSAGE_TASK_EDITED = 'La tâche "%s" a bien été modifiée.';
    const MESSAGE_TASK_DONE = 'La tâche "%s" a bien été marquée comme faite.';
    const MESSAGE_TASK_TODO = 'La tâche "%s" a bien été marquée comme non terminée.';
    const MESSAGE_USER_ADDED = 'L\'utililateur "%s" a bien été ajouté.';
    const MESSAGE_USER_EDITED = 'L\'utililateur "%s" a bien été modifié.';

    /**
     * FlashMessage constructor.
     *
     * @param string $type
     * @param string $message
     */
    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
