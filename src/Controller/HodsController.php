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

class HodsController extends AbstractController
{
    /**
     * @Route("/hods/area/dashboard", name="hods")
     */
    public function dashboard(): Response
    {
         $user1=$this->getUser();
           if($user1 != null)
          {
        $userid=$user1->getId();
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
           //symfony sql statement to print student details
        $decl="SELECT department.name
        FROM `user` inner join department on user.userdept_id=department.id  where user.id=:part";

        $statement12=$connection->prepare($decl);
        $statement12->bindParam(':part',$userid);
        $statement12->execute();
        $finddeptname=$statement12->fetchOne();

        $decl13="SELECT count(*)
        FROM `user` inner join department on user.userdept_id=department.id  where user.id=:part";

        $statement10=$connection->prepare($decl13);
        $statement10->bindParam(':part',$userid);
        $statement10->execute();
        $finddept=$statement10->fetchOne();

        $decl12="SELECT department.id
        FROM `user` inner join department on user.userdept_id=department.id  where user.id=:part";

        $statement121=$connection->prepare($decl12);
        $statement121->bindParam(':part',$userid);
        $statement121->execute();
        $findid=$statement121->fetchOne();



        $decl121="SELECT count(*)
        FROM `user` inner join department on user.userdept_id=department.id  where department.id=:part";

        $statement1212=$connection->prepare($decl121);
        $statement1212->bindParam(':part',$findid);
        $statement1212->execute();
        $findteacher=$statement1212->fetchOne();

        $decl122="SELECT count(*)
         FROM `course` inner join department on course.id=department.courseassigned_id  where department.id=:part";

        $statement123=$connection->prepare($decl122);
        $statement123->bindParam(':part',$findid);
        $statement123->execute();
        $findclass=$statement123->fetchOne();

         $decl124="SELECT count(*)
         FROM `course` inner join department on course.id=department.courseassigned_id  where department.id=:part";

        $statement124=$connection->prepare($decl124);
        $statement124->bindParam(':part',$findid);
        $statement124->execute();
        $findsubject=$statement124->fetchOne();


        $findevaluation =$this->getDoctrine()->getRepository(Evaluation::class)->findOneBy(['addstatus'=>'active']);
        if($findevaluation){
        $findevaluation=$findevaluation->getAddstatus();
         }
         else{
         $findevaluation="inactive";
         }
        return $this->render('hods/index.html.twig', [
            'controller_name' => 'HodsController','finddept'=>$finddept,'findclass'=>$findclass,
            'finddeptname'=>$finddeptname,'findsubject'=>$findsubject,'findteacher'=>$findteacher,
            'findevaluation'=>$findevaluation
        ]);
         }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }
      /**
     * @Route("/hods/report/evaluation/rank", name="evaluation_summary")
     */
    public function summary(): Response
    {
      $user1=$this->getUser();
           if($user1 != null)
        {
        $findevaluation =$this->getDoctrine()->getRepository(Evaluation::class)->findOneBy(['id'=>1]);
        $status=$findevaluation->getSemester();
        $lafesa=$findevaluation->getAddstatus();
         $userid=$user1->getId();
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
           //symfony sql statement to print student details
        $decl="SELECT department.id
        FROM `user` inner join department on user.userdept_id=department.id  where user.id=:part";

        $statement12=$connection->prepare($decl);
        $statement12->bindParam(':part',$userid);
        $statement12->execute();
        $findd=$statement12->fetchOne();

         $decl123="SELECT program.pname,program.pshort,program.pcode,program.pcredit,program.id
        FROM `program` inner join course_program on program.id=course_program.program_id
        inner join course on course_program.course_id=course.id inner join department on course.id=department.courseassigned_id where department.id=:ik and program.semester='semester1'";
        $statement121=$connection->prepare($decl123);
        $statement121->bindParam(':ik',$findd);
        $statement121->execute();
        $program=$statement121->fetchAll();

         $decl123="SELECT program.pname,program.pshort,program.pcode,program.pcredit,program.id
        FROM `program` inner join course_program on program.id=course_program.program_id
        inner join course on course_program.course_id=course.id inner join department on course.id=department.courseassigned_id where department.id=:ik and program.semester='semester2'";
        $statement121=$connection->prepare($decl123);
        $statement121->bindParam(':ik',$findd);
        $statement121->execute();
        $program2=$statement121->fetchAll();


        return $this->render('hods/evaluationreport.html.twig', ['program'=>$program,'program2'=>$program2,'lafesa'=>$lafesa,'status'=>$status,
            'controller_name' => 'StudentEvaluationController',
        ]);
        }
        else
         {
                    return $this->redirectToRoute('app_login');
          }
    }

     /**
     * @Route("/hods/result/view/staff/begin/final/verify/{program}", name="hods_evaluate")
     */
    public function Result($program): Response
    {
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        $material =$this->getDoctrine()->getRepository(Program::class)->findOneBy(['id'=>$program]);
        $pname=$material->getPname();
          $decl11="select count(DISTINCT(evaluate_mark.student_mark_id)) from evaluate_mark inner join
         evaluate_mark_program on evaluate_mark.id=evaluate_mark_program.evaluate_mark_id where
          evaluate_mark_program.program_id=:just";

        $statement120=$connection->prepare($decl11);
        $statement120->bindParam(':just',$program);
        $statement120->execute();
        $mark=$statement120->fetchOne();
        //symfony sql statement to print student details
        $decl=" SELECT user.username
        FROM `user` inner join program_user on user.id=program_user.user_id inner join program on program_user.program_id=program.id where program.id=:part";

        $statement12=$connection->prepare($decl);
        $statement12->bindParam(':part',$program);
        $statement12->execute();
        $userof=$statement12->fetchAll();
        $decl1="select avg(avg) from evaluate_mark inner join evaluate_mark_program on evaluate_mark.id=evaluate_mark_program.evaluate_mark_id
                where evaluate_mark_program.program_id=:part";

        $statement121=$connection->prepare($decl1);
        $statement121->bindParam(':part',$program);
        $statement121->execute();
        $average=$statement121->fetchOne();
        $average=round($average);
        $decl11="select count(*) from evaluate_mark inner join evaluate_mark_program on evaluate_mark.id=evaluate_mark_program.evaluate_mark_id
                where evaluate_mark_program.program_id=:part and comments is not null";

        $statement122=$connection->prepare($decl11);
        $statement122->bindParam(':part',$program);
        $statement122->execute();
        $comment=$statement122->fetchOne();
       return $this->render('setting/evalresult.html.twig',['mark'=>$mark,'average'=>$average,'userof'=>$userof,'pname'=>$pname,'comment' => $program,'findcomment'=>$comment]);
    }

        /**
     * @Route("/hods/view/Department/course/available", name="hods_course")
     */
    public function hodsView()
    {
        $user=$this->getUser();
        if($user != null){
       $userid=$user->getId();
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
           //symfony sql statement to print student details
        $decl="SELECT department.id
        FROM `user` inner join department on user.userdept_id=department.id  where user.id=:part";

        $statement12=$connection->prepare($decl);
        $statement12->bindParam(':part',$userid);
        $statement12->execute();
        $findd=$statement12->fetchOne();
        //symfony sql statement to print teacher details
        $decl=" SELECT course.id,course.cname,course.cshortname
        FROM department
        inner join `course`
        on department.courseassigned_id=course.id where  department.id=:just";

        $statement23=$connection->prepare($decl);
        $statement23->bindParam(':just',$findd);
        $statement23->execute();
        $course=$statement23->fetchAll();

        return $this->render('hods/departmentcourse.html.twig',['course'=>$course]);
        }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }
          /**
     * @Route("/hods/area/view/department/course/course_moduli/{id}", name="hods_view_moduli")
     */
    public function programhodsView($id)
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

        return $this->render('hods/coursemoduli.html.twig',['program'=>$program,'id'=>$id,'cname'=>$cname]);
        }else
         {
                    return $this->redirectToRoute('app_login');
          }
      }

        /**
     * @Route("/hods/view/available/department/academicstaff", name="hods_member")
     */
    public function view()
    {
        $user=$this->getUser();
        if($user != null){
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        $userid=$user->getId();
        //symfony sql statement to print teacher details
        $decl="SELECT department.id
        FROM `user` inner join department on user.userdept_id=department.id  where user.id=:part";

        $statement12=$connection->prepare($decl);
        $statement12->bindParam(':part',$userid);
        $statement12->execute();
        $findd=$statement12->fetchOne();

        $decl=" SELECT user.id,user.username,user.email,user.title
        FROM department
        inner join `user`
        on department.id=user.userdept_id where title='teacher' and department.id=:just";

        $statement23=$connection->prepare($decl);
        $statement23->bindParam(':just',$findd);
        $statement23->execute();
        $member=$statement23->fetchAll();

        return $this->render('hods/member.html.twig',['member'=>$member]);
        }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }
}
