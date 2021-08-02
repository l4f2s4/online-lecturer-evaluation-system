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
use App\Entity\EvaluateMark;

class RemoveController extends AbstractController
{

/**
     * @Route("/evaluate/admin/remove/{id}", name="removeadmin")
     */
    public function removeadmin(Request $request,$id): Response
    {

                    $user1=$this->getUser();
                    $em=$this->getDoctrine()->getManager();
                    $connection=$em->getConnection();
                    $display =$this->getDoctrine()->getRepository(User::class)->findBy(['title'=>'superadmin']);
                    $userid=$this->getUser()->getId();
                    $projectuser =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);

            if($projectuser){
                    $em->remove($projectuser);
                    $em->flush();
                    return $this->redirectToRoute('admin_controller');
            }
            else if($userid==$id){
                    $session = $this->get('session');
                    $session = new Session();
                    $session->invalidate();
                    $em->remove($projectuser);
                    $em->flush();
                    $success="Your account no longer exists contact administrator for more info";
                    return new response($success);

            }
            else{
                   return $this->render('admin/adminiregister.html.twig',['display'=>$display]);
                 }


    }

       /**
     * @Route("/evaluate/admin/delete/teacher/{id}", name="deleteteacher")
     */
    public function removeteacher(Request $request,$id): Response
    {
               $user1=$this->getUser();
                $em=$this->getDoctrine()->getManager();
                if($user1!=null){
                        $teacher =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);

                if($teacher){
                        $teacher->removeProgramassigned(null);
                        $teacher->setUserdept(null);
                        $em->remove($teacher);
                        $em->flush();
                     return $this->redirectToRoute('teacher_controller');
                }
                }
                else{
                          return $this->redirectToRoute('app_login');
                   }
    }
         /**
     * @Route("/evaluate/admin/delete/status/hods/{id}", name="deletehods")
     */
    public function removehods(Request $request,$id): Response
    {
               $user1=$this->getUser();
                $em=$this->getDoctrine()->getManager();
                if($user1!=null){
                        $teacher =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);

                if($teacher){
                        $teacher->removeProgramassigned(null);
                        $teacher->setUserdept(null);
                        $em->remove($teacher);
                        $em->flush();
                     return $this->redirectToRoute('hods_controller');
                }
                }
                else{
                          return $this->redirectToRoute('app_login');
                   }
    }
        /**
     * @Route("/evaluate/admin/delete/Department/show/{id}", name="deletedept")
     */
    public function removedept(Request $request,$id): Response
    {
               $user1=$this->getUser();
                $em=$this->getDoctrine()->getManager();
                if($user1!=null)
                {
                  $department =$this->getDoctrine()->getRepository(Department::class)->findOneBy(['id' => $id]);
                   $user =$this->getDoctrine()->getRepository(User::class)->findBy(['userdept' => $id]);
                if($department)
                {
                if($user)
                     {
                         foreach($user as $i)
                           {
                             $l=$i->getId();
                             $d =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $l]);
                             $department->removeAssigndept($d);
                            }
                        }
                     $department->setCourseassigned(null);
                     $em->remove($department);
                     $em->flush();
                }

                     return $this->redirectToRoute('department_controller');
                  }
                else{
                          return $this->redirectToRoute('app_login');
                   }
    }




 /**
     * @Route("/evaluate/admin/delete/student/permanent/{id}", name="deletestudent")
     */
    public function removestudent(Request $request,$id): Response
    {
                 $user1=$this->getUser();
                $em=$this->getDoctrine()->getManager();
                if($user1!=null){
                        $student =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $id]);
                        $evaluate =$this->getDoctrine()->getRepository(EvaluateMark::class)->findOneBy(['studentMark' => $id]);

                if($student){
                        $student->removeProgramassigned(null);
                        $student->setUserdept(null);
                        if($evaluate){
                         $student->removeAddMark($evaluate);
                        }
                        $em->remove($student);
                        $em->flush();
                     return $this->redirectToRoute('student_controller');
                }
                }
                else{
                          return $this->redirectToRoute('app_login');
                   }
    }



 }

