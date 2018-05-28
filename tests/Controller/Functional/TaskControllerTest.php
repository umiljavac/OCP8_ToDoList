<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 15/05/2018
 * Time: 16:40
 */

namespace App\Tests\Controller\Functional;

use App\Tests\Controller\BaseFunctionalTest;

class TaskControllerTest extends BaseFunctionalTest
{

    public function testNotLoggedListAction()
    {
        $this->client->request('GET', '/tasks');

        $this->assertEquals(
            302,
            $this->client->getResponse()->getStatusCode());
    }

    public function testListAction()
    {
        $crawler = $this->loggedAdmin->request('GET', '/tasks');

        $this->assertEquals(
            200,
            $this->loggedAdmin->getResponse()->getStatusCode()
        );
        $this->assertSame(
            1,
            $crawler->selectLink("Créer une tâche")->count()
        );
        $this->assertSame(
            2,
            $crawler->selectButton('Supprimer')->count()
        );
        $this->assertSame(
            1,
            $crawler->filter('html:contains("Manger des légumes")')->count()
        );
        $this->assertSame(
            2,
            $crawler->filter('div.caption')->count()
        );
    }

    public function testCreateTaskAction()
    {
        $crawler = $this->loggedAdmin->request('GET', '/tasks');

        $link = $crawler->selectLink('Créer une tâche')->link();
        $crawler = $this->loggedAdmin->click($link);

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = 'Sortir les poubelles';
        $form['task[content]'] = 'Il faut sortir la poubelle avant qu\'elle ne déborde';

        $this->loggedAdmin->submit($form);

        $this->assertTrue($this->loggedAdmin->getResponse()->isRedirect());

        $crawler = $this->loggedAdmin->followRedirect();

        $this->assertSame(
            200,
            $this->loggedAdmin->getResponse()->getStatusCode()
        );
        $this->assertSame(
            3,
            $crawler->filter('div.col-sm-4.col-lg-4.col-md-4')->count()
        );
        $this->assertSame(
            1,
            $crawler->filter('html:contains("Sortir les poubelles")')->count()
        );
        $this->assertSame(
            3,
            $crawler->selectButton('Supprimer')->count()
        );
    }

    public function testEditTaskAction()
    {
        $crawler = $this->loggedAdmin->request('GET', '/tasks');

        $link = $crawler->selectLink('Sortir les poubelles')->link();
        $crawler = $this->loggedAdmin->click($link);

        $form = $crawler->selectButton('Modifier')->form();

        $form['task[title]'] = 'Sortir la poubelle';
        $form['task[content]'] = 'Il faut sortir la poubelle avant qu\'elle ne déborde et que le plastique se déchire';

        $this->loggedAdmin->submit($form);

        $this->assertTrue($this->loggedAdmin->getResponse()->isRedirect());

        $crawler = $this->loggedAdmin->followRedirect();

        $this->assertSame(200,
            $this->loggedAdmin->getResponse()->getStatusCode()
        );
        $this->assertSame(
            3,
            $crawler->filter('div.col-sm-4.col-lg-4.col-md-4')->count()
        );
        $this->assertSame(
            1,
            $crawler->filter('html:contains("Sortir la poubelle")')->count()
        );
        $this->assertSame(
            1,
            $crawler->filter('html:contains("le plastique se déchire")')->count()
        );
    }

    public function testToggleTaskAction()
    {
        $crawler = $this->loggedAdmin->request('GET', '/tasks');

        $this->assertSame(
            3,
            $crawler->filter('span.glyphicon.glyphicon-remove')->count()
        );

        $form = $crawler->selectButton('Marquer comme faite')->eq(2)->form();
        $this->loggedAdmin->submit($form);

        $this->assertTrue($this->loggedAdmin->getResponse()->isRedirect());
        $crawler = $this->loggedAdmin->followRedirect();

        $this->assertSame(
            0,
            $crawler->filter('span.glyphicon.glyphicon-ok')->count()
        );

        $this->assertSame(
            2,
            $crawler->filter('span.glyphicon.glyphicon-remove')->count()
        );

        $link = $crawler->selectLink('Consulter la liste des tâches terminées')->link();
        $crawler = $this->loggedAdmin->click($link);

        $this->assertSame(
            1,
            $crawler->filter('html:contains("Liste des tâches terminées")')->count()
        );

        $form = $crawler->selectButton('Marquer non terminée')->form();
        $this->loggedAdmin->submit($form);

        $this->assertTrue($this->loggedAdmin->getResponse()->isRedirect());

        $crawler = $this->loggedAdmin->followRedirect();

        $this->assertSame(
            0,
            $crawler->filter('span.glyphicon.glyphicon-remove')->count()
        );
        $this->assertSame(
            0,
            $crawler->filter('span.glyphicon.glyphicon-ok')->count()
        );
        $this->assertSame(
            1,
            $crawler->filter('html:contains("Liste des tâches terminées")')->count()
        );
        $this->assertSame(
            1,
            $crawler->filter('html:contains("Il n\'y a pas encore de tâche enregistrée")')->count()
        );
    }

    public function testDeleteTaskAction()
    {
        $crawler = $this->loggedAdmin->request('GET', '/tasks');

        $this->assertSame(
            3,
            $crawler->filter('div.caption')->count()
        );

        $form = $crawler->selectButton('Supprimer')->eq(2)->form();

        $this->loggedAdmin->submit($form);

        $this->assertTrue($this->loggedAdmin->getResponse()->isRedirect());

        $crawler = $this->loggedAdmin->followRedirect();

        $this->assertSame(
            2,
            $crawler->filter('div.caption')->count()
        );
    }
}
