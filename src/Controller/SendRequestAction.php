<?php

namespace App\Controller;

use App\Entity\VerificationRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class SendRequestAction extends AbstractController
{
    public function __invoke(Request $request): VerificationRequest
    {
        $uploadedFile = $request->files->get('image');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"image" is required');
        }

        $mediaObject = new VerificationRequest();
        $mediaObject->image = $uploadedFile;

        return $mediaObject;
    }
}