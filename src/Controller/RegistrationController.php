<?php

namespace App\Controller;

use App\Entity\AppsUtilisateur;
use App\Security\EmailVerifier;
use App\Entity\DefAppsUtilisateur;
use App\Entity\Roles;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[IsGranted('ROLE_ADMIN')]
class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $AppsUser = new AppsUtilisateur();
        $DefAppsUser = new DefAppsUtilisateur();

        $form = $this->createForm(RegistrationFormType::class, $AppsUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $DefAppsUser
                ->setPrenom($form->get('Prenom')->getData())
                ->setNom($form->get('Nom')->getData())
                ->setAdresse($form->get('Adresse')->getData())
                ->setCP($form->get('CP')->getData())
                ->setVille($form->get('Ville')->getData())
                ->setMail($form->get('Mail')->getData())
                ->setTel1($form->get('Tel_1')->getData())
                ->setTel2($form->get('Tel_2')->getData());
        

            $roles = $form->get('roles')->getData();

            $AppsUser
                ->setNomUtilisateur($form->get('Nom_utilisateur')->getData())
                ->setColorCode($form->get('colorCode')->getData())
                ->addRole($roles)
                ->setIsVerified(true)
                ->setPassword(
                    $userPasswordHasher->hashPassword(
                        $AppsUser,
                        $form->get('plainPassword')->getData()
                    )
                )
                ->setIDUtilisateur($DefAppsUser);
                    
            $entityManager->persist($DefAppsUser);
            $entityManager->persist($AppsUser);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur à été crée avec succès! ');
            return $this->redirectToRoute('app_register');

            // $this->emailVerifier->sendEmailConfirmation('app_verify_email', $AppsUser,
            //     (new TemplatedEmail())
            //         ->from(new Address('test_mail@mrb-studio.fr', 'Bato Dashboard Verifier'))
            //         ->to($AppsUser->getIDUtilisateur()->getMail())
            //         ->subject('Please Confirm your Email')
            //         ->htmlTemplate('registration/confirmation_email.html.twig')
            // );
            // do anything else you need here, like send an email

            // return $userAuthenticator->authenticateUser(
            //     $AppsUser,
            //     $authenticator,
            //     $request
            // );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
