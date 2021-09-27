<?php

namespace App\Controller;

use App\Controller\AbstractController;
use App\Entity\Account;
use DateTimeZone;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * @Route("/service/v1/oauth2", name="oauth2_")
 */
class Oauth2Controller extends AbstractController
{
    
}
