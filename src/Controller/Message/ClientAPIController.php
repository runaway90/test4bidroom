<?php

namespace App\Controller\Message;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ClientAPIController
 * @package App\Controller\Message
 */
class ClientAPIController extends AbstractController
{
    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function createOne(Request $request, ValidatorInterface $validator): Response
    {

        $email = $request->query->get('email');
        $message = $request->query->get('message');


        $entityManager = $this->getDoctrine()->getManager();
        $newContactMessage = new Message();
        $newContactMessage->setEmail($email);
        $newContactMessage->setMessage($message);

        $errors = $validator->validate($newContactMessage);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $entityManager->persist($newContactMessage);
        $entityManager->flush();

        return Response::create('success', 200);
    }

    /**
     * @return Response
     */
    public function getAll(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Message::class);
        $messages = $repository->findAll();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($messages, 'json');

        return Response::create($jsonContent, 200);

    }

    public function getByEmail(string $email)
    {
        $repository = $this->getDoctrine()
        ->getRepository(Message::class)
        ->findByEmailField($email);

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($repository, 'json');

        return Response::create($jsonContent, 200);
    }

    public function findOneMessageById(Request $request)
    {
        $id = $request->query->get('id');

        $repository = $this->getDoctrine()
            ->getRepository(Message::class)
            ->findOneMessageById($id);

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($repository, 'json');

        return Response::create($jsonContent, 200);
    }

}
