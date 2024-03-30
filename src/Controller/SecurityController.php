<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    #[Route('/connexion', name: 'security.login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Vérifie si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            // Récupére les rôles de l'utilisateur connecté
            $roles = $this->getUser()->getRoles();

            // Vérifie si l'utilisateur a le rôle admin
            if (in_array('ROLE_ADMIN', $roles, true)) {
                // Redirige l'utilisateur vers la page d'accueil admin
                return $this->redirectToRoute('homepage_admin');
            } else {
                // Redirige l'utilisateur vers la page d'accueil utilisateur
                return $this->redirectToRoute('/');
            }
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        // Gérer les erreurs de connexion
        if (!is_string($lastUsername)) {
            $lastUsername = '';
        }
        
        return $this->render('pages/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
        
    }


    #[Route('/deconnexion', 'security.logout')]
    public function logout()
    {
        // Nothing to do here..
    }


    #[Route('/inscription', 'security.registration', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé.'
            );

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security.login');
        }

        return $this->render('pages/security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

