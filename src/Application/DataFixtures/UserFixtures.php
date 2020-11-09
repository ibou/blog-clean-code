<?php

namespace App\Application\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    private UserPasswordEncoderInterface $encoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail(sprintf("email%d@gmail.com", $i));
            $user->setPseudo(sprintf("pseudo%d", $i));
            $user->setPassword($this->encoder->encodePassword($user, "dev"));
            $manager->persist($user);
            $this->setReference(sprintf("user-%d", $i), $user);
        }
        $manager->flush();
    }

}
