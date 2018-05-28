<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 18/05/2018
 * Time: 12:22
 */

namespace App\Tests\Controller\Functional;

use App\Tests\Controller\BaseFunctionalTest;

class DefaultControllerTest extends BaseFunctionalTest
{
    public function  testNotLoggedHomePageAction()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertSame(
            1,
            $crawler->selectButton("Se connecter")->count()
        );
    }

    public function testHomePageAction()
    {
        $crawler = $this->loggedAdmin->request('GET', '/');
        $this->assertEquals(200, $this->loggedAdmin->getResponse()->getStatusCode());
        $this->assertSame(
            6,
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
            $crawler->selectLink("Créer un utilisateur")->count()
        );
        $this->assertSame(
            1, $crawler->selectLink("Se déconnecter")->count()
        );
        $this->assertSame(
            1,
            $crawler->filter('html:contains("Bienvenue sur Todo List")')->count()
        );
    }
}
