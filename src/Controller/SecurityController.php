<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Entity\Department;
use App\Entity\Course;
use App\Entity\Program;
use App\Entity\Evaluation;

class SecurityController extends AbstractController
{
    /**
     * @Route("/{login}", name="app_login", defaults={"login":null})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
          if($this->isGranted('ROLE_ADMIN')){
             return $this->RedirectToRoute('admin_evaluate');
         }
        if($this->isGranted('ROLE_TEACHER')){
            return $this->RedirectToRoute('hods');
       }
       if($this->isGranted('ROLE_STUDENT')){
            return $this->RedirectToRoute('student_instruction');
       }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout/session", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

/**
     * @Route("/evaluate/settings/switch/on", name="settingswitch")
     */
    public function settingsswitch(\Swift_Mailer $mailer): Response
    {
       $user1=$this->getUser();
        if($user1 != null)
        {
          $id=1;
                $idnam=$user1->getId();
                $entityManager=$this->getDoctrine()->getManager();
             $connection= $entityManager->getConnection();
            //symfony sql statement to print subject
         $decl1211="SELECT id   FROM
           user where title='student'";
          $statement9711=$connection->prepare($decl1211);
          $statement9711->execute();
          $student=$statement9711->fetchAll();
           $findevaluate =$this->getDoctrine()->getRepository(Evaluation::class)->findOneBy(['id'=>$id]);

            try{
            foreach($student as $i=>$data)
             {
            $test =$this->getDoctrine()->getRepository(User::class)->findOneBy(['id'=>$data]);
            $email=$test->getEmail();
            $message = (new \Swift_Message('NIT OLES'))
                                ->setFrom('nitqualitycontroller@gmail.com')
                                ->setTo($email)
                                ->setBody(
                                    "We are glad to say our evaluation form for lecturer assessment is now active.
                                      You are required to visit it  so that you can evaluate lectures based on course moduli"
                                );
                           $mailer->send($message);
                     }
              $this->addFlash('network','Notification sent to students via email.....');
             $findevaluate->setAddstatus('active');
             $entityManager->persist($findevaluate);
             $entityManager->flush();
            } catch (\Swift_TransportException $e) {
             $this->addFlash('network',$e->getMessage());
            }

             return $this->redirectToRoute('activate');
             }
        else
        {

            return $this->redirectToRoute('app_login');
        }
    }

/**
     * @Route("/evaluate/settings/switch/off/on", name="settingswitchoff")
     */
    public function settingsswitchoff(): Response
    {
             $user1=$this->getUser();
        if($user1 != null)
        {
               $id=1;
                $idnam=$user1->getId();
                $em=$this->getDoctrine()->getManager();
                $findleaves =$this->getDoctrine()->getRepository(Evaluation::class)->findOneBy(['id'=>$id]);
                     $findleaves->setAddstatus('inactive');
                     $em->persist($findleaves);
                     $em->flush();
                    return $this->redirectToRoute('activate');
        }
        else
        {

            return $this->redirectToRoute('app_login');
        }
    }


/**
     * @Route("/evaluate/settings/semester1/switch/on", name="settingsemester1")
     */
    public function settingsemester1(): Response
    {
             $user1=$this->getUser();
        if($user1 != null)
        {
          $id=1;
                $idnam=$user1->getId();
                $em=$this->getDoctrine()->getManager();
                $findevaluate =$this->getDoctrine()->getRepository(Evaluation::class)->findOneBy(['id'=>$id]);
                    $findevaluate->setSemester('semester1');
                     $em->persist($findevaluate);
                     $em->flush();
                    return $this->redirectToRoute('activate');
        }
        else
        {

            return $this->redirectToRoute('app_login');
        }
    }

/**
     * @Route("/evaluate/settings/semester2/switch/off/on", name="settingsemester2")
     */
    public function settingsemester2(): Response
    {
             $user1=$this->getUser();
        if($user1 != null)
        {
               $id=1;
                $idnam=$user1->getId();
                $em=$this->getDoctrine()->getManager();
                $findleaves =$this->getDoctrine()->getRepository(Evaluation::class)->findOneBy(['id'=>$id]);
                     $findleaves->setSemester('semester2');
                     $em->persist($findleaves);
                     $em->flush();
                    return $this->redirectToRoute('activate');
        }
        else
        {
            return $this->redirectToRoute('app_login');
        }
    }



}
