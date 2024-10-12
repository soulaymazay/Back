<?php

namespace App\Controller;


use App\Entity\Course;
use App\Entity\Chauff;
use App\Entity\User;
use App\Entity\Lesmoyens;
use App\Entity\Client;
use App\Repository\CourseRepository;
use App\Repository\ChauffRepository;
use App\Repository\ClientRepository;
use App\Repository\LesmoyensRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\LessThan;

/**
 * @Route("/api/course", name="api_")
 */
class CourseController extends AbstractController
{
    /**
     * @Route(name="create_course", methods={"POST"})
     */
    public function createMoyen(Request $request, EntityManagerInterface $em, CourseRepository $courseRepository, UserPasswordEncoderInterface $encoder): Response
    {
       
        $data = json_decode($request->getContent(), true);

    if (!$data) {
        return $this->json('invalid data',400);
    }
        if ($data['client']==null && $data['client']>0) 
        return $this->json('invalid client id',400);
        if ($data['chauffeur']==null && $data['chauffeur']>0) 
        return $this->json('invalid chauffeur id',400);
        if ($data['moyen']==null && $data['moyen']>0) 
        return $this->json('invalid moyen id',400);
        if ($data['destinationGPS']==null && $data['destinationGPS']>0) 
        return $this->json('invalid destinationGPS',400);
        if ($data['positionGPS']==null && $data['positionGPS']>0) 
        return $this->json('invalid positionGPS',400);


        $course = new Course();
         $chauffeur=$em->getReference(User::class,$data['chauffeur']);
        $course->setChauffeur($chauffeur->getId());
        $client=$em->getReference(User::class,$data['client']);
        $course->setClient($client->getId());
        $moyen=$em->getReference(Lesmoyens::class,$data['moyen']);
        $course->setMoyen($moyen->getId());
        $course->setDestinationGPS($data['destinationGPS']);
        $course->setPositionGPS($data['positionGPS']);
        $course->setEtat("pending");
        $course->setInputposition($data['inputposition']);
        $course->setInputdestination($data['inputdestination']);
        $course->setStartDateTime(new \DateTime());
        $em->persist($course);      
        $em->flush();
      //  $course->setChauffeur();
      return $this->json($course,200);
    }
    /**
* @Route( name="list_course", methods={"GET"})
*/
public function list(courseRepository $courseRepository,ChauffRepository $chauffRepository,LesmoyensRepository $lesmoyensRepository,ClientRepository $clientRepository)
{
    $courses = $courseRepository->findAll();

    foreach ($courses as $course) {

        $chauffeurname=$chauffRepository->findOneBy(['user' => $course->getChauffeur()])->getUser()->getName();
        $moyen=$lesmoyensRepository->findOneBy(['id' => $course->getMoyen()]);
        $moyenname=$moyen->getNom()." ".$moyen->getModel();
        $course->chauffeurName=$chauffeurname;
        if($course->getClient()!=-1)
        $course->clientName=$clientRepository->findOneBy(['user' => $course->getClient()])->getUser()->getName();
        else $course->clientName="Client supprimé";
        $course->moyenName=$moyenname;
    }
    return $this->json($courses);
}
/**
* @Route("/etat/{etat}", name="list_etatcourses", methods={"GET"})
*/
public function getByEtat($etat,courseRepository $courseRepository,ChauffRepository $chauffRepository,LesmoyensRepository $lesmoyensRepository,ClientRepository $clientRepository)
{
    $courses = $courseRepository->findBy(['etat' => $etat]);

    foreach ($courses as $course) {

        $chauffeurname=$chauffRepository->findOneBy(['user' => $course->getChauffeur()])->getUser()->getName();
        $moyen=$lesmoyensRepository->findOneBy(['id' => $course->getMoyen()]);
        $moyenname=$moyen->getNom()." ".$moyen->getModel();
        $course->chauffeurName=$chauffeurname;
        $course->clientName=$clientRepository->findOneBy(['user' => $course->getClient()])->getUser()->getName();
        $course->moyenName=$moyenname;
    }
    return $this->json($courses);
}
/**
* @Route("/client/{id}", name="listcourseclientid", methods={"GET"})
*/
public function getByClientid($id,courseRepository $courseRepository,ChauffRepository $chauffRepository,LesmoyensRepository $lesmoyensRepository,ClientRepository $clientRepository)
   {
    $courses = $courseRepository->findBy(['client' => $id]);

    foreach ($courses as $course) {

        $chauffeurname=$chauffRepository->findOneBy(['user' => $course->getChauffeur()])->getUser()->getName();
        $moyen=$lesmoyensRepository->findOneBy(['id' => $course->getMoyen()]);
        $moyenname=$moyen->getNom()." ".$moyen->getModel();
        $course->chauffeurName=$chauffeurname;
        $course->clientName=$clientRepository->findOneBy(['user' => $course->getClient()])->getUser()->getName();
        $course->moyenName=$moyenname;
    }
    return $this->json($courses);
}
/**
* @Route("/chauffeur/{id}", name="getByChauffeurid", methods={"GET"})
*/
public function getByChauffeurid($id,courseRepository $courseRepository,ChauffRepository $chauffRepository,LesmoyensRepository $lesmoyensRepository,ClientRepository $clientRepository)
{
    $courses = $courseRepository->findBy(['Chauffeur' => $id]);

    foreach ($courses as $course) {

        $chauffeurname=$chauffRepository->findOneBy(['user' => $course->getChauffeur()])->getUser()->getName();
        $moyen=$lesmoyensRepository->findOneBy(['id' => $course->getMoyen()]);
        $moyenname=$moyen->getNom()." ".$moyen->getModel();
        $course->chauffeurName=$chauffeurname;
        $course->clientName=$clientRepository->findOneBy(['user' => $course->getClient()])->getUser()->getName();
        $course->moyenName=$moyenname;
    }
    return $this->json($courses);
}
/**
* @Route("/moyen/{id}", name="getByChauffeurid", methods={"GET"})
*/
public function getBymoyenid($id,courseRepository $courseRepository,ChauffRepository $chauffRepository,LesmoyensRepository $lesmoyensRepository,ClientRepository $clientRepository)
{
    $courses = $courseRepository->findBy(['moyen' => $id]);

    foreach ($courses as $course) {

        $chauffeurname=$chauffRepository->findOneBy(['user' => $course->getChauffeur()])->getUser()->getName();
        $moyen=$lesmoyensRepository->findOneBy(['id' => $course->getMoyen()]);
        $moyenname=$moyen->getNom()." ".$moyen->getModel();
        $course->chauffeurName=$chauffeurname;
        $course->clientName=$clientRepository->findOneBy(['user' => $course->getClient()])->getUser()->getName();
        $course->moyenName=$moyenname;
    }
    return $this->json($courses);
}
    /**
* @Route("/etat/{id}/{etat}", name="setEtatcourses", methods={"PATCH"})
*/
public function setEtat($id,$etat,Request $request,courseRepository $courseRepository)
{
    $course = $courseRepository->findOneBy(['id' => $id]);

    if (!$course) {
        return $this->json(['message' => 'course not found'], 404);
    }
    $course->setEtat($etat);
    if($etat=="terminé")
    {
        $course->setFinishDateTime(new \DateTime());
    }
    $updateCourse = $courseRepository->save($course,true);

    return $this->json("updated");
}

/**
* @Route("/{id}", name="get_one_by_id", methods={"GET"})
*/
public function getOneById($id,Request $request,courseRepository $courseRepository)
{
    $course = $courseRepository->findOneBy(['id' => $id]);

    if (!$course) {
        return $this->json(['message' => 'course not found'], 404);
    }
    return $this->json($course);
}


/**
 * @Route("/avis/{id}", name="update_courseavis", methods={"PATCH"})
 */
public function setAvis($id, Request $request, CourseRepository $courseRepository)
{
    $course = $courseRepository->findOneBy(['id' => $id]);

    if (!$course) {
        return $this->json(['message' => 'moyen not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    empty($data['avis']) ? true : $course->setAvis($data['avis']);
    $courseRepository->save($course,true);
    return $this->json($course);
}

    private function respondValidationError($message)
    {
        return new JsonResponse(['message' => $message, 'status' => 'error'], 400);
    }
}



