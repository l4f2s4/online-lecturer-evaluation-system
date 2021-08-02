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
use App\Entity\User;
use App\Entity\Department;
use App\Entity\Course;
use App\Entity\Program;
use App\Entity\Evaluation;

class CollegeController extends AbstractController
{
    /**
     * @Route("/college/update/password", name="update")
     */
    public function updpass(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
      $user1=$this->getUser();
      if($user1 != null)
       {
       $oldpass=strip_tags($request->request->get('oldpass'));
       $user=$this->getUser();
       $encold= $passwordEncoder->isPasswordValid($user,$oldpass);
       $newpass=strip_tags($request->request->get('newpass'));
       $confirm=strip_tags($request->request->get('conf'));
          if($request->isMethod('POST') && !empty($oldpass) && !empty($newpass) && !empty($confirm))
            {
               if($encold==true)
                  {
                      if(strlen($newpass)>7)
                        {
                             if($newpass==$confirm)
                                 {
                                    $conf=$passwordEncoder->encodePassword($user,$confirm);
                                    $user->setPassword($conf);
                                    $entityManager = $this->getDoctrine()->getManager();
                                    $entityManager->persist($user);
                                    $entityManager->flush();
                                     $session = $this->get('session');
                                     $session = new Session();
                                     $session->invalidate();
                                     return $this->redirect($this->generateUrl('app_login'));
                                 }
                             else
                                 {
                                      $this->addFlash('mismatch',  'Password mismatch!');
                                      return $this->render('college/changepassword.html.twig');
                                  }
                        }
                      else
                        {

                                    return $this->render('college/changepassword.html.twig');
                         }
                  }
                  else
                  {
                        $this->addFlash('current','The current password is incorrect');
                        return $this->render('college/changepassword.html.twig');
                  }

            }
          else
           {

                  return $this->render('college/changepassword.html.twig');
          }
          return $this->render('college/changepassword.html.twig');
          }
      else{
            return $this->redirectToRoute('app_login');
          }
    }

    /**
     * @Route("/evaluate/admin/add/department", name="department_controller")
     */
    public function Department(): Response
    {
      $user=$this->getUser();
      if($user != null){
       $department =$this->getDoctrine()->getRepository(Department::class)->findAll();
        return $this->render('college/index.html.twig', [
            'controller_name' => 'CollegeController','department'=>$department
        ]);
        }
        else
         {
                    return $this->redirectToRoute('app_login');
          }
    }
         /**
             * @Route("/evaluate/admin/action/prove", name="department_action")
             */
        public function departmentAction(Request $request): Response
        {
            $user=$this->getUser();
         if($user != null){
           $em=$this->getDoctrine()->getManager();
            $department =new Department();
            $dept=$request->request->get('dept');
            $takedept =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name'=>$dept]);
            if($request->isMethod('post'))
            {
              if(!empty($dept))
               {
               if(!$takedept)
                 {
                    $department->setName($dept);
                    $em->persist($department);
                    $em->flush();
                    return $this->redirectToRoute('department_controller');
                 }
                 $this->addFlash('department',''.$dept.' department already exists');
                 return $this->render('college/departmentaction.html.twig');
                }
               else
                {
                 $this->addFlash('department','Please enter department name');
                return $this->render('college/departmentaction.html.twig');
                }
            }
                return $this->render('college/departmentaction.html.twig');
         }
           else
            {
                    return $this->redirectToRoute('app_login');
          }
    }

        /**
     * @Route("/evaluate/admin/view/Department/member/{id}", name="view_member")
     */
    public function view($id)
    {
        $user=$this->getUser();
        if($user != null){
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();

        //symfony sql statement to print teacher details
        $decl=" SELECT user.id,user.username,user.email,user.title
        FROM department
        inner join `user`
        on department.id=user.userdept_id where (title='teacher' or title='hods') and department.id=:just";

        $statement23=$connection->prepare($decl);
        $statement23->bindParam(':just',$id);
        $statement23->execute();
        $member=$statement23->fetchAll();

        return $this->render('college/member.html.twig',['member'=>$member]);
        }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }

      /**
     * @Route("/evaluate/admin/add/department/course", name="course_controller")
     */
    public function Course(): Response
    {
      $user=$this->getUser();
      if($user != null){
       $course =$this->getDoctrine()->getRepository(Department::class)->findAll();
        return $this->render('college/course.html.twig', [
            'controller_name' => 'CollegeController','department'=>$course
        ]);
        }
        else
         {
                    return $this->redirectToRoute('app_login');
          }
    }
         /**
             * @Route("/evaluate/admin/action/department/course/prove/{id}", name="course_action")
             */
        public function courseAction(Request $request,$id): Response
        {
            $user=$this->getUser();
         if($user != null){
           $em=$this->getDoctrine()->getManager();
            $course =new Course();
            $cname=$request->request->get('cname');
            $cshort=$request->request->get('cshort');
            $take=$this->getDoctrine()->getRepository(Course::class)->findOneBy(['cname'=>$cname]);
            $takeshort=$this->getDoctrine()->getRepository(Course::class)->findOneBy(['cshortname'=>$cshort]);
            $dept=$this->getDoctrine()->getRepository(Department::class)->findOneBy(['id'=>$id]);
            if($request->isMethod('post'))
            {
              if(!empty($cname) & !empty($cshort))
               {
               if(!$take)
                 {
                 if(!$takeshort){
                    $course->setCname($cname);
                    $course->setCshortname($cshort);
                    $course->addCoursedept($dept);
                    $em->persist($course);
                    $em->flush();
                    return $this->redirectToRoute('view_course',['id'=>$id]);
                    }
                 $this->addFlash('cshort',''.$cshort.' course short name already exists');
                 return $this->render('college/courseaction.html.twig',['id'=>$id]);
                 }
                 $this->addFlash('cname',''.$cname.' course already exists');
                 return $this->render('college/courseaction.html.twig',['id'=>$id]);
                }
               else
                {
                 $this->addFlash('cname','All field are required');
                return $this->render('college/courseaction.html.twig',['id'=>$id]);
                }
            }
                return $this->render('college/courseaction.html.twig',['id'=>$id]);
         }
           else
            {
                    return $this->redirectToRoute('app_login');
          }
    }

        /**
     * @Route("/evaluate/admin/view/Department/course/{id}", name="view_course")
     */
    public function courseView($id)
    {
        $user=$this->getUser();
        if($user != null){
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
       $take=$this->getDoctrine()->getRepository(Department::class)->findOneBy(['id'=>$id]);
       $dname=$take->getName();
        //symfony sql statement to print teacher details
        $decl=" SELECT course.id,course.cname,course.cshortname
        FROM department
        inner join `course`
        on department.courseassigned_id=course.id where  department.id=:just";

        $statement23=$connection->prepare($decl);
        $statement23->bindParam(':just',$id);
        $statement23->execute();
        $course=$statement23->fetchAll();

        return $this->render('college/departmentcourse.html.twig',['course'=>$course,'id'=>$id,'dname'=>$dname]);
        }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }

        /**
        * @Route("/evaluate/admin/action/department/course/program/prove/{id}", name="program_action")
        */
 public function programAction(Request $request,$id): Response
    {
            $user=$this->getUser();
         if($user != null){
            $em=$this->getDoctrine()->getManager();
            $pname=$request->request->get('program');
            $take=$this->getDoctrine()->getRepository(Course::class)->findOneBy(['id'=>$id]);
            $program=$this->getDoctrine()->getRepository(Program::class)->findOneBy(['pname'=>$pname]);
            $findprogram=$this->getDoctrine()->getRepository(Program::class)->findAll();

            if($request->isMethod('post'))
            {
              if(!empty($pname))
               {
               if($program)
                 {
                    $take->addCourseProgram($program);
                    $em->persist($take);
                    $em->flush();
                    return $this->redirectToRoute('view_moduli',['id'=>$id]);
                    }
                 $this->addFlash('pname','Moduli not available');
                 return $this->render('college/programaction.html.twig',['id'=>$id,'findprogram'=>$findprogram]);
                }
               else
                {
                 $this->addFlash('pname','All field are required');
               return $this->render('college/programaction.html.twig',['id'=>$id,'findprogram'=>$findprogram]);
                }
            }
                return $this->render('college/programaction.html.twig',['id'=>$id,'findprogram'=>$findprogram]);
         }
           else
            {
                    return $this->redirectToRoute('app_login');
          }
    }

        /**
     * @Route("/evaluate/admin/view/Department/course/course_moduli/{id}", name="view_moduli")
     */
    public function programView($id)
    {
        $user=$this->getUser();
        if($user != null){
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        $take=$this->getDoctrine()->getRepository(Course::class)->findOneBy(['id'=>$id]);
        $cname=$take->getCname();
        //symfony sql statement to print teacher details
        $decl=" SELECT program.id,program.pname,program.pshort,program.pcode,program.pcredit
        FROM program
        inner join course_program
        on program.id=course_program.program_id
        inner join `course`
        on course_program.course_id=course.id where  course.id=:just";

        $statement23=$connection->prepare($decl);
        $statement23->bindParam(':just',$id);
        $statement23->execute();
        $program=$statement23->fetchAll();

        return $this->render('college/coursemoduli.html.twig',['program'=>$program,'id'=>$id,'cname'=>$cname,'take'=>$id]);
        }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }

}


