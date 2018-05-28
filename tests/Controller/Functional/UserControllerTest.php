<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 21/05/2018
 * Time: 09:49
 */

namespace App\Tests\Controller\Functional;

use App\Tests\Controller\BaseFunctionalTest;

class UserControllerTest extends BaseFunctionalTest
{

    public function testUserRoleListAction()
    {
        $this->loggedUser->request('GET', '/users');

        $this->assertEquals(
            403,
            $this->loggedUser->getResponse()->getStatusCode()
        );
    }

    public function testListAction()
    {
        $crawler = $this->loggedAdmin->request('GET', '/users');
        $this->assertEquals(
            200,
            $this->loggedAdmin->getResponse()->getStatusCode()
        );
        $this->assertSame(
            3,
            $crawler->selectLink("Edit")->count()
        );
        $this->assertSame(
            1,
            $crawler->filter('html:contains("Liste des utilisateurs")')->count()
        );
    }

    public function testCreateAction()
    {
        $crawler = $this->loggedAdmin->request('GET', '/users/create');
        $this->assertSame(
            1,
            $crawler->filter('html:contains("Créer un utilisateur")')->count()
        );

        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'toto';
        $form['user[password][first]'] = 'toto';
        $form['user[password][second]'] = 'toto';
        $form['user[email]'] = 'toto@gmail.com';
        $form['user[formRoles]'] = 'ROLE_USER';

        $this->loggedAdmin->submit($form);

        $this->assertTrue($this->loggedAdmin->getResponse()->isRedirect());

        $crawler = $this->loggedAdmin->followRedirect();

        $this->assertSame(
            200,
            $this->loggedAdmin->getResponse()->getStatusCode()
        );
        $this->assertSame(
            4,
            $crawler->selectLink("Edit")->count()
        );
    }

    public function testEditAction()
    {
        $crawler = $this->loggedAdmin->request('GET', '/users/4/edit');
        $this->assertSame(
            1,
            $crawler->filter('html:contains("Créer un utilisateur")')->count()
        );

        $form = $crawler->selectButton('Modifier')->form();

        $form['user[username]'] = 'toto';
        $form['user[password][first]'] = 'toto';
        $form['user[password][second]'] = 'toto';
        $form['user[email]'] = 'toto@hotmail.com';
        $form['user[formRoles]'] = 'ROLE_ADMIN';

        $this->loggedAdmin->submit($form);

        $this->assertTrue($this->loggedAdmin->getResponse()->isRedirect());

        $crawler = $this->loggedAdmin->followRedirect();

        $this->assertSame(200,
            $this->loggedAdmin->getResponse()->getStatusCode()
        );
        $this->assertSame(
            4,
            $crawler->selectLink("Edit")->count()
        );
        $this->assertSame(
            1,
            $crawler->filter('td:contains("toto@hotmail.com")')->count()
        );
    }
}
