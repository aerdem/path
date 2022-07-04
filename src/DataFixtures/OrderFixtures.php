<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $fakerTr = Factory::create('tr_TR'); // create a Turkish faker

        for ($i = 0; $i < 10; $i++) {
            $user =  ($this->getReference(AppFixtures::USER_REFERENCE . '-' . rand(0, 2)));
            $order = new Order();
            $order->setOrderCode("PTH".$faker->unique()->numberBetween(10000,99999));
            $order->setProductId($faker->randomDigitNotNull);
            $order->setQuantity($faker->randomDigitNotNull);
            $order->setAddress($fakerTr->address);
            $order->setShippingDate($faker->dateTimeBetween('-2 years','now',null));
            $order->setCreatedBy($user->getId());
            $manager->persist($order);
        }
        $manager->flush();
    }
}
