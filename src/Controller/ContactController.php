<?php

namespace App\Controller;

use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{

    public function index(Request $request)
    {
        $message = new Message();
        $message->setEmail('Please enter email');
        $message->setMessage('Please write message');

        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder($message)
            ->add('email', EmailType::class, array(
                'required' => true,
                'constraints' => array(new Length(array('min' => 3, 'max' => 256)))))
            ->add('message', TextType::class, array(
                'required' => true,
                'constraints' => array(new Length(array('min' => 3, 'max' => 256)))))
            ->add('save', SubmitType::class, array('label' => 'Send message'))
            ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod('post') &&  $form->isValid()) {
            $dataFromForm = $request->get('form');
            $newContactMessage = new Message();
            $newContactMessage->setEmail($dataFromForm['email']);
            $newContactMessage->setMessage($dataFromForm['message']);
            $entityManager->persist($newContactMessage);
            $entityManager->flush();

        }
            return $this->render('contact/index.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}
