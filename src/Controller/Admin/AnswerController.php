<?php

namespace App\Controller\Admin;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Repository\AnswerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/questions", name="admin_answer_")
 */
class AnswerController extends AbstractController
{
    /**
     * @Route("/{id}/answers", name="index", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function index(Question $question = null): Response
    {
        if(!$question)
        {
            throw $this->createNotFoundException('Question introuvable');
        }
        return $this->render('admin/answer/index.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/answers/new", name="new", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function new(Request $request, Question $question = null): Response
    {
        if(!$question) {
            throw $this->createNotFoundException('Question introuvable');
        }
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $answer->setQuestion($question);
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('admin_answer_index', ['id'=> $question->getId()]);
        }

        return $this->render('admin/answer/new.html.twig', [
            'question' => $question,
            'answer' => $answer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/answers/{answerId}", name="show", methods={"GET"}, requirements={"id"="\d+", "answerId"="\d+"})
     */
    public function show($answerId, AnswerRepository $answerRepository): Response
    {
        $answer = $answerRepository->find($answerId);
        if(!$answer) {
            throw $this->createNotFoundException('Réponse introuvable');
        }
        return $this->render('admin/answer/show.html.twig', [
            'answer' => $answer,
        ]);
    }

    /**
     * @Route("/{id}/answers/{answerId}/edit", name="edit", methods={"GET","POST"}, requirements={"id"="\d+", "answerId"="\d+"})
     */
    public function edit(Request $request, $answerId, AnswerRepository $answerRepository): Response
    {
        $answer = $answerRepository->find($answerId);
        if(!$answer) {
            throw $this->createNotFoundException('Réponse introuvable');
        }
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_answer_index', [
                'id' => $answer->getQuestion()->getId(),
            ]);
        }

        return $this->render('admin/answer/edit.html.twig', [
            'answer' => $answer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Answer $answer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$answer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($answer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_answer_index', [
            'id' => $answer->getQuestion()->getId(),
            ]);
    }
}
