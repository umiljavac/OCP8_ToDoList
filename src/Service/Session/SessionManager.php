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

namespace App\Service\Session;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SessionManager
 */
class SessionManager
{
    private $session;

    /**
     * SessionManager constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param Request $request
     */
    public function setEditRedirection(Request $request)
    {
        if (!preg_match('#edit$#', $request->headers->get('referer'))) {
            $this->session->set(
                'edit-redirection',
                $request->headers->get('referer')
            );
        }
    }

    /**
     * @return mixed
     */
    public function getEditRedirection()
    {
        return $this->session->get('edit-redirection');
    }
}
