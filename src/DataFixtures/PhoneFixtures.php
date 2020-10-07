<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use App\Entity\Screen;
use App\Entity\Size;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhoneFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // One Plus 8 Pro
        $phone = new Phone();

        $screen = new Screen();
        $screen->hydrate('6.78 "', 'AMOLED', '3168 x 1440 px', '513 ppp', '120 Hz', $phone);

        $size = new Size();
        $size->hydrate('2.46 cm', '16.53 cm', '0.85 cm', $phone);
        
        $phone->hydrate(
            'One Plus',
            '8 Pro',
            750.00,
            'Android 10',
            'OxygenOS',
            'Qualcomm Snapdragon 865',
            '12 Go',
            '256 Go',
            '0.95 W/kg',
            '4510 mAh',
            true,
            '199 g',
            $size,
            $screen
        );     

        $manager->persist($phone);
        $manager->flush();

        //
    }
}
