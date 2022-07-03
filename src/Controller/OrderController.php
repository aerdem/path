<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OrderController extends AbstractController
{
    private $orderRepository;
    /**
     * @var string
     */
    private $userId;

    public function __construct(OrderRepository $orderRepository, UserRepository $userRepository, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->orderRepository = $orderRepository;
        $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());
        $username = $decodedJwtToken['username'];
        $user = $userRepository->findOneBy(['username' => $username],null,1);
        $this->userId = $user->getId();
    }

    #[Route('/api/order', name: 'app_order')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OrderController.php',
        ]);
    }

    #[Route('/api/order/add', name: 'add_order', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $fields = array(
                'orderCode' => array("required" => true),
                'productId' => array("required" => true),
                'quantity' => array("required" => true),
                'address' => array("required" => true),
                'shippingDate' => array("required" => false),
            );


            foreach ($fields as $key => $field) {
                if(!array_key_exists($key,$data)){
                    throw new \Exception("Eksik parametre => '$key'", Response::HTTP_BAD_REQUEST);
                }

                if($field['required'] && !$data[$key]){
                    throw new \Exception("Zorunlu alanlar girilmelidir => '$key'", Response::HTTP_BAD_REQUEST);
                }
            }
            $data['createdBy'] = $this->userId;

            $this->orderRepository->addOrder($data);
            return new JsonResponse(['status' => 'Order ekleme işlemi başarılı.'], Response::HTTP_CREATED);
        } catch (\Exception $ex){
            return new JsonResponse(['status' => $ex->getMessage()],$ex->getCode());
        }
    }

    #[Route('/api/order/update/{id}', name: 'update_order', methods: ['PUT'])]
    public function update($id, Request $request): JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id' => $id, 'createdBy' => $this->userId]);

        if (empty($order)) {
            return new JsonResponse(['status' => 'Order bulunamadı.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        empty($data['shippingDate']) ? true : $order->setShippingDate(new \DateTime('@'.strtotime($data['shippingDate'])));
        $this->orderRepository->update($order,true);

        return new JsonResponse(['status' => 'Order güncelleme işlemi başarılı.'], Response::HTTP_OK);
    }

    #[Route('/api/orders/{id}', name: 'get_order', methods: ['GET'])]
    public function get(string $id): object
    {
        $order = $this->orderRepository->findOneBy(['id' => $id, 'createdBy' => $this->userId]);

//        echo "<pre>";
//        print_r($id);
//        print_r($order);
//        echo "asd";
//        exit;

        if (empty($order)) {
            return new JsonResponse(['status' => 'Order bulunamadı.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $order->getId(),
            'orderCode' => $order->getOrderCode(),
            'productId' => $order->getProductId(),
            'quantity' => $order->getQuantity(),
            'address' => $order->getAddress(),
            'shippingDate' => $order->getShippingDate()
        ], Response::HTTP_OK);
    }

    #[Route('/api/orders', name: 'get_orders', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $orders = $this->orderRepository->findBy(['createdBy' => $this->userId],null,1);

        $data = [];

        foreach ($orders as $order) {
            $data[] = [
                'id' => $order->getId(),
                'orderCode' => $order->getOrderCode(),
                'productId' => $order->getProductId(),
                'quantity' => $order->getQuantity(),
                'address' => $order->getAddress(),
                'shippingDate' => $order->getShippingDate(),
                'createdBy' => $order->getCreatedBy()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
