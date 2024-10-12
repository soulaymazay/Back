<?php
// src/App/EventListener/JWTCreatedListener.php
namespace App\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
;

class JWTCreatedListener
{
/**
 * @var RequestStack
 */
private $requestStack;
/**
 * @var EntityManagerInterface
 */
private  $em;
/**
 * @var UserRepository
 */
private  $userRepository;


/**
 * @param RequestStack $requestStack
 */
public function __construct(RequestStack $requestStack,EntityManagerInterface $em, UserRepository $userRepository)
{
    $this->requestStack = $requestStack;
    $this->em=$em;
    $this->userRepository=$userRepository;
}

/**
 * @param JWTCreatedEvent $event
 *
 * @return void
 */
public function onJWTCreated(JWTCreatedEvent $event)
{
    $request = $this->requestStack->getCurrentRequest();


    $expiration = new \DateTime('+10 day');
    $expiration->setTime(2, 0, 0);

    $payload        = $event->getData();
    $user=$this->userRepository->findOneBy(['email' =>$payload['username']]);
    if(!$user)
    {
        $payload["id"]->setData(["-1"]);
return;
    }
    else{
        $payload["id"]=((string)$user->getId());
        $payload["name"]=((string)$user->getName());
    }
    $payload['exp'] = $expiration->getTimestamp();
      $payload['ip'] = $request->getClientIp();
    $event->setData($payload);

}
}
?>