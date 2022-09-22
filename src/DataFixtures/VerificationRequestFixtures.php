<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\VerificationRequest;
use App\Helpers\Helper;

class VerificationRequestFixtures extends Fixture
{
    /**
     * loading Verification Request data fixtures for symfony test
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
         $helper = new Helper();
         $verificationRequest = new VerificationRequest();
         $verificationRequest->setMessage($helper->generateRandomString(40));
         $verificationRequest->setStatus($helper->randomValue('status'));
         $verificationRequest->setUser($helper->persistUser());
         $verificationRequest->setImagePath($helper->randomImage());
         $verificationRequest->setCreatedAt(new \DateTime());
         $verificationRequest->setUpdatedAt(new \DateTime());
         $verificationRequest->setDecisionReason($helper->generateRandomString(10));
         $manager->persist($verificationRequest);

        $manager->flush();
    }
}
