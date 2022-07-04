<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const USER_REFERENCE = 'order-user';
    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    public function __construct(
        UserPasswordHasherInterface $hasher
    )
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $fakerTr = Factory::create('tr_TR'); // create a Turkish faker
        for ($i = 0; $i < 3; $i++) {

            $user = new User();
            $password = $faker->password;
            $username = $fakerTr->userName;
            $firstname = $fakerTr->firstName;
            $lastname = $fakerTr->lastName;
            $hashed = $this->hasher->hashPassword($user, $password);
            $user->setFirstName($firstname);
            $user->setLastName($lastname);
            $user->setUsername($username);
            $user->setPassword($hashed);
            $manager->persist($user);
            $manager->flush();
            $this->addReference(self::USER_REFERENCE . '-' . $i, $user);
            print_r(
                    array(
                        "username" => $username,
                        "password" => $password,
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "hashed" => $hashed
                    )
                );
        }


    }
}
