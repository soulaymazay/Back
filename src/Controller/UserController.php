<?php

namespace App\Controller;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/api/user", name="api_")
 */
class UserController extends AbstractController
{    public function __construct(private  $clientDirectory,
    private  $chauffeurDirectory,private  $moyenDirectory)  {
}
       /**
* @Route("/getimage/{id}", name="getimage", methods={"GET"})
*/
public function getimage($id,Request $request,UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        $roles=$user->getRoles();
        $imageDirectory="";
        if (in_array("ROLE_CHAUFFEUR", $roles))
            {
            $imageDirectory=$this->chauffeurDirectory;
            }
           else if (in_array("ROLE_CLIENT", $roles))
            {
            $imageDirectory=$this->clientDirectory;
            }
        
        if($user->getImageFilename()!=null) {
            $path=$imageDirectory.'/'.$user->getImageFilename();
      
    
            $content=file_get_contents($path);
            if($content ===false)
            {
                $path=$this->clientDirectory.'/'.$user->getId().'.'.'png';
                $content=file_get_contents($path);
            }
            $content=base64_encode($content);     
            $user->setImage($content);
        return $this->json($content);
    }

}

}
