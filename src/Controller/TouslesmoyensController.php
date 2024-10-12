<?php

namespace App\Controller;
use App\Service\FileUploader;

use App\Entity\Lesmoyens;
use App\Repository\ChauffRepository;
use App\Repository\LesmoyensRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/moyen", name="api_")
 */
class TouslesmoyensController extends AbstractController
{  public function __construct(private  $moyenDirectory)  {
}
    /**
     * @Route(name="create_moyen", methods={"POST"})
     */
    public function createMoyen(Request $request,FileUploader $fileUploader, EntityManagerInterface $em,ChauffRepository $chauffRepository, LesmoyensRepository $lesmoyensRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $image=null;
        if($request->files->all())
        $image=$request->files->all()['image'];
        $data=$request->request->all()["data"];
        $data = json_decode($data, true);

        if (!$data) {
            return $this->respondValidationError('Invalid JSON data.');
        }
        $lesmoyens = new Lesmoyens();
        $lesmoyens->setNom($data['nom']);
        $lesmoyens->setMarque($data['marque']);
        $lesmoyens->setModel($data['model']);
        $lesmoyens->setCouleur($data['couleur']);
        $lesmoyens->setAnnee($data['annee']);
        // $lesmoyens->setImage(stream_get_contents($strm));
        $userid=$data["userId"];
        $chauff = $chauffRepository->findOneBy(['user' => $userid]);
        $lesmoyens->setChauff($chauff);

        $lesmoyens->setEtat("pending");

        $em->persist($lesmoyens);

        $em->flush();
        if($image!=null)
        {     $filename= $fileUploader->upload($image,$this->moyenDirectory,$lesmoyens->getId());
            $lesmoyens->setImageFilename($filename);
            $em->persist($lesmoyens);
            $em->flush();
        }
        return $this->json($lesmoyensRepository->transform($lesmoyens));
    }
    /**
* @Route( name="list_moyens", methods={"GET"})
*/
public function list(lesmoyensRepository $lesmoyensRepository)
{
    $lesmoyens = $lesmoyensRepository->findAll();

foreach ($lesmoyens as $value) {
    $this->GetImage($value);

}
    return $this->json($lesmoyens);
}
/**
* @Route("/etat/{etat}", name="list_etatmoyens", methods={"GET"})
*/
public function getByEtat($etat,lesmoyensRepository $lesmoyensRepository)
{
    $lesmoyens = $lesmoyensRepository->findBy(['etat' => $etat]);

foreach ($lesmoyens as $value) {
    $this->GetImage($value);

}
    return $this->json($lesmoyens);
}
    /**
* @Route("/{id}/{etat}", name="setEtat", methods={"PATCH"})
*/
public function setEtat($id,$etat,Request $request,lesmoyensRepository $lesmoyensRepository)
{
    $lesmoyens = $lesmoyensRepository->findOneBy(['id' => $id]);

    if (!$lesmoyens) {
        return $this->json(['message' => 'moyen not found'], 404);
    }
    $lesmoyens->setEtat($etat);
    $updateLesmoyens = $lesmoyensRepository->updateLesmoyens($lesmoyens);

    return $this->json("updated");
}

/**
 * @Route("/{id}", name="update_moyen", methods={"PUT"})
 */
public function update($id, Request $request, LesmoyensRepository $lesmoyensRepository)
{
    $lesmoyens = $lesmoyensRepository->findOneBy(['id' => $id]);

    if (!$lesmoyens) {
        return $this->json(['message' => 'moyen not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    empty($data['nom']) ? true : $lesmoyens->setNom($data['nom']);
    empty($data['marque']) ? true : $lesmoyens->setMarque($data['marque']);
    empty($data['couleur']) ? true : $lesmoyens->setCouleur($data['couleur']);
    empty($data['annee']) ? true : $lesmoyens->setAnnee($data['annee']);
    empty($data['model']) ? true : $lesmoyens->setModel($data['model']);

    $updateLesmoyens = $lesmoyensRepository->updateLesmoyens($lesmoyens);
    $data = [
        'id' => $lesmoyens->getId(),
        'nom' => $lesmoyens->getNom(),
        'marque' => $lesmoyens->getMarque(),
        'couleur' => $lesmoyens->getCouleur(),
        'annee' => $lesmoyens->getAnnee(),
        'model' => $lesmoyens->getModel(),
    ];
    return $this->json($data);
    return $this->json($updateLesmoyens);
}
/**
 * @Route("/{id}", name="delete_moyen", methods={"DELETE"})
 */
public function delete($id, EntityManagerInterface $entityManager)
{
    $lesmoyens = $entityManager->getRepository(Lesmoyens::class)->findOneBy(['id' => $id]);
    $entityManager->remove($lesmoyens);
    $entityManager->flush();
    return new JsonResponse(['status' => 'moyen deleted']);
}

public function GetImage(Lesmoyens &$moyen)
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
    private function respondValidationError($message)
    {
        return new JsonResponse(['message' => $message, 'status' => 'error'], 400);
    }
}
