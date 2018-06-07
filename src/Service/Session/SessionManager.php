<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 25/05/2018
 * Time: 17:23
 */

namespace App\Service\Session;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionManager
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function setEditRedirection(Request $request)
    {
        if (!preg_match('#edit$#', $request->headers->get('referer'))) {
            $this->session->set('edit-redirection', $request->headers->get('referer'));
        }
    }

    public function getEditRedirection()
    {
        return $this->session->get('edit-redirection');
    }
}
