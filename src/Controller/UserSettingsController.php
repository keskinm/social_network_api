<?php

namespace App\Controller;

use App\Entity\UserSettings;
use App\Form\UserSettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserSettingsController extends AbstractController
{
    private SerializerInterface $serializer;
    private EntityManagerInterface $entityManager;
    private array $headers = ['Content-Type' => 'application/json'];

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    #[Route('/getUserSettingsByUserName', name: 'getUserSettingsByUserName')]
    public function getUserSettingsByUserName(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $repository = $this->getDoctrine()->getRepository(UserSettings::class);
        $query = $repository->findByUserName($data['username']);
        return new Response($this->serializer->serialize($query, 'json'), Response::HTTP_OK, $this->headers);
    }

    #[Route('/getUserSettings', name: 'getUserSettings')]
    public function getUserSettings(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $repository = $this->getDoctrine()->getRepository(UserSettings::class);
        $query = $repository->findOneBy(array('user_id' => $data['user_id']));
        return new Response($this->serializer->serialize($query, 'json'), Response::HTTP_OK, $this->headers);
    }

    #[Route('/updateUserSettingsField', name: 'updateUserSettingsField')]
    public function updateUserSettingsField(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $repository = $this->getDoctrine()->getRepository(UserSettings::class);
        $result = $repository->updateUserSettingsField($data);
        return new Response($this->serializer->serialize($result, 'json'), Response::HTTP_OK, $this->headers);
    }

    #[Route('/createUserSettings', name: 'createUserSettings', methods: ['POST'])]
    public function createUserSettings(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(UserSettingsType::class, new UserSettings());
        $form->submit($data);
        if ($form->isValid()) {
            $user_settings = $form->getData();
            $this->entityManager->persist($user_settings);
            $this->entityManager->flush();
            return new Response($this->serializer->serialize($user_settings, 'json'), Response::HTTP_OK, $this->headers);
        } else {
            return new Response($this->serializer->serialize($form->getErrors(true,true), 'json'), Response::HTTP_BAD_REQUEST, $this->headers);

        }

    }


}
