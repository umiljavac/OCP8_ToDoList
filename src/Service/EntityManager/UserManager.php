<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 21/05/2018
 * Time: 10:04
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
    private $formManager;
    private $passwordEncoder;
    private $mailerService;

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface       $em
     * @param FormManager                  $formManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerService                $mailerService
     */
    public function __construct(EntityManagerInterface $em, FormManager $formManager, UserPasswordEncoderInterface $passwordEncoder, MailerService $mailerService)
    {
        $this->em = $em;
        $this->formManager = $formManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->mailerService = $mailerService;
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
        $form = $this->formManager->createUserRegistrationForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mailerService->sendMail(
                $user,
                MailerService::SUBJECT_REGISTER,
                MailerService::TEMPLATE_CREATE
            );

            $encodedPassword = $this->passwordEncoder->encodePassword(
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
        $form = $this->formManager->createUserRegistrationForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mailerService->sendMail(
                $user,
                MailerService::SUBJECT_EDIT_USER,
                MailerService::TEMPLATE_EDIT
            );

            $encodedPassword = $this->passwordEncoder->encodePassword(
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
