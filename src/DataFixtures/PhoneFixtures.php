<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use App\Entity\Screen;
use App\Entity\Size;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PhoneFixtures extends Fixture
{    
    /**
     * parameterBag
     * 
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;
    
    /**
     * __construct
     *
     * @param ParameterBagInterface  $parameterBag
     * @return void
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }
    
    public function load(ObjectManager $manager): void
    {
        // Initial data list of 10 phones, contained in the phones.ini file at the root of the application.
        $phones = parse_ini_file(
            $this->parameterBag->get('app.phones_ini'),
            true,
            INI_SCANNER_TYPED
        );
        // Create phones entities from $phones data, and persist them in database
        foreach ($phones as $value) {
            $phone = $this->phoneCreation($value);
            $manager->persist($phone);
        }
        
        $manager->flush();
    }
    
    /**
     * phoneCreation
     *
     * @param  array<mixed, mixed> $data
     * @return Phone
     */
    public function phoneCreation(array $data): Phone
    {
        $phone = new Phone();

        $screen = new Screen();
        $screen->hydrate(
            $data['screen_size'],
            $data['screen_technology'],
            $data['screen_definition'],
            $data['screen_resolution'],
            $data['screen_refresh_rate'],
            $phone
        );

        $size = new Size();
        $size->hydrate($data['size_width'], $data['size_height'], $data['size_thickness'], $phone);
        
        $phone->hydrate(
            $data['constructor'],
            $data['name'],
            $data['price_euro'],
            $data['system'],
            $data['user_interface'],
            $data['processor'],
            $data['ram'],
            $data['capacity'],
            $data['das'],
            $data['battery_capacity'],
            $data['wireless_charging'],
            $data['weight'],
            $size,
            $screen
        );     

        return $phone;
    }
}