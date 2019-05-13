<?php

namespace App\Controller\Admin;

use App\Entity\Quizz;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuizzRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/quizz", name="admin_question_")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/{id}/questions", name="index", methods={"GET"})
     */
    public function index(Quizz $quizz = null, QuestionRepository $questionRepository): Response
    {
        if(!$quizz) {
            throw $this->createNotFoundException('Quizz introuvable');
        }
        return $this->render('admin/question/index.html.twig', [
            // 'questions' => $questionRepository->findAll(),
            'quizz' => $quizz,
            // 'questions' => $quizz->getQuestions(),
        ]);
    }

    /**
     * @Route("/{id}/questions/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, Quizz $quizz = null): Response
    {
        if(!$quizz) {
            throw $this->createNotFoundException('Quizz introuvable');
        }
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $question->setQuizz($quizz);
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('admin_question_index', ['id'=> $quizz->getId()]);
        }

        return $this->render('admin/question/new.html.twig', [
            'quizz' => $quizz,
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/questions/{questionId}", name="show", methods={"GET"})
     */
    public function show($questionId, QuestionRepository $questionRepository): Response
    {
        $question = $questionRepository->find($questionId);
        if(!$question) {
            throw $this->createNotFoundException('Question introuvable');
        }
        return $this->render('admin/question/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/questions/{questionId}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, $questionId, QuestionRepository $questionRepository): Response
    {
        $question = $questionRepository->find($questionId);
        if(!$question) {
            throw $this->createNotFoundException('Question introuvable');
        }
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_question_index', [
                'id' => $question->getQuizz()->getId(),
            ]);
        }

        return $this->render('admin/question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_question_index', [
            'id' => $question->getQuizz()->getId(),
            ]);
    }
}
