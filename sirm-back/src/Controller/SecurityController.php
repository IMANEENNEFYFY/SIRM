<?php
namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/api/auth', name: 'api_auth', methods: ['POST'])]
    public function login(
        Request $request,
        UtilisateurRepository $repo,
        UserPasswordHasherInterface $hasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['login'], $data['motDePasse'])) {
            return $this->json(['error' => 'Login et motDePasse requis'], 400);
        }

        $user = $repo->findOneBy(['login' => $data['login']]);

        if (!$user || !$hasher->isPasswordValid($user, $data['motDePasse'])) {
            return $this->json(['error' => 'Identifiants invalides'], 401);
        }

        if (!$user->isActif()) {
            return $this->json(['error' => 'Compte désactivé'], 401);
        }

        $payload = [
            'userId'   => $user->getId(),
            'login'    => $user->getLogin(),
            'role'     => $user->getRole()->value,
            'iat'      => time(),
            'exp'      => time() + (8 * 3600),
        ];

        $token = JWT::encode($payload, $_ENV['APP_SECRET'], 'HS256');

        return $this->json(['token' => $token]);
    }
}