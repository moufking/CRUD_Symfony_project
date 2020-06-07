<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Student;
use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StrudentController extends AbstractController
{
    private $studentRepository;


    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * @Route("/",name="list_student")
     */
    public function index()
    {
        $all_student = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->render('student/index.html.twig',['all_student'=>$all_student]);
    }

    /**
     * @Route("/create",name="create_student", methods={"GET"})
     */
    public function create() {
        $all_department= $this->getDoctrine()->getRepository(Department::class)->findAll();
        return $this->render('student/create.html.twig',['all_department'=>$all_department]);
    }

    /**
     * @Route("/create",name="store_student", methods={"POST"})
     */
    public function store(Request $request) {
        $firstname = trim($request->request->get('Firstname'));
        $lastname = trim($request->request->get('Lastname'));
        $number = trim($request->request->get('num_etud'));
        $department_id = trim($request->request->get('depatment_id'));
        if(empty($firstname) && empty($lastname )  && empty($number) && empty($department_id) )
                return $this->redirectToRoute('list_student');

        $entityManager = $this->getDoctrine()->getManager();

        $student =  new Student();
        $student->setFirstname($firstname);
        $student->setLastname($lastname);
        $student->setNumEtud($number);
        $department =  $entityManager->getRepository(Department::class)->find($department_id);
        $student->setDepartment($department);
        $entityManager->persist($student);

        $entityManager->flush();


        //return $this->redirectToRoute('list_student');

        return $this->redirectToRoute('list_student');
        //exit($request->request->get('Firstname'));
    }

    /**
     * @Route("/show/{id}",name="show_student", methods={"GET"})
     */
    public function show($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $student =  $entityManager->getRepository(Student::class)->find($id);

        return $this->render('student/show.html.twig',['student'=>$student]);
    }

    /**
     * @Route("/delete/{id}",name="delete_student", methods={"GET"})
     */
    public function delete($id) {

        $entityManager = $this->getDoctrine()->getManager();
        $student =  $entityManager->getRepository(Student::class)->find($id);

        if(!$student)  {
            return $this->redirectToRoute('list_student');
        }

        $entityManager->remove($student);
        $entityManager->flush();
        return $this->redirectToRoute('list_student');
    }


    /**
     * @Route("/update/{id}",name="update_student", methods={"GET"})
     */
    public function update($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $student =  $entityManager->getRepository(Student::class)->find($id);
        $all_department= $this->getDoctrine()->getRepository(Department::class)->findAll();


        return $this->render('student/update.html.twig',['student'=>$student,'all_department'=>$all_department]);
    }

    /**
     * @Route("/edit/{id}",name="edit_student", methods={"POST"})
     */

    public function  edit($id, Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $student =  $entityManager->getRepository(Student::class)->find($id);
        $firstname = trim($request->request->get('Firstname'));
        $lastname = trim($request->request->get('Lastname'));
        $number = trim($request->request->get('num_etud'));
        $department_id = trim($request->request->get('depatment_id'));
        $student->setFirstname($firstname);
        $student->setLastname($lastname);
        $student->setNumEtud($number);
        $department =  $entityManager->getRepository(Department::class)->find($department_id);
        $student->setDepartment($department);

        $entityManager->flush();
        return $this->redirectToRoute('list_student');
    }
}
