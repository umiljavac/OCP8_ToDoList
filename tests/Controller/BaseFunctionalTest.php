<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 15/05/2018
 * Time: 16:57
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseFunctionalTest extends WebTestCase
{
    protected $client = null;
    protected $loggedAdmin = null;
    protected $loggedUser = null;

    public function setUp()
    {
        $this->client = static::createClient();

        $this->loggedAdmin = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'max',
            'PHP_AUTH_PW'   => 'max',
        ));

        $this->loggedUser = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'jo',
            'PHP_AUTH_PW'   => 'jo',
        ));

    }
}
