<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Classroom;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ClassroomFormType;
use App\Repository\ClassroomRepository;


class ClassroomController extends AbstractController
{
    #[Route('/classroomList', name: 'app_classroom')]
    public function ListeClassroom(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Classroom::class);
        $classrooms = $repo->findAll();
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
            'classrooms'=>$classrooms
        ]);
    }
    #[Route('/DeleteClassroom/{id}', name: 'delete_classroom')]
    public function deleteClassroom($id,ManagerRegistry $doctrine){
        $classroom=$doctrine->getRepository(Classroom::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($classroom);
        $em->flush();
        return $this->redirectToRoute('app_classroom');
    }
    #[Route('/addClassroom', name: 'add_classroom')]
    public function addClassroom(Request $request, EntityManagerInterface $entityManager): Response
    {
        $classroom = new Classroom();
        $form = $this->createForm(ClassroomFormType::class, $classroom);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($classroom);
            $entityManager->flush();
            return $this->redirectToRoute('app_classroom');
        }
        return $this->render('classroom/ajouter.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/editClassroom/{id}', name: 'edit_classroom')]
    public function editClassroom($id,Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $classroom =  $doctrine->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(ClassroomFormType::class, $classroom);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('app_classroom');
        }
        return $this->render('classroom/modifier.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
