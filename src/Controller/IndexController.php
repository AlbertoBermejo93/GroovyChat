<?php

namespace App\Controller;

use DateTimeImmutable;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index()
    {
        $username = $this->getUser()->getUsername();
        // $token = (new Builder(new JoseEncoder(), ChainedFormatter::default()))
        //     ->withClaim('mercure', ['subscribe' => [sprintf("/%s", $username)]])
        //     ->getToken(
        //         new Sha256(),
        //         new Key($this->getParameter('mercure_jwt_secret'))
        //     )
        // ;

        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm    = new Sha256();
        $signingKey   = InMemory::plainText(random_bytes(32));

        $now   = new DateTimeImmutable();
        $token = $tokenBuilder
            // Configures the issuer (iss claim)
        
            // Configures the audience (aud claim)
       
            // Configures the id (jti claim)
     
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the time that the token can be used (nbf claim)
            ->canOnlyBeUsedAfter($now->modify('+1 minute'))
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+1 hour'))
            // Configures a new claim, called "uid"
            ->withClaim('mercure', ['subscribe' => [sprintf("/%s", $username)]])
            // Configures a new header, called "foo"
            ->withHeader('foo', 'bar')
            // Builds a new token
            ->getToken($algorithm, $signingKey);

        // echo $token->toString();


        $response =  $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);

        $response->headers->setCookie(
            new Cookie(
                'mercureAuthorization',
                $token->toString(),
                (new \DateTime())
                ->add(new \DateInterval('PT2H')),
                '/.well-known/mercure',
                null,
                false,
                true,
                false,
                'strict'
            )
        );

        return $response;
    }
}
