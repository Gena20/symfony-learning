<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppIUserFixture extends Fixture
{
    public const DEFAULT_EMAIL = 'user@example.com';
    public const DEFAULT_PASSWORD = 'example-password';
    public const AMOUNT_OF_USERS = 10;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::AMOUNT_OF_USERS; ++$i) {
            $user = (new User())
                ->setEmail(self::DEFAULT_EMAIL . $i)
                ->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, self::DEFAULT_PASSWORD));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
