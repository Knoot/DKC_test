<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $role = new Role();
            $role->setTitle($faker->word);
            $manager->persist($role);
        }

        $manager->flush();

        $roles = $this->roleRepository->findAll();

        for ($i = 0; $i < 30; $i++) {
            $user = new User();
            $user->setName($faker->firstName);
            $user->setRole($roles[array_rand($roles)]);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
