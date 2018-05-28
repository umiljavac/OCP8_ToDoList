<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/05/2018
 * Time: 10:43
 */

namespace App\Service\Mailer;

use App\Entity\User;
use Psr\Container\ContainerInterface;

class MailerService
{
    private $mailer;
    private $container;
    const FROM = 'ulm_ulm@hotmail.com';
    const SUBJECT_REGISTER = 'ToDo & CO : Identifiants de connexion';
    const SUBJECT_EDIT_USER = 'ToDo & CO : Mise à jour de vos informations';
    const TEMPLATE_CREATE = 'emails/createUserInformations.html.twig';
    const TEMPLATE_EDIT = 'emails/editUserInformations.html.twig';

    public function __construct(\Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    /**
     * @param User $user
     * @param $subject
     * @param $template
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function sendMail(User $user, $subject, $template)
    {
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom(self::FROM)
            ->setTo($user->getEmail())
            ->setBody(
                $this->container->get('twig')->render(
                    $template,
                    array(
                        'user' => $user)
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}