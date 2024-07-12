<?php

namespace App\Controller;

use App\Form\EditPasswordType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $user->setEmail($data->getEmail());
        }

        $formEditPassword = $this->createForm(EditPasswordType::class);
        $formEditPassword->handleRequest($request);
        if($formEditPassword->isSubmitted() && $formEditPassword->isValid())
        {
            $data = $formEditPassword->getData();
        }

        return $this->render('user/index.html.twig', [
            "user" => $this->getUser(),
            "form" => $form,
            "form_edit_password" => $formEditPassword
        ]);
    }
}
