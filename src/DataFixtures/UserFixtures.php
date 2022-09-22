<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Helpers\Helper;

class UserFixtures extends Fixture
{
    /**
     * loading User data fixtures for symfony test
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $helper = new Helper();
        $user = new User();
        $user->setEmail($helper->randomEmail());
        $user->setPassword(Helper::TEST_USER_PASSWORD);
        $user->setRoles($helper->randomValue('role'));
        $manager->persist($user);

        $manager->flush();
    }
}
