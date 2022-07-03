<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $fakerTr = Factory::create('tr_TR'); // create a French faker


        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setOrderCode("PTH".$faker->unique()->numberBetween(10000,99999));
            $order->setProductId($faker->randomDigitNotNull);
            $order->setQuantity($faker->randomDigitNotNull);
            $order->setAddress($fakerTr->address);
            $order->setShippingDate($faker->dateTimeBetween('-2 years','now',null));
            $manager->persist($order);
        }

        $manager->flush();
    }
}
