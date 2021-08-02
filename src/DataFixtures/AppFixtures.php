<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $staff = new User();
        $staff->setUsername("Administrator");
        $staff->setEmail("admin@gmail.com");
        $staff->setGender("male");
        $staff->setTitle('superadmin');
        $staff->setPassword('$argon2id$v=19$m=65536,t=4,p=1$L253MHFwQXQ5WC5ObHYvSA$NTh9TvR5AW9fUnPmlxNJjDdv3pwcaGkFNW5BCRBE8uA');
        $staff->setNationality("Tanzanian");
        $staff->setMaritalStatus("single");
        $staff->setRoles(['ROLE_ADMIN']);
        $manager->persist($staff);
        $manager->flush();
    }
}
