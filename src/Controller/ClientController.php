<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Service\FileUploader;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use Psr\Log\LoggerInterface;

/**
 * @Route("/api/client", name="api_")
 */

class ClientController extends AbstractController
// { private $logger;
    {
        public function __construct(private  $clientDirectory)  {
        }
    /**
     * @Route(name="create_client", methods={"POST"})
     */
    public function createclient(Request $request,FileUploader $fileUploader, EntityManagerInterface $em,UserRepository $userRepository, ClientRepository $clientRepository, UserPasswordEncoderInterface $encoder): Response

 {
    $image=null;
    if($request->files->all())
    $image=$request->files->all()['image'];
    $data=$request->request->all()["data"];
    $data = $this->transformJsonBody($data);

 
    if (!$data) {
        return $this->json('invalid data',400);

    }
    if ($data['email']==null) 

        return $this->json('invalid email',400);

    $userWithSameEmail = $userRepository->findOneBy(['email' =>$data['email']]);
    if($userWithSameEmail)
    {
        return $this->json('email exists',400);

    }


    $client = new Client;
    $user=new User;
    $user->setName($data['username']);
    $user->setEmail($data['email']);
    $user->setPassword($data['password']);
    $user->setRoles(["ROLE_CLIENT"]);
    $client->setEtat("actif");
 
    $hash=$encoder->encodePassword($user, $user->getPassword());
    $user->setPassword($hash);
    $client->setUser($user);
    $em->persist($user);

    $em->persist($client);
    $em->flush();
    if($image!=null)
    {    
        $filename= $fileUploader->upload($image,$this->clientDirectory,$user->getId());
        $user->setImageFilename($filename);
                $em->persist($user);
        $em->flush();
    }
    
    
    return $this->json($clientRepository->transform($client));
}
/**
* @Route(name="list_client", methods={"GET"})
*/
public function list(clientRepository $clientRepository)
{
    $client = $clientRepository->findAll();

    foreach ($client as $value) {
        $this->GetImage($value);

    }
    return $this->json($client);
}
/**
 * @Route("/{id}", name="update_client", methods={"PUT"})
 */
public function update($id, Request $request, ClientRepository $clientRepository)
{
    /** @var Client $client **/
     $client = $clientRepository->findOneBy(['user' => $id]);
    if (!$client) {
        return $this->json(['message' => 'Client not found'], 404);
    }
    $data = json_decode($request->getContent(), true);

    empty($data['etat']) ? true : $client->setEtat($data['etat']);


    $updateClient = $clientRepository->updateClient($client);
    $data = [
        'id' => $client->getId(),
        'etat' => $client->getEtat(),
    
    ];
    return $this->json($updateClient);
}
/**
 * @Route("/{id}", name="delete_client", methods={"DELETE"})
 */
public function delete($id, EntityManagerInterface $entityManager)
{
    $client = $entityManager->getRepository(client::class)->findOneBy(['user' => $id]);
    $entityManager->remove($client);
    $entityManager->flush();
    return new JsonResponse(['status' => 'client deleted']);
}


private function transformJsonBody(string $request)
{
    $data = json_decode($request, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return null;
    }

    if ($data === null) {
        return $request;
    }

    $request=$data;

    return $request;
}
public function GetImage(Client &$client)
{
    if($client->getUser()->getImageFilename()!=null) {
        $path=$this->clientDirectory.'/'.$client->getUser()->getImageFilename();
  

        $content=file_get_contents($path);
        if($content ===false)
        {
            $path=$this->clientDirectory.'/'.$client->getUser()->getId().'.'.'png';
            $content=file_get_contents($path);

        }
        $content=base64_encode($content);     

        $client->getUser()->setImage($content);
    }
}


    private function respondValidationError($message)
    {
        return new JsonResponse(['message' => $message, 'status' => 'error'], 400);
    }
}
