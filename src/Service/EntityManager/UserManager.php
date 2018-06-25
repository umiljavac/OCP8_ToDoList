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

namespace App\Service\EntityManager;

use App\Entity\User;
use App\Service\FlashMessage\FlashMessage;
use App\Service\Form\FormManager;
use App\Service\Mailer\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserManager
 */
class UserManager
{
    private $em;
    private $fm;
    private $pe;
    private $ms;

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface       $em
     * @param FormManager                  $fm
     * @param UserPasswordEncoderInterface $pe
     * @param MailerService                $ms
     */
    public function __construct(EntityManagerInterface $em, FormManager $fm, UserPasswordEncoderInterface $pe, MailerService $ms)
    {
        $this->em = $em;
        $this->fm = $fm;
        $this->pe = $pe;
        $this->ms = $ms;
    }

    /**
     * @return array
     */
    public function listAllAction()
    {
        return $this->em->getRepository(User::class)->findAll();
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\Form\FormView
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createUser(Request $request)
    {
        $user = new User();
        $form = $this->fm->createUserRegistrationForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->ms->sendMail(
                $user,
                MailerService::SUBJECT_REGISTER,
                MailerService::TEMPLATE_CREATE
            );

            $encodedPassword = $this->pe->encodePassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($encodedPassword);
            $this->em->persist($user);
            $this->em->flush();

            $flashMessage = new FlashMessage(
                FlashMessage::TYPE_SUCCESS,
                FlashMessage::MESSAGE_USER_ADDED
            );
            $managerResult['message'] = $flashMessage;
            $managerResult['user'] = $user;

            return $managerResult;
        }

        return $form->createView();
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return FlashMessage|\Symfony\Component\Form\FormView
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function editUser(Request $request, User $user)
    {
        $form = $this->fm->createUserRegistrationForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->ms->sendMail(
                $user,
                MailerService::SUBJECT_EDIT_USER,
                MailerService::TEMPLATE_EDIT
            );

            $encodedPassword = $this->pe->encodePassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($encodedPassword);
            $this->em->flush();

            return new FlashMessage(
                FlashMessage::TYPE_SUCCESS,
                FlashMessage::MESSAGE_USER_EDITED
            );
        }

        return $form->createView();
    }
}
