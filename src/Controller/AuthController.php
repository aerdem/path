<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\ResponseService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends AbstractController
{
    private $responseService;
    private $userRepository;

    public function __construct(ResponseService $responseService, UserRepository $userRepository)
    {
        $this->responseService = $responseService;
        $this->userRepository = $userRepository;
    }

    public function register(Request $request): JsonResponse
    {
        $request = $this->responseService->transformJsonBody($request);
        $firstName = $request->get('firstname');
        $lastName = $request->get('lastname');
        $username = $request->get('username');
        $password = $request->get('password');

        if (empty($username) || empty($password) ){
            return $this->responseService->respondValidationError("Kullanıcı Adı veya Parola geçersiz.");
        }

        $this->userRepository->addUser($firstName, $lastName, $username, $password);

        return $this->responseService->respondWithSuccess('Kullanıcı başarılı bir şekilde eklendi.');
    }

    /**
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        return new JsonResponse([
            'token' => $JWTManager->create($user)
        ]);
    }

    public function userList(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'username' => $user->getUsername(),
                'firstname' => $user->getFirstName(),
                'lastname' => $user->getLastName()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}