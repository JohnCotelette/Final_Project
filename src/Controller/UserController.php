<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\Cv;
use App\Entity\Business;
use App\Entity\User;
use App\Form\AvatarType;
use App\Form\CandidatType;
use App\Form\CvType;
use App\Form\RecruiterType;
use App\Repository\BusinessRepository;
use App\Service\MailService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @var UserService
     */
    private $userService;

    /**
     * @var MailService
     */
    private $mailService;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserController constructor.
     * @param UserService $userService
     * @param MailService $mailService
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserService $userService, MailService $mailService, UserPasswordEncoderInterface $encoder) {
        $this->userService = $userService;
        $this->mailService = $mailService;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/candidate/register", name="candidate_register", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function newCandidate(Request $request): Response
    {
        $user = $this->getUser();

        if ($user) {
            return $this->userService->redirectBasedOnRoles($user);
        }

        $user = new User();

        $form = $this->createForm(CandidatType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["legalConditions"]->getData() === true) {
                $password = $this->encoder->encodePassword($user, $user->getPassword());

                $user
                    ->setPassword($password)
                    ->setRoles(['ROLE_CANDIDATE']);

                $entityManager = $this
                    ->getDoctrine()
                    ->getManager();

                $entityManager->persist($user);
                $entityManager->flush();

                $activationUrl = $this->generateUrl("account_activate", [
                    "uuid" => $user->getId(),
                ], false);

                $this->mailService->sendMailToRecipient($user, $activationUrl, "activationLink");

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
     * @param BusinessRepository $businessRepository
     * @return Response
     */
    public function newRecruiter(Request $request, BusinessRepository $businessRepository): Response
    {
        $user = $this->getUser();

        if ($user) {
            {
                return $this->userService->redirectBasedOnRoles($user);
            }
        }

        $user = new User();

        $form = $this->createForm(RecruiterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["legalConditions"]->getData() === true) {
                $siret = $form["business"]->getData();

                $business = new Business();

                $business->setSiretNumber($siret);

                $password = $this->encoder->encodePassword($user, $user->getPassword());

                $user
                    ->setPassword($password)
                    ->setRoles(['ROLE_RECRUITER'])
                    ->setBusiness($business);

                $entityManager = $this
                    ->getDoctrine()
                    ->getManager();

                $entityManager->persist($user);
                $entityManager->flush();

                $activationUrl = $this->generateUrl("account_activate", [
                    "uuid" => $user->getId(),
                ], false);

                $this->mailService->sendMailToRecipient($user, $activationUrl, "activationLink");

                $this->addFlash("successAccountCreated", "Votre compte nécessite désormais une activation pour être pleinement fonctionnel, veuillez cliquer sur le lien d'activation reçu par e-mail.");

                return $this->redirectToRoute("login");
            }
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/candidate/applications", name="candidate_applications")
    */
    public function candidateApplications()
    {
        $user = $this->getUser();
        $applications = $user->getApplications();

        return $this->render('/user/dashboard/candidate/applicationCandidate.html.twig', [
             "applications" => $applications,
             "user" => $user
        ]);
    } 
    
    /**
     * @Route("/dashboard/candidate/profile", name="candidate_profile")
     * @param Request $request
     * @return RedirectResponse|response
    */
    public function candidateProfile(Request $request )
    {
        $user =$this->getUser();
        $avatar = new Avatar();

        if($user)
        {
            $formAvatar = $this->createForm(AvatarType::class, $avatar);
            $formAvatar->handleRequest($request);

            if($formAvatar->isSubmitted() && $formAvatar->isValid())
            {
                $entityManager = $this->getDoctrine()->getManager();

                if($user->getAvatar())
                {
                   $entityManager->remove($user->getAvatar());
                }
               
                 $user->setAvatar($avatar);
                 $entityManager->persist($avatar);
                 $entityManager->flush();

                 $this->addFlash("success", "l'avatar est bien ajoutée");
                 $this->redirectToRoute("candidate_profile");
            }
   
            return $this->render('/user/dashboard/candidate/profileCandidate.html.twig', [
                 "user" => $user,
                 "formAvatar" => $formAvatar->createView()
            ]);
        }

       return $this->redirectToRoute("login");
    }   

    /**
     * @Route("/dashboard/candidate/updateprofile", name="candidate_update_profile")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     */
    public function candidateUpdateProfile(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
      
        if ($user) {
            $entityManager = $this ->getDoctrine()->getManager();

            $form = $this->createForm(CandidatType::class, $user);
            $form->handleRequest($request);
           
             // Update profile candidate
            if ($form->isSubmitted() && $form->isValid())
            {
                $password = $encoder->encodePassword( $user, $user->getPassword());
                $user->setPassword( $password );

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash("success", "Votre profile est bien mis à jour ");

                return $this->redirectToRoute('candidate_profile');
            }

            return $this->render('/user/dashboard/candidate/profileUpdateCandidate.html.twig', [
                "form" => $form->createView(),
                "user" => $user
            ]);
        }

        return $this->redirectToRoute("login");
    }

    /**
     * @Route("/dashboard/candidate/Cv", name= "candidate_cv")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     */
    public function candidateCV(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
      
        if ($user) 
        {
            $cv = new Cv();
            $formCv = $this->createForm(CvType::class, $cv);
            $formCv->handleRequest($request);

            // candidate change Cv
            if ($formCv->isSubmitted() && $formCv->isValid()) 
            {
                $em = $this->getDoctrine()->getManager();

                if ($user->getCv()) 
                {
                    $em->remove( $user->getCv() );
                }

                $user->setCv($cv);

                $em->persist($cv);
                $em->flush();

                $this->addFlash("success", "Votre CV  est bien mis à jour ");

                return $this->redirectToRoute("candidate_cv");
            }

            return $this->render('/user/dashboard/candidate/CvupdateCandidate.html.twig', [
                "formCv" => $formCv->createView(),
                "user" => $user
            ]);
        }

        return $this->redirectToRoute("login");
    }
}

