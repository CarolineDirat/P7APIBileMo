<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * GuestFixtures.
 *
 * Fixture class to load in database 1 client and his 10 user linked by
 */
class GuestFixtures extends Fixture
{
    /**
     * encoder.
     *
     * @var UserPasswordEncoderInterface password encoder
     */
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * load data fixtures (1 client and his 10 linked users and 1 client without users ) with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        // create first client
        $client = new Client();
        $client->setUsername('FirstClient');
        $client->setPassword($this->encoder->encodePassword($client, 'password'));
        $client->setUuid(Uuid::uuid4());
        $client->setEmail('first.client@mail.com');

        $manager->persist($client);

        // create users linked by the client
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setUuid(Uuid::uuid4());
            $user->setEmail(sprintf('user%d@mail.com', $i));
            $user->setPassword(sha1($user->getUuid()));
            $user->setFirstname(sprintf('firstname%d', $i));
            $user->setLastname(sprintf('lastname%d', $i));
            $user->setClient($client);

            $manager->persist($user);
        }

        // create a second client, without users
        $client = new Client();
        $client->setUsername('SecondClient');
        $client->setPassword($this->encoder->encodePassword($client, 'password'));
        $client->setUuid(Uuid::uuid4());
        $client->setEmail('second.client@mail.com');

        $manager->persist($client);

        
        $manager->flush();
    }
}
