<?php

namespace App\Controller;

use App\Entity\VerificationRequest;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Doctrine\Persistence\ManagerRegistry;
use App\Helpers\Helper;

#[AsController]
final class VerificationRequestDecision extends AbstractController
{
    public function __invoke(  ManagerRegistry $doctrine,$id ,Request $request)
    {
        $helper = new Helper();
        $entityManager = $doctrine->getManager();
        $verificationRequest = $entityManager->getRepository(VerificationRequest::class)->findOneById($request->get('id'));

        if (!$verificationRequest) {
            throw $this->createNotFoundException(
                'No Request found for user with id '.$request->get('id')
            );
        }

        if (intval($request->get('decision')) == $helper::APPROVE) {
            $_SESSION['DECISION'] = 1;
            $verificationRequest->setStatus('Approved');

            //set the user role
            $user = $entityManager->getRepository(User::class)->findOneById($verificationRequest->getUser()->getId());
            $user->setRoles(array('ROLE_BLOGGER'));
        }

        if (intval($request->get('decision')) == $helper::DECLINE) {
            $_SESSION['DECISION'] = 2;
            $verificationRequest->setStatus('Declined');
            $user = $entityManager->getRepository(User::class)->findOneById($verificationRequest->getUser()->getId());

            //remove the user role if it exists
            if ($user->getRoles() !== []) {
                $user->setRoles(array('ROLE_USER'));
            }
        }

        if ($request->get('rejection_message')) {
            $verificationRequest->setDecisionReason($request->get('rejection_message'));
        }

        if (intval($request->get('decision')) !== $helper::APPROVE && intval($request->get('decision')) !== $helper::DECLINE) {
            throw $this->createNotFoundException(
                'wrong parameter entered with decision id '. $request->get('decision')
            );
        }

        $entityManager->persist($user);
        $entityManager->persist($verificationRequest);
        $entityManager->flush();

        return $verificationRequest;
    }
}