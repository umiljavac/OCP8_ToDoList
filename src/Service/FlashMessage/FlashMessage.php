<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 14/05/2018
 * Time: 14:44
 */

namespace App\Service\FlashMessage;

class FlashMessage
{
    /**
     * @var $type string
     */
    private $type;

    /**
     * @var $message string
     */
    private $message;

    const TYPE_SUCCESS= 'success';
    const MESSAGE_TASK_ADDED = 'La tâche "%s" a bien été ajoutée.';
    const MESSAGE_TASK_REMOVED = 'La tâche "%s" a bien été supprimée.';
    const MESSAGE_TASK_EDITED = 'La tâche "%s" a bien été modifiée.';
    const MESSAGE_TASK_DONE = 'La tâche "%s" a bien été marquée comme faite.';
    const MESSAGE_TASK_TODO = 'La tâche "%s" a bien été marquée comme non terminée.';

    /**
     * FlashMessage constructor.
     *
     * @param $type
     * @param $message
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
