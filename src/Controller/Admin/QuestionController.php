<?php

namespace App\Controller\Admin;

use App\Entity\Quizz;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Utils\FileUploader;
use App\Repository\QuizzRepository;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/quizzs", name="admin_question_")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/{id}/questions", name="index", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function index(Quizz $quizz = null): Response
    {
        if(!$quizz) {
            throw $this->createNotFoundException('Quizz introuvable');
        }
        return $this->render('admin/question/index.html.twig', [
            'quizz' => $quizz,
        ]);
    }

    /**
     * @Route("/{id}/questions/new", name="new", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function new(Request $request, Quizz $quizz = null, FileUploader $fileUploader): Response
    {
        if(!$quizz) {
            throw $this->createNotFoundException('Quizz introuvable');
        }
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $form->get('image')->getData();
            if(!is_null($question->getImage())){
                $fileName = $fileUploader->upload($file);
                $question->setImage($fileName);
            }
            
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
     * @Route("/{id}/questions/{questionId}", name="show", methods={"GET"}, requirements={"id"="\d+", "questionId"="\d+"})
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
     * @Route("/{id}/questions/{questionId}/edit", name="edit", methods={"GET","POST"}, requirements={"id"="\d+", "questionId"="\d+"})
     */
    public function edit(Request $request, $questionId, QuestionRepository $questionRepository, FileUploader $fileUploader): Response
    {
        $question = $questionRepository->find($questionId);
        if(!$question) {
            throw $this->createNotFoundException('Question introuvable');
        }

        $oldImage = $question->getImage();
        if(!empty($oldImage)) {
            $question->setImage(
                new File($this->getParameter('images_directory').'/'.$oldImage)
            );
        }

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!is_null($question->getImage())){
                $file = $form->get('image')->getData();
                $fileName = $fileUploader->upload($file);                
                $question->setImage($fileName);
                if(!empty($oldImage)){
                    unlink(
                        $this->getParameter('images_directory') .'/'.$oldImage
                    );
                }
            } else {
                $question->setImage($oldImage); //ancien nom de fichier
            }
            
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
     * @Route("/questions/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $filename = $question->getImage();
            $entityManager->remove($question);
            $entityManager->flush();
            if(!empty($filename)){
                unlink(
                    $this->getParameter('images_directory') .'/'.$filename
                );
            }
        }

        return $this->redirectToRoute('admin_question_index', [
            'id' => $question->getQuizz()->getId(),
            ]);
    }

    /**
     * @Route("/{id}/questions/{questionId}/answers/{answerId}/right", name="right", methods={"POST"}, requirements={"id"="\d+", "questionId"="\d+", "answerId"="\d+"})
     */
    public function right(Request $request, $questionId, $answerId, QuestionRepository $questionRepository, AnswerRepository $answerRepository) : Response
    {
        $question = $questionRepository->find($questionId);
        if(!$question) {
            throw $this->createNotFoundException('Question introuvable');
        }
        $answer = $answerRepository->find($answerId);
        if(!$answer) {
            throw $this->createNotFoundException('Réponse introuvable');
        }
        if ($this->isCsrfTokenValid('right'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $question->setRightAnswer($answer);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Enregistrement effectué'
        );
        }
        return $this->redirectToRoute('admin_answer_index', ['id'=> $question->getId()]);
    }
}
