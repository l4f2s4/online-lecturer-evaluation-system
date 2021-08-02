<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
use App\Entity\User;
use App\Entity\Department;
use App\Entity\Course;
use App\Entity\Program;
use App\Entity\Evaluation;

class SettingController extends AbstractController
{
    /**
     * @Route("/evaluation/page/setting", name="setting")
     */
    public function evaluation(): Response
    {
         $user1=$this->getUser();
         if($user1 != null)
          {
        $em=$this->getDoctrine()->getManager();
        //symfony doctrine handle
        $findevaluate =$this->getDoctrine()->getRepository(Evaluation::class)->findAll();
        return $this->render('setting/settingnavbar.html.twig', [
            'controller_name' => 'SettingController','findevaluate'=>$findevaluate
        ]);
        }
        else{
          return $this->redirectToRoute('app_login');
        }
    }
       /**
     * @Route("/evaluation/page/setting/activate", name="activate")
     */
    public function evaluationsetting(): Response
    {
         $user1=$this->getUser();
         if($user1 != null)
          {
           $findevaluate =$this->getDoctrine()->getRepository(Evaluation::class)->findAll();
        return $this->render('setting/evaluationsetting.html.twig', ['findevaluate'=>$findevaluate,
            'controller_name' => 'SettingController'
        ]);
        }
        else{
          return $this->redirectToRoute('app_login');
        }
    }

 /**
     * @Route("/setting/update/evaluation/question",name="question_edit")
     */
public function question(Request $request)
{
         $user=$this->getUser();
        if($user != null){
        $id=1;
        $user4 =$this->getDoctrine()->getRepository(Evaluation::class)->findOneBy(['id' => $id]);
       $form = $this->createFormBuilder($user4)

        ->add('q1',TextType::class, array('label'=>'Question 1','attr' => array('class' => 'form-control font-weight-bold')))
        ->add('q2',TextType::class, array('label'=>'Question 2','attr' => array('class' => 'form-control font-weight-bold')))
        ->add('q3',TextType::class, array('label'=>'Question 3','attr' => array('class' => 'form-control font-weight-bold')))
        ->add('q4',TextType::class, array('label'=>'Question 4','attr' => array('class' => 'form-control font-weight-bold')))
        ->add('q5',TextType::class, array('label'=>'Question 5','attr' => array('class' => 'form-control font-weight-bold')))
        ->add('q6',TextType::class, array('label'=>'Question 6','attr' => array('class' => 'form-control font-weight-bold')))
        ->add('q7',TextType::class, array('label'=>'Question 7','attr' => array('class' => 'form-control font-weight-bold')))
        ->add('q8',TextType::class, array('label'=>'Question 8','attr' => array('class' => 'form-control font-weight-bold')))
        ->add('q9',TextType::class, array('label'=>'Question 9','attr' => array('class' => 'form-control font-weight-bold')))
        ->add('save',SubmitType::class, array(
            'label' => 'save',
            'attr' => array('class' => 'btn btn-primary submit-btn')
        ))
        ->getForm();

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirect($this->generateUrl('setting'));
        }
        return $this->render('profile/questionedit.html.twig',array('form'=>$form->createView()

                ));
         }else
         {
                    return $this->redirectToRoute('app_login');
          }
    }
     /**
     * @Route("/external/transfer/email", name="external")
     */
    public function forgottenPassword(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator
    ): Response
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('kind');
             if(!empty($email)){
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('verify', 'Email not found!');
                return $this->render('security/index.html.twig');
            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('verify','Connection problem');
                return $this->render('security/index.html.twig');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Password Reset Link For NIT OLES'))
                ->setFrom('nitqualitycontroller@gmail.com')
                ->setTo($user->getEmail())

                ->setBody(
                    "Please Click On This link : " . $url." to reset your password.",
                    'text/html'
                );



              if (!$mailer->send($message))
            {
              echo "Failures:";
              print_r($failures);
            }
              $mailer->send($message);

            return $this->redirectToRoute('app_login');
             }
             else{
                $this->addFlash('verify', 'This field cannot be empty');
                return $this->render('security/index.html.twig');
             }
        }

        return $this->render('security/index.html.twig');
     }

 /**
     * @Route("/reset_password/change/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

 if ($request->isMethod('POST'))
     {
           $newpass=$request->request->get('newpass');
           $conf=$request->request->get('confirm');
           if(!empty($newpass) || !empty($conf))
           {
                if(strlen($newpass)>7)
                {
                     if($newpass==$conf)
                       {
                            $entityManager = $this->getDoctrine()->getManager();

                            $user = $entityManager->getRepository(User::class)->findOneBy(['ResetToken' => $token]);
                            /* @var $user User */

                            if ($user === null) {
                                $this->addFlash('dangerToken', 'Token Incorrect');
                                return $this->render('security/reset_password.html.twig', ['token' => $token]);
                            }

                            $user->setResetToken(null);
                            $user->setPassword($passwordEncoder->encodePassword($user, $conf));
                            $entityManager->flush();


                            return $this->redirectToRoute('app_login');
                     }
                    else
                    {
                            $this->addFlash('dangerToken',  'Password mismatch!');
                            return $this->render('security/reset_password.html.twig', ['token' => $token]);
                    }
                }
             else
                {
                    $this->addFlash('dangerToken', 'Password must be at least 8 characters!');
                    return $this->render('security/reset_password.html.twig', ['token' => $token]);
               }
          }
         else
         {
                $this->addFlash('dangerToken',  'All fields are required!');
                return $this->render('security/reset_password.html.twig', ['token' => $token]);
         }
    }
   else
    {

        return $this->render('security/reset_password.html.twig', ['token' => $token]);
    }

}
}
