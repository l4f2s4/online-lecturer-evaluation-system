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


class AdminController extends AbstractController
{
    /**
     * @Route("/evaluate/admin/dashboard", name="admin_evaluate")
     */
    public function admin(): Response
    {
          $user1=$this->getUser();
           if($user1 != null)
          {
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        //symfony doctrine handle
        $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
        $findhods =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'HoDs']);
        $findteacher =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'teacher']);
        $findclass =$this->getDoctrine()->getRepository(Course::class)->findAll();
        $findstudent =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'student']);
        $total =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'student']);
        $decl1="select count(DISTINCT(evaluate_mark.student_mark_id)) from evaluate_mark";

        $statement121=$connection->prepare($decl1);

        $statement121->execute();
        $mark=$statement121->fetchOne();
        $findsubject =$this->getDoctrine()->getRepository(Program::class)->findAll();
        $findevaluation =$this->getDoctrine()->getRepository(Evaluation::class)->findOneBy(['addstatus'=>'active']);
        if($findevaluation){
        $findevaluation=$findevaluation->getAddstatus();
         }
         else{
         $findevaluation="inactive";
         }
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController','finddept'=>$finddept,'findclass'=>$findclass,
            'findstudent'=>$findstudent,'findsubject'=>$findsubject,'findhods'=>$findhods,'findteacher'=>$findteacher,
            'findevaluation'=>$findevaluation,'mark'=>$mark,'total'=>$total
        ]);
         }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }


     /**
     * @Route("/evaluate/admin/add/control", name="admin_controller")
     */
    public function add(): Response
    {
         $user1=$this->getUser();
         if($user1 != null)
          {
          $display =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'superadmin']);
          return $this->render('admin/adminiregister.html.twig',['display'=>$display]);
          }
         else{
            return $this->redirectToRoute('app_login');
          }
    }
      /**
     * @Route("/evaluate/admin/add/control/action", name="admin_action")
     */
    public function addAction(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
         $user1=$this->getUser();
         if($user1 != null)
          {
                $display =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'superadmin']);
                $em=$this->getDoctrine()->getManager();
                $admin = new User();
                $fname=$request->request->get('fname');
                $lname=$request->request->get('lname');
                $email=$request->request->get('email');
                $gender=$request->request->get('gender');
                $nation=$request->request->get('national');
                $marital=$request->request->get('marital');
                $connection=$em->getConnection();
                $statement2=$connection->prepare("select email from user where email=:just");
                $statement2->bindParam(':just', $email);
                $statement2->execute();
                $verifyemail=$statement2->fetchAll();

                     if($request->isMethod('post'))
                     {
                     if( !empty($fname) & !empty($lname) & !empty($nation) & !empty($marital) & !empty($email) & !empty($gender)){
                             if(!$verifyemail)
                              {
                                $admin->setUsername($fname.' '.$lname);
                                $admin->setEmail($email);
                                $admin->setGender($gender);
                                $admin->setTitle("superadmin");
                                $admin->setPassword($passwordEncoder->encodePassword(
                                                                $admin,
                                                                $lname
                                                            ));
                                $admin->setNationality($nation);
                                $admin->setMaritalStatus($marital);
                                $admin->setRoles([
                                                "ROLE_ADMIN"
                                            ]);

                                $em->persist($admin);
                                $em->flush();

                                return $this->redirectToRoute('admin_controller');
                                 }

                              else
                              {

                                     $this->addFlash('email',  'User email already exists');
                                      return $this->render('admin/addadmin.html.twig',['display'=>$display]);
                              }
                     }
                     else
                     {
                                $this->addFlash('success',  'All fields are mandatory,please fill out');
                                return $this->render('admin/addadmin.html.twig',['display'=>$display]);
                     }
                   }
          return $this->render('admin/addadmin.html.twig',['display'=>$display]);
          }
         else{
            return $this->redirectToRoute('app_login');
          }
    }
      /**
     * @Route("/evaluate/admin/add/hods/control", name="hods_controller")
     */
    public function addhods(): Response
    {
         $user1=$this->getUser();
         if($user1 != null)
          {
          $display =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'hods']);
          return $this->render('admin/hodsregister.html.twig',['display'=>$display]);
          }
         else{
            return $this->redirectToRoute('app_login');
          }
    }
      /**
     * @Route("/evaluate/admin/add/hods/center/control/action", name="hods_action")
     */
    public function hodsAction(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
         $user1=$this->getUser();
         if($user1 != null)
          {
                $display =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'hods']);
                $findprogram =$this->getDoctrine()->getRepository(Program::class)->findAll();
                $em=$this->getDoctrine()->getManager();
                $teacher = new User();
                $fname=$request->request->get('fname');
                $lname=$request->request->get('lname');
                $email=$request->request->get('email');
                $gender=$request->request->get('gender');
                $nation=$request->request->get('national');
                $dept=$request->request->get('dept');
                $marital=$request->request->get('marital');
                $program=$request->request->get('program');
                $pprogram =$this->getDoctrine()->getRepository(Program::class)->findOneBy(['pname'=>$program]);
                $connection=$em->getConnection();
                $statement2=$connection->prepare("select email from user where email=:just");
                $statement2->bindParam(':just', $email);
                $statement2->execute();
                $verifyemail=$statement2->fetchAll();
               $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
               $dept1 =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name'=>$dept]);
               if($request->isMethod('post'))
                {
                     if(!empty($fname) & !empty($lname) & !empty($nation) & !empty($dept) & !empty($marital) & !empty($email) & !empty($gender))
                     {
                             if(!$verifyemail)
                              {
                                if($dept1)
                                    {
                                        $teacher->setUsername($fname.' '.$lname);
                                        $teacher->setEmail($email);
                                        $teacher->setGender($gender);
                                        $teacher->setTitle("hods");
                                        $teacher->setPassword($passwordEncoder->encodePassword(
                                                                        $teacher,
                                                                        $lname
                                                                    ));
                                        $teacher->setNationality($nation);
                                        $teacher->setMaritalStatus($marital);
                                        $teacher->setRoles([
                                                        'ROLE_TEACHER'
                                                    ]);
                                          if($pprogram){
                                        $teacher->addProgramassigned($pprogram);
                                          }
                                        $teacher->setUserdept($dept1);
                                        $em->persist($teacher);
                                        $em->flush();
                                        return $this->redirectToRoute('hods_controller');

                                    }
                                     else
                                     {
                                                return $this->render('admin/addhods.html.twig',['finddept'=>$finddept,'display'=>$display,'findprogram'=>$findprogram]);
                                     }

                                 }

                              else
                              {

                                     $this->addFlash('email',  'User email already exists');
                                      return $this->render('admin/addhods.html.twig',['finddept'=>$finddept,'display'=>$display,'findprogram'=>$findprogram]);
                              }
                     }
                     else
                     {
                                $this->addFlash('success',  'All fields are mandatory,please fill out');
                                return $this->render('admin/addhods.html.twig',['finddept'=>$finddept,'display'=>$display,'findprogram'=>$findprogram]);
                     }
               }
         return $this->render('admin/addhods.html.twig',['finddept'=>$finddept,'display'=>$display,'findprogram'=>$findprogram]);
          }
         else{
            return $this->redirectToRoute('app_login');
          }
    }

        /**
     * @Route("/evaluate/admin/add/teacher", name="teacher_controller")
     */
    public function addteacher(): Response
    {
         $user1=$this->getUser();
         if($user1 != null)
          {
          $display =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'teacher']);
          return $this->render('admin/teacherregister.html.twig',['display'=>$display]);
          }
         else{
            return $this->redirectToRoute('app_login');
          }
    }
      /**
     * @Route("/evaluate/admin/add/teacher/action", name="teacher_action")
     */
    public function teacherAction(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
         $user1=$this->getUser();
         if($user1 != null)
          {
                $display =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'teacher']);
                $findprogram =$this->getDoctrine()->getRepository(Program::class)->findAll();
                $em=$this->getDoctrine()->getManager();
                $teacher = new User();
                $fname=$request->request->get('fname');
                $lname=$request->request->get('lname');
                $email=$request->request->get('email');
                $gender=$request->request->get('gender');
                $phoneno =$request->request->get('phoneno');
                $nation=$request->request->get('national');
                $dept=$request->request->get('dept');
                $program=$request->request->get('program');
                $pprogram =$this->getDoctrine()->getRepository(Program::class)->findOneBy(['pname'=>$program]);
                $marital=$request->request->get('marital');
                $connection=$em->getConnection();
                $statement2=$connection->prepare("select email from user where email=:just");
                $statement2->bindParam(':just', $email);
                $statement2->execute();
                $verifyemail=$statement2->fetchAll();
               $finddept =$this->getDoctrine()->getRepository(Department::class)->findAll();
               $dept1 =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['name'=>$dept]);
               if($request->isMethod('post'))
                {
                     if(!empty($fname) & !empty($lname) & !empty($phoneno) & !empty($nation) & !empty($dept) & !empty($marital) & !empty($email) & !empty($gender))
                     {
                             if(!$verifyemail)
                              {
                                if($dept1)
                                    {
                                        $teacher->setUsername($fname.' '.$lname);
                                        $teacher->setEmail($email);
                                        $teacher->setGender($gender);
                                        $teacher->setTitle("teacher");
                                        $teacher->setNationality($nation);
                                        $teacher->setMaritalStatus($marital);
                                        $teacher->setPhoneno($phoneno);
                                        $teacher->setRoles([
                                                        'ROLE_STAFF'
                                                    ]);
                                        if($pprogram){
                                        $teacher->addProgramassigned($pprogram);
                                          }
                                        $teacher->setUserdept($dept1);
                                        $em->persist($teacher);
                                        $em->flush();
                                        return $this->redirectToRoute('teacher_controller');

                                    }
                                     else
                                     {
                                                return $this->render('admin/addteacher.html.twig',['finddept'=>$finddept,'display'=>$display,'findprogram'=>$findprogram]);
                                     }

                                 }

                              else
                              {

                                     $this->addFlash('email',  'User email already exists');
                                      return $this->render('admin/addteacher.html.twig',['finddept'=>$finddept,'display'=>$display,'findprogram'=>$findprogram]);
                              }
                     }
                     else
                     {
                                $this->addFlash('success',  'All fields are mandatory,please fill out');
                     return $this->render('admin/addteacher.html.twig',['finddept'=>$finddept,'display'=>$display,'findprogram'=>$findprogram]);
                     }
               }
         return $this->render('admin/addteacher.html.twig',['finddept'=>$finddept,'display'=>$display,'findprogram'=>$findprogram]);
          }
         else{
            return $this->redirectToRoute('app_login');
          }
    }

        /**
     * @Route("/evaluate/admin/add/student/verify", name="student_controller")
     */
    public function addStudent(): Response
    {
         $user1=$this->getUser();
         if($user1 != null)
          {
          $display =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'student']);
          return $this->render('admin/student.html.twig',['display'=>$display]);
          }
         else{
            return $this->redirectToRoute('app_login');
          }
    }
      /**
     * @Route("/evaluate/admin/add/student/verify/action", name="student_action")
     */
    public function studentAction(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
         $user1=$this->getUser();
         if($user1 != null)
          {
                $display =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'student']);
               $em=$this->getDoctrine()->getManager();
                $student = new User();
                $fname=$request->request->get('fname');
                $lname=$request->request->get('lname');
                $email=$request->request->get('email');
                $regno=$request->request->get('reg');
                $gender=$request->request->get('gender');
                $nation=$request->request->get('national');
                $phoneno=$request->request->get('phoneno');
                $yos=$request->request->get('yos');
                $course=$request->request->get('course');
                $verifyemail=$this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$email]);
                $verifyregno=$this->getDoctrine()->getRepository(User::class)->findOneBy(['regno'=>$regno]);
                $findclass =$this->getDoctrine()->getRepository(Course::class)->findAll();
                $course1 =$this->getDoctrine()->getRepository(Course::class)->findOneBy(['cname'=>$course]);
               if($request->isMethod('post'))
                {
                     if(!empty($fname) & !empty($phoneno) & !empty($yos) & !empty($regno) & !empty($lname) & !empty($nation)  & !empty($course) & !empty($email) & !empty($gender))
                     {
                             if(!$verifyemail)
                              {
                               if(!$verifyregno)
                               {
                               if( (strlen($regno)>15) & (str_starts_with($regno,"nit/")) ){

                                  if($course1)
                                  {
                                            $student->setUsername($fname.' '.$lname);
                                            $student->setEmail($email);
                                            $student->setGender($gender);
                                            $student->setTitle('student');
                                            $student->setPassword($passwordEncoder->encodePassword(
                                                                $student,
                                                                $lname
                                                            ));
                                            $student->setRegno($regno);
                                            $student->setPhoneno($phoneno);
                                            $student->setYos($yos);
                                            $student->setNationality($nation);
                                            $student->setAssignCourse($course1);
                                            $student->setRoles([
                                                        'ROLE_STUDENT'
                                                    ]);
                                            $em->persist($student);
                                            $em->flush();
                                            return $this->redirectToRoute('student_controller');
                                     }
                                     else
                                     {
                                         return $this->render('admin/studentaction.html.twig',['findclass'=>$findclass,'display'=>$display]);
                                     }
                                    }
                                   else
                                      {
                                                 $this->addFlash('regno',  "Invalid registration number");
                                                 return $this->render('admin/studentaction.html.twig',['findclass'=>$findclass,'display'=>$display]);
                                      }
                                  }
                                    else
                                      {
                                                 $this->addFlash('regno',  'registration number already exists');
                                                  return $this->render('admin/studentaction.html.twig',['findclass'=>$findclass,'display'=>$display]);
                                      }

                                 }

                              else
                              {

                                     $this->addFlash('email',  'This email '.$email.' already exists');
                                     return $this->render('admin/studentaction.html.twig',['findclass'=>$findclass, 'display'=>$display]);
                              }
                     }
                     else
                     {
                                $this->addFlash('success',  'All fields are mandatory,please fill out');
                                    return $this->render('admin/studentaction.html.twig',['findclass'=>$findclass,'display'=>$display]);
                     }
               }
    return $this->render('admin/studentaction.html.twig',['findclass'=>$findclass,'display'=>$display]);
            }
         else{
            return $this->redirectToRoute('app_login');
          }
    }
}

