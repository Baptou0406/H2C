<?php
// src/Controller/HashPasswordController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class HashPasswordController extends AbstractController
{
    #[Route('/hash-password', name: 'hash_password')]
    public function hashPassword(UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            'Jury2025'  // Le mot de passe que vous voulez utiliser
        );
        
        return new Response("Mot de passe hashé : " . $hashedPassword);
    }
}