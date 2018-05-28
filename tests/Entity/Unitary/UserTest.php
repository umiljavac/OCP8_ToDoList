<?php
/**
 * Created by PhpStorm.
 * User: ulrich
 * Date: 22/05/2018
 * Time: 14:11
 */

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testSetRoles()
    {
        $user = new User();
        $user->setRoles('ROLE_ADMIN');
        $this->assertEquals(['ROLE_ADMIN'], $user->getRoles());
    }
}
