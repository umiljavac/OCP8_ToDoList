<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 15/05/2018
 * Time: 20:11
 */

namespace App\Tests\Controller\Functional;


use App\Tests\Controller\BaseFunctionalTest;

class SecurityControllerTest extends BaseFunctionalTest
{
    public function testLoginAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'bob';
        $form['_password'] = 'bob';
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->followRedirect();

        $this->assertSame(
            200,
            $client->getResponse()->getStatusCode()
        );
        $this->assertSame(
            4,
            $crawler->filter('a.btn')->count()
        );
        $this->assertSame(
            1,
            $crawler->selectLink("Créer une nouvelle tâche")->count()
        );
        $this->assertSame(
            1,
            $crawler->selectLink("Consulter la liste des tâches à faire")->count()
        );
        $this->assertSame(
            1,
            $crawler->selectLink("Consulter la liste des tâches terminées")->count()
        );
        $this->assertSame(
            1,
            $crawler->selectLink("Se déconnecter")->count())
        ;
        $this->assertSame(
            1,
            $crawler->filter('html:contains("Bienvenue sur Todo List")')->count());
    }

    public function testLogoutAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'jo';
        $form['_password'] = 'jo';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->followRedirect();

        $this->assertSame(
            1,
            $crawler->selectLink("Se déconnecter")->count()
        );

        $link = $crawler->selectLink('Se déconnecter')->link();
        $client->click($link);

        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->followRedirect();

        $this->assertSame(
            1,
            $crawler->selectButton("Se connecter")->count()
        );
    }
}
