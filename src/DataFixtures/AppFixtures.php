<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $user = new User();
         $user->setUsername('Soukaina');
         $user->setPassword('Soukaina25');
         $user->setRole('ADMIN');
         $manager->persist($user);

         $user = new User();
         $user->setUsername('Hudayfa');
         $user->setPassword('any78/*');
         $user->setRole('ADMIN');
         $manager->persist($user);

         $user = new User();
         $user->setUsername('Clement');
         $user->setPassword('any78/*');
         $user->setRole('ADMIN');
         $manager->persist($user);

        $manager->flush();
    }
}
