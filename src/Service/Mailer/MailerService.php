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

namespace App\Service\Mailer;

use App\Entity\User;
use Psr\Container\ContainerInterface;

/**
 * Class MailerService
 */
class MailerService
{
    private $mailer;
    private $container;
    const FROM = 'ulm_ulm@hotmail.com';
    const SUBJECT_REGISTER = 'ToDo & CO : Identifiants de connexion';
    const SUBJECT_EDIT_USER = 'ToDo & CO : Mise à jour de vos informations';
    const TEMPLATE_CREATE = 'emails/createUserInformations.html.twig';
    const TEMPLATE_EDIT = 'emails/editUserInformations.html.twig';

    /**
     * MailerService constructor.
     *
     * @param \Swift_Mailer      $mailer
     * @param ContainerInterface $container
     */
    public function __construct(\Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    /**
     * @param User   $user
     * @param string $subject
     * @param string $template
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
                        'user' => $user,
                        )
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
