<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use App\Form\RegistrationType;

class SecurityController extends Controller
{
    /**
     * @Route("/inscription", name="security_regisration")
     */
    public function registration(){

        $user = new User();
        //relier le formulaire avec l'user en cours.
        $form = $this->createForm(RegistrationType::class, $user);

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
