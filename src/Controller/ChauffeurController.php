<?php

namespace App\Controller;

use App\Entity\Chauff;
use App\Entity\Lesmoyens;
use App\Entity\User;
use App\Repository\ChauffRepository;
use App\Repository\LesmoyensRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/chauffeur", name="api_")
 */
class ChauffeurController extends AbstractController
{   
    public function __construct(private  $chauffeurDirectory,private $moyenDirectory)  {
    }
    /**
     * @Route(name="create_chauffeur", methods={"POST"})
     */
    public function createChauffeur(Request $request,
    EntityManagerInterface $em,UserRepository $userRepository, FileUploader $fileUploader,
     ChauffRepository $chauffRepository, UserPasswordEncoderInterface $encoder): Response
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

    $user=new User;

    $chauff = new Chauff;
    $chauff->setNumpermis($data['numpermis']);
    $user->setName($data['username']);
    $user->setEmail($data['email']);
    $user->setPassword($data['password']);
    $user->setRoles(["ROLE_CHAUFFEUR"]);
    $chauff->setEtat("offline");
    $chauff->setEtatcompte("pending");

    $hash=$encoder->encodePassword($user,$user->getPassword());
    $user->setPassword($hash);
    $chauff->setUser($user);
    $em->persist($user);

    $em->persist($chauff);
    $em->flush();
    if($image!=null)
    {
   $filename= $fileUploader->upload($image,$this->chauffeurDirectory,$user->getId());
      $user->setImageFilename($filename);
    $em->persist($user);
    $em->flush();
}

    return $this->json($chauffRepository->transform($chauff));
}
/**
* @Route(name="list_chauffeurs", methods={"GET"})
*/
public function list(chauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->findAll();

    foreach ($chauff as $value) {
    $this->GetImage($value);
       
    }
    return $this->json($chauff);
}
    /**
* @Route("/getmoyen/{id}", name="get_moyen_one", methods={"GET"})
*/
public function getMoyen($id,Request $request,LesmoyensRepository $moyenRepository,ChauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->findOneBy(["user"=>$id]);
    $chauffid=$chauff->getId();
    $lesmoyens = $moyenRepository->findBy(["chauff"=>$chauffid]);
if($lesmoyens!=null)
foreach ($lesmoyens as $value) {
    $this->GetMoyenImage($value);

}
  return $this->json($lesmoyens);
}

public function GetMoyenImage(Lesmoyens &$moyen)
{
    if($moyen->getImageFilename()!=null) {
        $path=$this->moyenDirectory.'/'.$moyen->getImageFilename();
  

        $content=file_get_contents($path);
        if($content ===false)
        {
            $path=$this->moyenDirectory.'/'.$moyen->getId().'.'.'png';
            $content=file_get_contents($path);

        }
        $content=base64_encode($content);     

        $moyen->setImage($content);
    }
}
    /**
* @Route("/getmoyenaccepted/{id}", name="get_moyen_accepted_idchauff", methods={"GET"})
*/
public function getMoyenAccepted($id,Request $request,LesmoyensRepository $moyenRepository,ChauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->findOneBy(["user"=>$id]);
    if($chauff==null)
    return null;
    $chauffid=$chauff->getId();
    $lesmoyens = $moyenRepository->findBy(["chauff"=>$chauffid,"etat"=>"Accepted"]);
    if($lesmoyens!=null)
foreach ($lesmoyens as $value) {
    $this->GetMoyenImage($value);

} 
  return $this->json($lesmoyens);
}

    /**
* @Route("/etatcompterejected/{id}", name="get_chauffeur_byid_rejected", methods={"GET"})
*/
public function getChauffeurByIdAndEtatCompteRejected($id,Request $request,ChauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->findOneBy(['user' => $id,"etatcompte"=>"rejected"]);
    if($chauff!=null)
    $this->GetImage($chauff);

    return $this->json($chauff);
}
    /**
* @Route("/etatcompteaccepted/{id}", name="get_chauffeurs_accepted", methods={"GET"})
*/
public function getChauffeurByIdAndEtatCompteAccepted($id,Request $request,ChauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->findOneBy(['user' => $id,"etatcompte"=>"accepted"]);
    if($chauff!=null)
    $this->GetImage($chauff);

    return $this->json($chauff);
}
/**
 * @Route("/{id}", name="update_chauffeur", methods={"PUT"})
 */
public function update($id, Request $request, ChauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->findOneBy(['user' => $id]);

    if (!$chauff) {
        return $this->json(['message' => 'Chauffeur not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    empty($data['numpermis']) ? true : $chauff->setNumpermis($data['numpermis']);
   
    empty($data['etat']) ? true : $chauff->setEtat($data['etat']);
    empty($data['etatcompte']) ? true : $chauff->setEtatCompte($data['etatcompte']);

    $updateChauff = $chauffRepository->updateChauff($chauff);

    return $this->json("updated");
}


/**
* @Route("/getby/etat/{etat}", name="list_chauffeurs_byetat", methods={"GET"})
*/
public function getByEtat($etat,ChauffRepository $chauffRepository)
{
    $lesmoyens = $chauffRepository->findBy(['etat' => $etat]);

foreach ($lesmoyens as $value) {
    $this->GetImage($value);

}
    return $this->json($lesmoyens);
}

/**
* @Route("/getby/etatcompte/{etat}", name="list_chauffeurs_byetatcompte", methods={"GET"})
*/
public function getByEtatcompte($etat,ChauffRepository $chauffRepository)
{
    $chauffeurs = $chauffRepository->findBy(['etatcompte' => $etat]);
if($chauffeurs!=null)
foreach ($chauffeurs as $value) {
    $this->GetImage($value);

}
    return $this->json($chauffeurs);
}
/**
 * @Route("/getonlineandacceptedchauffeurs", name="list_chauffeurs_accepted_online", methods={"GET"})
 */
 public function getonlineandacceptedchauffeurs(ChauffRepository $chauffRepository)
 {
     $chauffeurs = $chauffRepository->findBy(['etatcompte' => 'accepted','etat'=>'online']);
 if($chauffeurs!=null)
 foreach ($chauffeurs as $value) {
    $this->GetImage($value);

 }
     return $this->json($chauffeurs);
 }
 

/**
 * @Route("/{id}", name="delete_chauffeur", methods={"DELETE"})
 */
public function delete($id, EntityManagerInterface $entityManager)
{
    $chauff = $entityManager->getRepository(Chauff::class)->findOneBy(['user' => $id]);
    $entityManager->remove($chauff);
    $entityManager->flush();
    return new JsonResponse(['status' => 'chauffeur deleted']);
}

    /**
* @Route("/{id}", name="get_one_by_idchauffeur", methods={"GET"})
*/
public function getChauffeurById($id,Request $request,ChauffRepository $chauffRepository)
{
    $chauff = $chauffRepository->findOneBy(['user' => $id]);
    $content=null;
    $this->GetImage($chauff);

    return $this->json($chauff);
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
public function GetImage(Chauff &$chauffeur)
{
    if($chauffeur->getUser()->getImageFilename()!=null) {
        $path=$this->chauffeurDirectory.'/'.$chauffeur->getUser()->getImageFilename();
  

        $content=file_get_contents($path);
        if($content ===false)
        {
            $path=$this->chauffeurDirectory.'/'.$chauffeur->getUser()->getId().'.'.'png';
            $content=file_get_contents($path);

        }
        $content=base64_encode($content);     

        $chauffeur->getUser()->setImage($content);
    }
}
}
