<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\User;
use App\Entity\Department;
use App\Entity\Course;
use App\Entity\Program;
use App\Entity\Evaluation;

class ProgramController extends AbstractController
{

            /**
             * @Route("/evaluate/program/action/take", name="program_label")
             */
 public function programAction(Request $request): Response
    {
            $user=$this->getUser();
         if($user != null){
            $em=$this->getDoctrine()->getManager();
            $program = new Program();
            $pname=$request->request->get('pname');
            $pshort=$request->request->get('pshort');
            $pcode=$request->request->get('pcode');
            $pcredit=$request->request->get('pcredit');
            $semester=$request->request->get('semester');
            $p=$this->getDoctrine()->getRepository(Program::class)->findOneBy(['pname'=>$pname]);


            if($request->isMethod('post'))
            {
              if(!empty($pname) & !empty($pshort) & !empty($pcode) & !empty($pcredit) & !empty($semester))
               {
               if(!$p)
                 {
                    $program->setPname($pname);
                    $program->setPshort($pshort);
                    $program->setPcode($pcode);
                    $program->setPcredit($pcredit);
                    $program->setSemester($semester);
                    $em->persist($program);
                    $em->flush();
                    return $this->redirectToRoute('program_moduli');
                    }
                 $this->addFlash('success','programe already exists');
                 return $this->render('college/programadd.html.twig');
                }
               else
                {
                 $this->addFlash('success','All field are required');
               return $this->render('college/programadd.html.twig');
                }
            }
                return $this->render('college/programadd.html.twig');
         }
           else
            {
                    return $this->redirectToRoute('app_login');
          }
    }

     /**
     * @Route("/evaluate/admin/program/available", name="program_moduli")
     */
    public function programView()
    {
        $user=$this->getUser();
        if($user != null){
        $em=$this->getDoctrine()->getManager();
        $program=$this->getDoctrine()->getRepository(Program::class)->findAll();
        return $this->render('college/program.html.twig',['program'=>$program]);
        }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }


 /**
     * @Route("/user/profile/area/update/info/detail",name="my_profile")
     */
public function editprofile(Request $request)
{
        $user=$this->getUser();
        if($user != null){

        $id=$user->getId();
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($user4)

        ->add('username',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('phoneno',TelType::class, array('label'=>'Phone no','attr' => array('class' => 'form-control')))
        ->add('userimage',FileType::class, array('label'=>' Upload Profile picture','mapped'=>false,
        'data_class'=>User::class,'required'=>false,'attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('maritalstatus',TextType::class, array('label'=>'Marital status','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
                    // we must transform the image string from Db  to File to respect the form types
              $oldFileName = $user4->getUserimage();
                   /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['userimage']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('photos_directory');

                $newFilename = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                  try {
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                }catch(FileException $e){
                }
                $user4->setUserimage($newFilename);
            }
            else{
                 $user4->setUserimage($oldFileName );
            }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('my_profile'));
        }
        return $this->render('profile/index.html.twig',array('form'=>$form->createView()

                ));
         }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }


 /**
     * @Route("/user/update/area/info/detail/{id}",name="teacher_info")
     */
public function edituser(Request $request,$id)
{
         $user=$this->getUser();
        if($user != null){
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
       $form = $this->createFormBuilder($user4)

        ->add('username',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('maritalstatus',TextType::class, array('label'=>'Marital status','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('teacher_controller'));
        }
        return $this->render('profile/useredit.html.twig',array('form'=>$form->createView()

                ));
         }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }
 /**
     * @Route("/user/update/area/hods/info/detail/{id}",name="hods_info")
     */
public function edithods(Request $request,$id)
{
          $user=$this->getUser();
        if($user != null){
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
       $form = $this->createFormBuilder($user4)

        ->add('username',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('maritalstatus',TextType::class, array('label'=>'Marital status','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('hods_controller'));
        }
        return $this->render('profile/useredit.html.twig',array('form'=>$form->createView()

                ));
         }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }

 /**
     * @Route("/user/update/area/admin/info/detail/{id}",name="admin_info")
     */
public function editadmin(Request $request,$id)
{
         $user=$this->getUser();
        if($user != null){
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
       $form = $this->createFormBuilder($user4)

        ->add('username',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('maritalstatus',TextType::class, array('label'=>'Marital status','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('admin_controller'));
        }
        return $this->render('profile/useredit.html.twig',array('form'=>$form->createView()

                ));
         }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }


            /**
     * @Route("/evaluate/admin/area/student/{id}/edit/info/detail",name="editstudent")
     */
public function editstudent(Request $request,$id)
{
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($user4)

        ->add('username',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('regno',TextType::class, array('label'=>'Registration no','attr' => array('class' => 'form-control')))
        ->add('phoneno',TelType::class, array('label'=>'Phone no','attr' => array('class' => 'form-control')))
       ->add('yos',IntegerType::class, array('label'=>'Year of study','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
             ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('student_controller'));
        }
        return $this->render('profile/editstudent.html.twig',array('form'=>$form->createView()

                ));
    }


     /**
     * @Route("/{id}/edit/department/name",name="editd")
     */
public function editd(Request $request,$id)
{

        $department4 =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($department4)

        ->add('name',TextType::class, array('label'=>'Department Name','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('department_controller'));
        }
        return $this->render('profile/editdepartment.html.twig',array('form'=>$form->createView()

                ));
}


     /**
     * @Route("/{id}/edit/course/available/name",name="editcourse")
     */
public function editC(Request $request,$id)
{

        $course =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($course)

        ->add('cname',TextType::class, array('label'=>'Course Name','attr' => array('class' => 'form-control')))
        ->add('cshortname',TextType::class, array('label'=>'Course Short Name','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('course_controller'));
        }
        return $this->render('profile/editcourse.html.twig',array('form'=>$form->createView()

                ));
}
     /**
     * @Route("/{id}/edit/college/program/available/name",name="editprogram")
     */
public function editP(Request $request,$id)
{

        $program =$this->getDoctrine()->getRepository(Program::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($program)

        ->add('pname',TextType::class, array('label'=>'Program Name','attr' => array('class' => 'form-control')))
        ->add('pshort',TextType::class, array('label'=>'Program  Short Name','attr' => array('class' => 'form-control')))
        ->add('pcode',IntegerType::class, array('label'=>'Program  Code','attr' => array('class' => 'form-control')))
        ->add('pcredit',IntegerType::class, array('label'=>'Program  Credit','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirect($this->generateUrl('program_moduli'));
        }
        return $this->render('profile/editprogram.html.twig',array('form'=>$form->createView()

                ));
}
/**
     * @Route("/student/profile/area/update/info/edit/detail/show",name="student_profile")
     */
public function editstudentP(Request $request)
{
        $user=$this->getUser();
        if($user != null){

        $id=$user->getId();
        $user4 =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
        $form = $this->createFormBuilder($user4)

        ->add('username',TextType::class, array('label'=>'Full Name','attr' => array('class' => 'form-control')))
        ->add('email',EmailType::class, array('label'=>'Email','attr' => array('class' => 'form-control')))
        ->add('gender',TextType::class, array('label'=>'Gender','attr' => array('class' => 'form-control')))
        ->add('phoneno',TelType::class, array('label'=>'Phone no','attr' => array('class' => 'form-control')))
        ->add('nationality',TextType::class, array('label'=>'Nationality','attr' => array('class' => 'form-control')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('student_profile'));
        }
        return $this->render('student_evaluation/profile.html.twig',array('form'=>$form->createView()

                ));
         }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }

}
