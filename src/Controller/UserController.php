<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Entity\User;
use App\Entity\Avatar;
use App\Form\AvatarType;
use App\Form\BusinessType;
use App\Form\CandidatType;
use App\Form\CvType;
use App\Form\EditUserType;
use App\Form\RecruiterType;
use App\Form\ResetPasswordDashboardType;
use App\Service\MailService;
use App\Service\UserService;
use App\Repository\BusinessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UserController constructor.
     * @param UserService $userService
     * @param MailService $mailService
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserService $userService, MailService $mailService, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager) {
        $this->userService = $userService;
        $this->mailService = $mailService;
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/candidate/register", name="candidate_register", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function newCandidate(Request $request): Response
    {

        if ($user = $this->getUser()) {
            return $this->userService->redirectBasedOnRoles($user);
        }

        $user = new User();

        $form = $this->createForm(CandidatType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->encoder->encodePassword($user, $user->getPassword());

            $user
                ->setPassword($password)
                ->setRoles(['ROLE_CANDIDATE']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $activationUrl = $this->generateUrl("account_activate", [
                "uuid" => $user->getId(),
            ], false);

            $this->mailService->sendMailToRecipient($user, $activationUrl, "activationLink");

            $this->addFlash("successAccountCreated", "Votre compte nécessite désormais une activation pour être pleinement fonctionnel, veuillez cliquer sur le lien d'activation reçu par e-mail.");

            return $this->redirectToRoute("login");
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
    public function newRecruiter(Request $request): Response
    {
        if ($user = $this->getUser()) {
            {
                return $this->userService->redirectBasedOnRoles($user);
            }
        }

        $user = new User();

        $form = $this->createForm(RecruiterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->encoder->encodePassword($user, $user->getPassword());

            $business = $request->attributes->get("businessItem");

            $user
                ->setPassword($password)
                ->setRoles(['ROLE_RECRUITER'])
                ->setBusiness($business);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $activationUrl = $this->generateUrl("account_activate", [
                "uuid" => $user->getId(),
            ], false);

            $this->mailService->sendMailToRecipient($user, $activationUrl, "activationLink");

            $this->addFlash("successAccountCreated", "Votre compte nécessite désormais une activation pour être pleinement fonctionnel, veuillez cliquer sur le lien d'activation reçu par e-mail.");

            return $this->redirectToRoute("login");
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/candidate/dashboard", name="candidate_dashboard", methods={"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|response
    */
    public function candidateProfile(Request $request)
    {
        $user = $this->getUser();
        $avatar = new Avatar();

        $formAvatar = $this->createForm(AvatarType::class, $avatar);
        $formAvatar->handleRequest($request);

        if ($formAvatar->isSubmitted() && $formAvatar->isValid())
        {
            if ($user->getAvatar())
            {
                $this->entityManager->remove($user->getAvatar());
            }

                $user->setAvatar($avatar);
                $this->entityManager->persist($avatar);
                $this->entityManager->flush();

                $this->redirectToRoute("candidate_dashboard");
        }

        return $this->render('/user/dashboard/candidate/profileCandidate.html.twig', [
             "formAvatar" => $formAvatar->createView(),
        ]);
    }

    /**
     * @Route("/candidate/dashboard/updateprofile", name="candidate_dashboard_updateprofile", methods={"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function candidateUpdateProfile(Request $request)
    {
        $user = $this->getUser();

        $formProfile = $this->createForm(EditUserType::class, $user);

        $formResetPassword = $this->createForm(ResetPasswordDashboardType::class, null, [
            "ResetPasswordDashboard" => true,
        ]);

        $formDeleteAccount = $this->createForm(ResetPasswordDashboardType::class, null);

        $formProfile->handleRequest($request);
        $formResetPassword->handleRequest($request);
        $formDeleteAccount->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $this->entityManager->flush();

            $this->addFlash("successProfileUpdated", "Votre profil a bien été mis à jour.");

            return $this->redirectToRoute('candidate_dashboard');
        }

        if ($formResetPassword->isSubmitted() && $formResetPassword->isValid()) {
            $user->setPassword($this->encoder->encodePassword($user, $formResetPassword["newPassword"]->getData()));

            $this->entityManager->flush();

            $this->addFlash("successPasswordChanged", "Votre mot de passe a bien été modifié.");

            return $this->redirectToRoute('candidate_dashboard');
        }

        if ($formDeleteAccount->isSubmitted() && $formDeleteAccount->isValid()) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            return $this->redirectToRoute("home");
        }

        return $this->render('/user/dashboard/candidate/profileUpdateCandidate.html.twig', [
            "formProfile" => $formProfile->createView(),
            "formResetPassword" => $formResetPassword->createView(),
            "formDeleteAccount" => $formDeleteAccount->createView(),
        ]);
    }

    /**
     * @Route("/candidate/dashboard/applications", name="candidate_dashboard_applications", methods={"GET"})
     */
    public function candidateApplications()
    {
        return $this->render("/user/dashboard/candidate/applicationsCandidate.html.twig");
    }

    /**
     * @Route("/candidate/dashboard/cv", name= "candidate_dashboard_cv")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function candidateCV(Request $request)
    {
        $user = $this->getUser();

        $cv = new Cv();

        $formCv = $this->createForm(CvType::class, $cv);

        $formCv->handleRequest($request);

        if ($formCv->isSubmitted() && $formCv->isValid())
        {
            if ($user->getCv())
            {
                $this->entityManager->remove($user->getCv());
            }

            $user->setCv($cv);

            $this->entityManager->persist($cv);
            $this->entityManager->flush();

            $this->addFlash("successCvUpdated", "Votre CV a bien été mis à jour.");

            return $this->redirectToRoute("candidate_dashboard_cv");
        }

        return $this->render('/user/dashboard/candidate/cvUpdateCandidate.html.twig', [
            "formCv" => $formCv->createView(),
        ]);
    }

    /**
     * @Route("/recruiter/dashboard", name="recruiter_dashboard", methods={"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|response
     */
    public function recruiterProfile(Request $request)
    {
        $user = $this->getUser();
        $avatar = new Avatar();

        $formAvatar = $this->createForm(AvatarType::class, $avatar);
        $formAvatar->handleRequest($request);

        if ($formAvatar->isSubmitted() && $formAvatar->isValid())
        {
            if ($user->getAvatar())
            {
                $this->entityManager->remove($user->getAvatar());
            }

            $user->setAvatar($avatar);
            $this->entityManager->persist($avatar);
            $this->entityManager->flush();

            $this->redirectToRoute("recruiter_dashboard");
        }

        return $this->render('/user/dashboard/recruiter/profileRecruiter.html.twig', [
            "formAvatar" => $formAvatar->createView(),
        ]);
    }

    /**
     * @Route("/recruiter/dashboard/updateprofile", name="recruiter_dashboard_updateprofile", methods={"GET", "POST"})
     * @param Request $request
     * @param TokenStorageInterface $tokenStorageInterface
     * @return RedirectResponse|Response
     */
    public function recruiterUpdateProfile(Request $request, TokenStorageInterface $tokenStorageInterface)
    {
        $user = $this->getUser();
        $avatar = new Avatar();

        $formProfile = $this->createForm(EditUserType::class, $user);
        $formBusiness = $this->createForm(BusinessType::class, $user->getBusiness());
        $formResetPassword = $this->createForm(ResetPasswordDashboardType::class, null, [
            "ResetPasswordDashboard" => true,
        ]);
        $formDeleteAccount = $this->createForm(ResetPasswordDashboardType::class, null);
        $formAvatar = $this->createForm(AvatarType::class, $avatar);

        $formProfile->handleRequest($request);
        $formResetPassword->handleRequest($request);
        $formDeleteAccount->handleRequest($request);
        $formBusiness->handleRequest($request);
        $formAvatar->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $this->entityManager->flush();

            $this->addFlash("successProfileUpdated", "Votre profil a bien été mis à jour.");

            return $this->redirectToRoute('recruiter_dashboard');
        }

        if ($formBusiness->isSubmitted() && $formBusiness->isValid()) {
            $this->entityManager->flush();

            $this->addFlash("successBusinessUpdated", "Votre entreprise a bien été mis à jour.");

            return $this->redirectToRoute('recruiter_dashboard');
        }

        if ($formResetPassword->isSubmitted() && $formResetPassword->isValid()) {
            $user->setPassword($this->encoder->encodePassword($user, $formResetPassword["newPassword"]->getData()));

            $this->addFlash("successPasswordChanged", "Votre mot de passe a bien été modifié.");

            $this->entityManager->flush();

            return $this->redirectToRoute('recruiter_dashboard');
        }

        if ($formDeleteAccount->isSubmitted() && $formDeleteAccount->isValid()) {
            $tokenStorageInterface->setToken(null);
            $request->getSession()->invalidate();

            $this->entityManager->remove($user);
            $this->entityManager->flush();

            return $this->redirectToRoute("home");
        }

        if ($formAvatar->isSubmitted() && $formAvatar->isValid()) {
            /* @var User $user */

            if ($user->getBusiness()->getAvatar())
            {
                $this->entityManager->remove($user->getBusiness()->getAvatar());
            }

            $user->getBusiness()->setAvatar($avatar);

            $this->entityManager->persist($avatar);
            $this->entityManager->flush();

            return $this->redirectToRoute("recruiter_dashboard_updateprofile");
        }


        return $this->render('/user/dashboard/recruiter/profileUpdateRecruiter.html.twig', [
            "formProfile" => $formProfile->createView(),
            "formResetPassword" => $formResetPassword->createView(),
            "formDeleteAccount" => $formDeleteAccount->createView(),
            "formBusiness" => $formBusiness->createView(),
            "formAvatar" => $formAvatar->createView(),
        ]);
    }

    /**
     * @Route("/recruiter/dashboard/offers", name="recruiter_dashboard_offers", methods={"GET"})
     * @return Response
     */
    public function recruitersOffers()
    {
        return $this->render("user/dashboard/recruiter/offersRecruiter.html.twig");
    }

    /**
     * @Route("/profile/{id}", name="public_profile", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function publicProfile(User $user)
    {
        if ($user->getRoles() === ["ROLE_CANDIDATE"]) {
            return $this->render("user/publicProfile.html.twig", [
                "user" => $user,
            ]);
        }

        return $this->redirectToRoute("home");
    }
}

