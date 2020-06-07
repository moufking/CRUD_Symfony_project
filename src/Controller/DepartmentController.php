<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Student;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/department", name="list_department")
     */
    public function index()
    {
        $all_department = $this->getDoctrine()->getRepository(Department::class)->findAll();
        return $this->render('department/index.html.twig',['all_department'=>$all_department]);
    }

    /**
     * @Route("/department/create", name="create_department")
     */
    public function create()
    {
        return $this->render('department/create.html.twig');
    }

    /**
     * @Route("/department/store",name="store_department", methods={"POST"})
     */
    public function store(Request $request) {
        $name = trim($request->request->get('name'));
        $capacity = trim($request->request->get('capacity'));
        if(empty($name))
            return $this->redirectToRoute('list_department');

        $entityManager = $this->getDoctrine()->getManager();

        $department =  new Department();
        $department->setName($name);
        $department->setCapacity($capacity);

        $entityManager->persist($department);

        $entityManager->flush();


        return $this->redirectToRoute('list_department');

    }

    /**
     * @Route("/departement/show/{id}",name="show_department", methods={"GET"})
     */
    public function show($id):JsonResponse {
        $department =   $this->getDoctrine()
                        ->getRepository(Department::class)
                        ->find($id);

        $data= [];

        if(empty($department)) {
            return new JsonResponse('Student Not found');
        }
        $all_student = $department->getStudents();
        foreach ($all_student as $student) {


            $data[] = [
                'id' =>$student->getId(),
                'firstname' =>$student->getFirstname(),
                'lastname' =>$student->getLastname(),
                'numEtud' =>$student->getNumEtud(),
            ];
        }

        return new JsonResponse($data);

       //return $this->render('department/show.html.twig',['all_student'=>$all_student]);
    }
}
