<?php

namespace App\Controller;

use App\Entity\Business;
use App\Entity\User;
use App\Form\CandidatType;
use App\Form\RecruiterType;
use App\Repository\BusinessRepository;
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
     * @param MailService $mailService
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
     * @param MailService $mailService
     * @param BusinessRepository $businessRepository
     * @return Response
     */
    public function newRecruiter(Request $request, UserPasswordEncoderInterface $encoder, MailService $mailService, BusinessRepository $businessRepository): Response
    {
        $user = new User();

        $form = $this->createForm(RecruiterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["legalConditions"]->getData() === true) {
                $siret = $form["business"]->getData();

                $business = new Business();

                $business->setSiretNumber($siret);

                $password = $encoder->encodePassword($user, $user->getPassword());

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

                $mailService->sendMailToRecipient($user, $activationUrl, "activationLink");

                $this->addFlash("successAccountCreated", "Votre compte nécessite désormais une activation pour être pleinement fonctionnel, veuillez cliquer sur le lien d'activation reçu par e-mail.");

                return $this->redirectToRoute("login");
            }
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
