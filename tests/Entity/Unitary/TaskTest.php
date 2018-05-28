<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 15/05/2018
 * Time: 16:08
 */

namespace App\Tests\Entity\Unitary;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTaskIsDone()
    {
        $task = new Task();
        $this->assertEquals(false, $task->isDone());
    }

    public function testToggle()
    {
        $task = new Task();
        $task->toggle(!$task->isDone());
        $this->assertEquals(true, $task->isDone());
        $task->toggle(!$task->isDone());
        $this->assertEquals(false, $task->isDone());
    }

    public function testSetAuthor()
    {
        $task = new Task();
        $user = new User();
        $task->setAuthor($user);
        $this->assertEquals($user, $task->getAuthor());
    }

    public function testGetCreatedAt()
    {
        $task = new Task();
        $task->setCreatedAt(new \DateTime());
        $this->assertNotSame(new \DateTime(), $task->getCreatedAt());
    }

    public function testGetTitle()
    {
        $task = new task();
        $task->setTitle('Dire bonjour');
        $this->assertEquals('Dire bonjour', $task->getTitle());
    }

    public function testGetContent()
    {
        $task = new task();
        $task->setContent('Il faut dire bonjour à tout le monde');
        $this->assertEquals('Il faut dire bonjour à tout le monde', $task->getContent());
    }
}
