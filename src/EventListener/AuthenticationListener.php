<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticationListener
{
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event): void
    {
        $response = new JWTAuthenticationFailureResponse(
            'Invalid email or password.',
            JsonResponse::HTTP_UNAUTHORIZED
        );
        $event->setResponse($response);
    }

    public function onJWTInvalid(JWTInvalidEvent $event)
    {
        $response = new JWTAuthenticationFailureResponse(
            'Invalid token, login to get a new one.',
            JsonResponse::HTTP_UNAUTHORIZED
        );
        $event->setResponse($response);
    }

    public function onJWTNotFound(JWTNotFoundEvent $event)
    {
        $data = [
            'status' => 403,
            'message' => 'You are not authenticated, please login.',
        ];
        $response = new JsonResponse(
            $data,
            403
        );
        $event->setResponse($response);
    }

    public function onJWTExpired(JWTExpiredEvent $event)
    {
        $data = [
            'status' => 403,
            'message' => 'Your authorization expired, please login.',
        ];
        $response = new JsonResponse(
            $data,
            JsonResponse::HTTP_UNAUTHORIZED
        );
        $event->setResponse($response);
    }
}