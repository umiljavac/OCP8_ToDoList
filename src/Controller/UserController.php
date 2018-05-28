<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\EntityManager\UserManager;
use App\Service\FlashMessage\FlashMessage;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{
    /**
     * @Route("/users", name="user_list")
     *
     * @param UserManager $userManager
     *
     * @return Response
     */
    public function listAction(UserManager $userManager)
    {
        return $this->render('views/user/list.html.twig', ['users' => $userManager->listAllAction()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     *
     * @param Request     $request
     * @param UserManager $userManager
     *
     * @return RedirectResponse|Response
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createAction(Request $request, UserManager $userManager)
    {
        $managerResult = $userManager->createUser($request);

        if (is_array($managerResult)) {
            return $this->redirectToListOfUsers($managerResult['message'], $managerResult['user']);
        }
        return $this->render('views/user/create.html.twig', ['form' => $managerResult]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     *
     * @param User        $user
     * @param Request     $request
     * @param UserManager $userManager
     *
     * @return Response
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function editAction(User $user, Request $request, UserManager $userManager)
    {
        $managerResult = $userManager->editUser($request, $user);

        if ($managerResult instanceof FlashMessage) {
            return $this->redirectToListOfUsers($managerResult, $user);
        }
        return $this->render('views/user/edit.html.twig', ['form' => $managerResult, 'user' => $user]);
    }

    /**
     * @param FlashMessage $flashMessage
     * @param User         $user
     *
     * @return RedirectResponse
     */
    public function redirectToListOfUsers(FlashMessage $flashMessage, User $user)
    {
        $this->addFlash($flashMessage->getType(), sprintf($flashMessage->getMessage(), $user->getUsername()));

        return $this->redirectToRoute('user_list');
    }
}
