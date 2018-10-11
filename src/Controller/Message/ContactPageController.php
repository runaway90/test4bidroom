<?php

namespace App\Controller\Message;

use App\Entity\Message;
use App\Form\MessageSendType;
use App\Form\MessageWriteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ContactPageController
 * @package App\Controller\Message
 */
class ContactPageController extends AbstractController
{

    public function messageForm()
    {
        $message = new Message();
        $formWrite = $this->createForm(MessageWriteType::class, $message);
        $formSend = $this->createForm(MessageSendType::class, $message);

        return $this->render('contact/index.html.twig', [
            'form' => $formWrite->createView(),
            'form_send' => $formSend->createView()
        ]);
    }


    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function writeMessageToDB(Request $request, ValidatorInterface $validator): Response
    {
        $dataFromForm = $request->get('message_write');
        $email = $dataFromForm['email'];
        $message = $dataFromForm['message'];

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

        return new Response('success', 200);
    }


    /**
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function sendMessageFromForm(Request $request, \Swift_Mailer $mailer): Response
    {
        $data = $request->get('message_send');
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom($data['email'])
            ->setTo('vsbezgin@gmail.com')
            ->setBody(
                $this->renderView(
                    'contact/email.html.twig',
                    array('message' => $data['message'])
                ),
                'type');

        $mailer->send($message);
        return Response::create('success', 200, ['headers-up' => 'up']);
    }

}
