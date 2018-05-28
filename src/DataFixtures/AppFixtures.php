<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 23/03/2018
 * Time: 16:00
 */

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setUsername('bob');
        $user->setEmail('bob@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'bob'));

        $manager->persist($user);

        $user1 = new User();
        $user1->setUsername('jo');
        $user1->setEmail('jo@gmail.com');
        $user1->setPassword($this->passwordEncoder->encodePassword($user1, 'jo'));

        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('max');
        $user2->setEmail('max@gmail.com');
        $user2->setPassword($this->passwordEncoder->encodePassword($user2, 'max'));

        $manager->persist($user2);

        $task = new Task();
        $task->setTitle('Manger des légumes');
        $task->setContent('Il faut manger 5 fruits et légumes par jour.');

        $manager->persist($task);

        $task = new Task();
        $task->setTitle('Faire la vaisselle');
        $task->setContent('Il faut faire la vaisselle chaque jour !');
        $task->setAuthor($user);

        $manager->persist($task);

        $task = new Task();
        $task->setTitle('Retourner à la piscine');
        $task->setContent('2 fois par semaine : natation !');
        $task->setAuthor($user1);

        $manager->persist($task);

        $task = new Task();
        $task->setTitle('Apprendre le Java');
        $task->setContent('Commencer par suivre un mooc sur OC.');
        $task->setAuthor($user2);

        $manager->persist($task);

        $manager->flush();
    }
}
