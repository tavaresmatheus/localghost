<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($counter = 0; $counter < 50; $counter++) {
            $user = new User();
            $firstName = Factory::faker()->firstName();
            $lastName = Factory::faker()->lastName();
            $fullName = "{$firstName} {$lastName}";
            $emailDomain = Factory::faker()->safeEmailDomain();
            $email = strtolower($firstName) . '.' . strtolower($lastName) . '@' . $emailDomain;
            $user->setName($fullName);
            $user->setEmail($email);
            $user->setPassword(password_hash(Factory::faker()->password(), PASSWORD_BCRYPT));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
