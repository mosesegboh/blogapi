<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\VerificationRequest;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;

#[AsController]
class VerificationRequestUpdate extends  AbstractController
{
    public function __invoke(  ManagerRegistry $doctrine,$id ,Request $request): VerificationRequest
    {
        $entityManager = $doctrine->getManager();
        $verificationRequest = $entityManager->getRepository(VerificationRequest::class)->findOneById($request->get('id'));

        if($verificationRequest->getStatus() !== 'Verification requested') {
            throw $this->createNotFoundException(
                'You cannot edit your request at this point'
            );
        }
        return $verificationRequest;
    }
}