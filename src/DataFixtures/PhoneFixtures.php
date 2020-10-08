<?php

namespace App\DataFixtures;

use App\Entity\Phone;
use App\Entity\Screen;
use App\Entity\Size;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * GuestFixtures.
 *
 * Fixture class to load 10 phones in database
 */
class PhoneFixtures extends Fixture
{
    /**
     * parameterBag.
     *
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    /**
     * __construct.
     *
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * load data fixtures (10 phones) with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        // Recovery in $phones initial data list of 10 phones, contained in the phones.ini file at the root of the application.
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
     * Create a phone entity from the $data array.
     *
     * @param array<mixed, mixed> $data
     *
     * @return Phone
     */
    public function phoneCreation(array $data): Phone
    {
        $phone = new Phone();

        $screen = new Screen();
        $screen->hydrate($data, $phone);

        $size = new Size();
        $size->hydrate($data, $phone);

        $phone->hydrate($data, $size, $screen);

        return $phone;
    }
}
