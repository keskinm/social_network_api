<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;

#[Route('/api', name: 'user')]
class UserController extends AbstractController
{
    private SerializerInterface $serializer;
    private UserPasswordHasherInterface $hasher;
    private EntityManagerInterface $entityManager;
    private array $headers = ['Content-Type' => 'application/json'];

    public function __construct(SerializerInterface $serializer, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
    }

    #[Route('/user', name: 'current_user',methods: ['GET'],)]
    public function getCurrentUser(): Response
    {
        return new Response($this->serializer->serialize($this->getUser(), 'json'), Response::HTTP_OK, $this->headers);
    }

    #[Route('/updateUserField', name: 'updateUserField')]
    public function updateUserField(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $repository = $this->getDoctrine()->getRepository(User::class);
        $result = $repository->updateUserField($data);
        return new Response($this->serializer->serialize($result, 'json'), Response::HTTP_OK, $this->headers);
    }

    #[Route('/getUserFields', name: 'getUserFields')]
    public function getUserFields(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $repository = $this->getDoctrine()->getRepository(User::class);
        $result = $repository->getUserFields($data);
        return new Response($this->serializer->serialize($result, 'json'), Response::HTTP_OK, $this->headers);
    }

    #[Route('/register', name: 'register_user', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher,): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(UserType::class, new User());
        $form->submit($data);
        if ($form->isValid()) {
            $user = $form->getData();
            $user->setPassword($this->hasher->hashPassword($user, $user->getPassword()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return new Response($this->serializer->serialize($user, 'json'), Response::HTTP_OK, $this->headers);
        } else {
            return new Response($this->serializer->serialize($form->getErrors(true,true), 'json'), Response::HTTP_BAD_REQUEST, $this->headers);

        }

    }
            //TODO Connexion email / username
}
