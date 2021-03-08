<?php


namespace App\DataFixture;


use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixture extends Fixture
{

    private $productArray = array("Telefon", "Telewizor", "Pralka", "Lodówka","Laptop");
    public function load(ObjectManager $manager)
    {
        $this->loadProducts($manager);

    }

    public function loadProducts(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for($i=0; $i<5; $i++) {
            $product = new Product();
            $product->setName($this->productArray[$i]);
            $product->setQuantity($faker->numberBetween(1,6));
            $product->setStatus(1);
            $manager->persist(($product));

        }
        $manager->flush();


    }

}