<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\MailService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @param UserService $userService
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, UserService $userService): Response
    {
        if ($this->getUser()) {
            return $userService->redirectBasedOnRoles($this->getUser());
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout()
    {
        return $this->redirectToRoute("homepage");
    }

    /**
     * @Route("/forgotPassword", name="forgotPassword", methods={"GET", "POST"})
     * @param Request $request
     * @param UserRepository $userRep
     * @param UserService $userService
     * @param MailService $mailService
     * @return Response
     */
    public function forgotPassword(Request $request, UserRepository $userRep, UserService $userService , MailService $mailService)
    {
        if ($request->getMethod() == "POST") {
            $user = $userRep->findOneBy(["email" => $request->get("email")]);

            if (!$user) {
                $this->addFlash("alert", "Utilisateur non enregistré.");

                return $this->redirectToRoute("forgotPassword");
            }

            $userService->generatePasswordToken($user);

            $entityManager = $this
                ->getDoctrine()
                ->getManager();

            $entityManager->persist($user);
            $entityManager->flush();

            $resetPasswordUrl = $this->generateUrl("resetPassword", [
                "resetPasswordToken" => $user->getPasswordToken(),
            ], false);

            $mailService->sendMailToRecipient($user, $resetPasswordUrl, "resetPassword");

            $this->addFlash("success", "Le lien pour changer votre mot de passe est dans votre boite mail.");

            $this->redirectToRoute("login");
        }

        return $this->render("security/forgotPassword.html.twig");
    }

    /**
     * @Route("/resetPassword/{resetPasswordToken}", name="resetPassword", methods={"GET", "POST"})
     * @param Request $request
     * @param UserRepository $userRep
     * @param ResetPasswordType $form
     * @param string $resetPasswordToken
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function resetPassword(Request $request, UserRepository $userRep, ResetPasswordType $form, string $resetPasswordToken, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $userRep->findOneBy(["passwordToken" => $resetPasswordToken]);

        if (!$user || $user->getPasswordToken() === null) {
            return $this->redirectToRoute("home");
        }

        $form = $this->createForm(ResetPasswordType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (password_verify($form["password"]->getData(), $user->getPassword())) {
                $this->addFlash("errorResetPassword", "Votre ancien mot de passe et le nouveau doivent être différents.");

                return $this->render("security/changePassword.html.twig", [
                    "form" => $form->createView(),
                ]);
            }

            $user->setPassword($passwordEncoder->encodePassword($user, $form["password"]->getData()));
            $user->setPasswordToken(null);

            $this
                ->getDoctrine()
                ->getManager()
                ->flush();

            $this->addFlash("successResetPassword", "Votre mot de passe a bien été modifié.");

            return $this->redirectToRoute("login");
        }

        return $this->render("security/changePassword.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/activate/{uuid}", name="account_activate")
     * @param string $uuid
     * @param UserRepository $userRepository
     * @return RedirectResponse
     */
    public function activate(string $uuid, UserRepository $userRepository)
    {
        $user = $userRepository->find($uuid);

        if (!$user || $user->getIsActive() === true) {
            return $this->redirectToRoute("home");
        }

        $user->setIsActive(true);

        $entityManager = $this
            ->getDoctrine()
            ->getManager();

        $entityManager->flush();

        $this->addFlash("successAccountActivated", "Votre compte a bien été activé, vous pouvez désormais vous connecter.");

        return $this->redirectToRoute("login");
    }
}