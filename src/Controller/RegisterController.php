<?php

namespace App\Controller;

use App\Dto\RegisterRequest;
use App\Entity\User;
use App\Entity\Concierge;
use App\Entity\Proprietaire;
use App\Repository\ConciergerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        ConciergerieRepository $conciergerieRepository
    ) : JsonResponse {
        $data = json_decode($request->getContent(), true);

       $required = ["email", 'password', 'nom', 'prenom', 'telephone', 'codeSynchro'];
       foreach ($required as $field) {
        if (!isset($data[$field])){
            return new JsonResponse(["error" => "Missing field: $field"], 400);
        }
       } 

       if ($em->getRepository(User::class)->findOneBy(['email' => $data['email']])) {
            return new JsonResponse(["error" => "Email already exists"], 400);
        }

        $conciergerie = $conciergerieRepository->findOneBy(['codeSynchro' => $data['codeSynchro']]);

        if (!$conciergerie) {
            return new JsonResponse(["error" => "Invalid codeSynchro"], 404);
        }

         // Création user
        $user = new User();
        $user->setEmail($data['email']);
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setTelephone($data['telephone']);
        $user->setRoles(['ROLE_USER']);
        $user->setConciergerie($conciergerie);

        // Hash mdp
        $hashed = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashed);

        

        if(isset($data["couleur"])){
            $proprietaire = new Proprietaire();
            $proprietaire->setUser($user);
            $proprietaire->setCouleur($data['couleur']);
            $user->setType("PROPRIETAIRE");
            $em->persist($user);
            $em->persist($proprietaire);
        }else{
            $concierge = new Concierge();
            $concierge->setUser($user);
            $user->setType("CONCIERGE");
            $em->persist($user);
            $em->persist($concierge);
        }

        
        $em->flush();

        return new JsonResponse([
            "success" => true,
            "message" => "User created successfully"
        ], 201);

    }
}