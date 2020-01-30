<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\CandidatType;
use App\Form\RecruiterType;
use App\Service\MailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/candidate/register", name="candidate_register", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function newCandidate(Request $request, UserPasswordEncoderInterface $encoder, MailService $mailService): Response
    {
        $user = new User();

        $form = $this->createForm(CandidatType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["legalConditions"]->getData() === true) {
                $password = $encoder->encodePassword($user, $user->getPassword());

                $user->setPassword($password);
                $user->setRoles(['ROLE_CANDIDAT']);

                $entityManager = $this
                    ->getDoctrine()
                    ->getManager();

                $entityManager->persist($user);
                $entityManager->flush();

                $activationUrl = $this->generateUrl("account_activate", [
                    "uuid" => $user->getId(),
                ], false);

                $mailService->sendMailToRecipient($user, $activationUrl, "activationLink");

                $this->addFlash("successAccountCreated", "Votre compte nécessite désormais une activation pour être pleinement fonctionnel, veuillez cliquer sur le lien d'activation reçu par e-mail.");

                return $this->redirectToRoute("login");
            }
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/recruiter/register", name="recruiter_register", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function newRecruiter(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        $form = $this->createForm(RecruiterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["legalConditions"]->getData() === true) {
                $password = $encoder->encodePassword( $user, $user->getPassword());

                $user->setPassword( $password );
                $user->setRoles(['ROLE_RECRUITER']);

                $entityManager = $this
                    ->getDoctrine()
                    ->getManager();

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute("login");
            }
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //candidate dashbord
    //--------------------------------------------------------------------------------------------------------------------------
    /**
     * @Route("/dashbord/{id}", name= "user_dashbord", methods={"GET", "POST"})
     * 
     * @param RenewPasswordType $renewPasswordType
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $encoder
     * 
     */
    public function dashbordUser(Request $request , $user, UserPasswordEncoderInterface $encoder)
    {
         if($user) 
         {
            if( $user->getRoles === "ROLE_CANDIDAT" )
            { 
                
                    $form= $this->createForm(CandidatType::class, $user, ["legalConditions", ]);

                    $form->handleRequest($request);


                    if($form->isSubmitted() && $form->isValid())
                    {
                        $password = $encoder->encodePassword( $user, $user->getPassword());
                        $user->setPassword( $password );
                        $entityManager = $this ->getDoctrine()->getManager();

                    
                    
                        $entityManager->persist($user);
                      
                        $this->addFlash("success", "Votre profile est bien mis à jour ");
                        return $this->redirectToRoute('user_dashbord');

                    }

            } 
            // if the user is Recruiter
            elseif( $user->getRoles() === "ROLE_RECRUITER" )
            {
                $form = $this->createForm(RecruiterType::class, $user);
                if($form->isSubmitted() && $form->isValid())
                {
                    $password = $encoder->encodePassword( $user, $user->getPassword());

                    $user->setPassword( $password );
                    $entityManager = $this ->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $this->addFlash("success", "Votre profile est bien mis à jour ");
                    return $this->redirectToRoute('user_dashbord');    
                }
            }
            else
            {
                $this->addFlash("info", " vous éte ni candidat ni entreprise ");
            }
            $entityManager->flush();  
         }  
         
         
           return $this->render('/user/dashbord.html.twig', [
               "form" => $form->createView(),
               "user" => $user
           ]) ;
    }



}
