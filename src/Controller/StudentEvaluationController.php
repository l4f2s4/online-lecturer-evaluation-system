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

class StudentEvaluationController extends AbstractController
{
        /**
     * @Route("/student/evaluation/instruction/form", name="student_instruction")
     */
    public function instructionPage(): Response
    {
        return $this->render('student_evaluation/instruction.html.twig', [
            'controller_name' => 'StudentEvaluationController',
        ]);
    }
       /**
     * @Route("/student/evaluation/rating/rank", name="student_rating")
     */
    public function ratingPage(): Response
    {
        return $this->render('student_evaluation/rating.html.twig', [
            'controller_name' => 'StudentEvaluationController',
        ]);
    }
          /**
     * @Route("/evaluate/admin/evaluation/report/rank", name="evaluation_report")
     */
    public function report(): Response
    {
        $findevaluation =$this->getDoctrine()->getRepository(Evaluation::class)->findOneBy(['id'=>1]);
        $status=$findevaluation->getSemester();
        $lafesa=$findevaluation->getAddstatus();
        $program =$this->getDoctrine()->getRepository(Program::class)->findBy(['semester'=>'semester1']);
        $program2 =$this->getDoctrine()->getRepository(Program::class)->findBy(['semester'=>'semester2']);
        return $this->render('setting/evaluationreport.html.twig', ['program'=>$program,'program2'=>$program2,'lafesa'=>$lafesa,'status'=>$status,
            'controller_name' => 'StudentEvaluationController',
        ]);
    }
           /**
     * @Route("/student/begin/evaluation/process/final", name="evaluate")
     */
    public function evaluate(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        $user=$this->getUser();
        $just=$user->getId();
       // $id =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=>$id]);

        //symfony sql statement to print student details
        $decl="SELECT program.id,pname,pcode,pshort,pcredit
        FROM `user` inner join course on user.assign_course_id=course.id
        inner join course_program on course.id=course_program.course_id
        inner join program on course_program.program_id=program.id where user.id=:part and program.semester='semester1'
        and program.id
        not in
         (  select program.id from evaluate_mark inner join
         evaluate_mark_program on evaluate_mark.id=evaluate_mark_program.evaluate_mark_id
         inner join program on evaluate_mark_program.program_id=program.id
         where
         program.semester='semester1' and  evaluate_mark.student_mark_id=:regno)
        ";
        $statement12=$connection->prepare($decl);
        $statement12->bindParam(':part',$just);
        $statement12->bindParam(':regno',$just);
        $statement12->execute();
        $program=$statement12->fetchAll();
         $decl12=" SELECT program.id,pname,pcode,pshort,pcredit
        FROM `user` inner join course on user.assign_course_id=course.id
        inner join course_program on course.id=course_program.course_id
        inner join program on course_program.program_id=program.id where user.id=:part and program.semester='semester2'
        and program.id
        not in
         (  select program.id from evaluate_mark inner join
         evaluate_mark_program on evaluate_mark.id=evaluate_mark_program.evaluate_mark_id
         inner join program on evaluate_mark_program.program_id=program.id
         where
         program.semester='semester2' and  evaluate_mark.student_mark_id=:regno)
        ";

        $statement121=$connection->prepare($decl12);
        $statement121->bindParam(':part',$just);
        $statement121->bindParam(':regno',$just);
        $statement121->execute();
        $program2=$statement121->fetchAll();



         $decl192="select  program.id,pname,pshort,pcode,pcredit from evaluate_mark inner join
         evaluate_mark_program on evaluate_mark.id=evaluate_mark_program.evaluate_mark_id
         inner join program on evaluate_mark_program.program_id=program.id
         where
         program.semester='semester1' and  evaluate_mark.student_mark_id=:part
        ";

        $statement129=$connection->prepare($decl192);
        $statement129->bindParam(':part',$just);
        $statement129->execute();
        $programt=$statement129->fetchAll();


        $decl198="select program.id,pname,pshort,pcode,pcredit from evaluate_mark inner join
         evaluate_mark_program on evaluate_mark.id=evaluate_mark_program.evaluate_mark_id
         inner join program on evaluate_mark_program.program_id=program.id
         where
         program.semester='semester2' and  evaluate_mark.student_mark_id=:part
        ";

        $statement128=$connection->prepare($decl198);
        $statement128->bindParam(':part',$just);
        $statement128->execute();
        $programm=$statement128->fetchAll();

        $findevaluation =$this->getDoctrine()->getRepository(Evaluation::class)->findOneBy(['id'=>1]);
        $status=$findevaluation->getSemester();
        $lafesa=$findevaluation->getAddstatus();
        return $this->render('student_evaluation/programteachear.html.twig', ['program'=>$program,'program2'=>$program2,
            'controller_name' => 'StudentEvaluationController','status'=>$status,'lafesa'=>$lafesa,'programt'=>$programt,'programm'=>$programm
        ]);
    }
     /**
     * @Route("/student/begin/evaluation/process/final/verify/{program}", name="evaluate_action")
     */
    public function evaluate_action(Request $request,$program): Response
    {
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
         $user=$this->getUser();
         $stid=$user->getId();

        //symfony sql statement to print student details
        $decl=" SELECT user.username
        FROM `user` inner join program_user on user.id=program_user.user_id inner join program on program_user.program_id=program.id where program.id=:part";

        $statement12=$connection->prepare($decl);
        $statement12->bindParam(':part',$program);
        $statement12->execute();
        $userof=$statement12->fetchAll();

        $evaluation = new EvaluateMark();
        $rating=9;
        $mark1=$request->request->get('mark1');
        $mark2=$request->request->get('mark2');
        $mark3=$request->request->get('mark3');
        $mark4=$request->request->get('mark4');
        $mark5=$request->request->get('mark5');
        $mark6=$request->request->get('mark6');
        $mark7=$request->request->get('mark7');
        $mark8=$request->request->get('mark8');
        $mark9=$request->request->get('mark9');
        $today=new \DateTime('now');
        $today=$today->format('Y-m-d');
        $ayear=$request->request->get('ayear');
        $comment=$request->request->get('comment');

        $min=1;
        $max=5;
        /*total possibility*/

        $material =$this->getDoctrine()->getRepository(Program::class)->findOneBy(['id'=>$program]);
        $pname=$material->getPname();
        $findevaluation =$this->getDoctrine()->getRepository(Evaluation::class)->findAll();
        $student =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=>$stid]);
            if($request->isMethod('post'))
            {

                   if(!empty($mark1) & !empty($mark2) & !empty($mark3) & !empty($ayear) & !empty($mark4) & !empty($mark5) & !empty($mark6) & !empty($mark7) & !empty($mark8) & !empty($mark9)){
                      if((($min <= $mark1) && ($mark1 <= $max)) && (($min <= $mark2) && ($mark2 <= $max)) && (($min <= $mark3) && ($mark3 <= $max)) && (($min <= $mark4) && ($mark4 <= $max)) && (($min <= $mark5) && ($mark5 <= $max)) && (($min <= $mark6) && ($mark6 <= $max)) && (($min <= $mark7) && ($mark7 <= $max)) && (($min <= $mark8) && ($mark8 <= $max)) && (($min <= $mark9) && ($mark9 <= $max)))
                        {
                        $total=($mark1 + $mark2 + $mark3 + $mark4 + $mark5 + $mark6 + $mark7 + $mark8 + $mark9);
                        $avg = ($total/$rating);
                        $avg = round($avg);
                        $evaluation->setMark1($mark1);
                        $evaluation->setMark2($mark2);
                        $evaluation->setMark3($mark3);
                        $evaluation->setMark4($mark4);
                        $evaluation->setMark5($mark5);
                        $evaluation->setMark6($mark6);
                        $evaluation->setMark7($mark7);
                        $evaluation->setMark8($mark8);
                        $evaluation->setMark9($mark9);
                        $evaluation->setTotal($total);
                        $evaluation->setAvg($avg);
                        $evaluation->setAcademicyear($ayear);
                        $evaluation->setDate($today);
                        if(!empty($comment)){
                          $evaluation->setComments($comment);
                        }
                        $evaluation->setStudentMark($student);
                        $evaluation->addProgramMark($material);
                        $em->persist($evaluation);
                        $em->flush();
                        return $this->redirect($this->generateUrl('evaluate'));
                    }
                      else
                  {
                         $this->addFlash('success','Please use rating scale.. 1,2,3,4,5 to answer');
                         return $this->render('student_evaluation/eval.html.twig',['findevaluate'=>$findevaluation,'pname'=>$pname,'today'=>$today,'userof'=>$userof]);
                  }
                  }
                   else
                  {
                         $this->addFlash('success','Your required to fill all fields');
                         return $this->render('student_evaluation/eval.html.twig',['findevaluate'=>$findevaluation,'pname'=>$pname,'today'=>$today,'userof'=>$userof]);
                  }
                  }
               return $this->render('student_evaluation/eval.html.twig',['findevaluate'=>$findevaluation,'pname'=>$pname,'today'=>$today,'userof'=>$userof]);
    }


     /**
     * @Route("/evaluate/result/view/begin/final/verify/{program}", name="evaluate_mark")
     */
    public function evaluateResult($program): Response
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

       return $this->render('setting/evalresult.html.twig',['mark'=>$mark,'average'=>$average,'userof'=>$userof,'pname'=>$pname, 'comment' => $program,'findcomment'=>$comment]);
    }


      /**
     * @Route("/user/result/view/comment/begin/final/verify/{program}", name="comment")
     */
    public function Comment($program): Response
    {
        $em=$this->getDoctrine()->getManager();
        $connection=$em->getConnection();
        $material =$this->getDoctrine()->getRepository(Program::class)->findOneBy(['id'=>$program]);
        $pname=$material->getPname();
        $decl11="select comments from evaluate_mark inner join evaluate_mark_program on evaluate_mark.id=evaluate_mark_program.evaluate_mark_id
                where evaluate_mark_program.program_id=:part and comments is not null";

        $statement122=$connection->prepare($decl11);
        $statement122->bindParam(':part',$program);
        $statement122->execute();
        $comment=$statement122->fetchAll();

       return $this->render('student_evaluation/comment.html.twig',['comment' => $comment,'pname'=>$pname]);
    }


}
