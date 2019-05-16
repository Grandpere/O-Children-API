<?php

namespace App\Controller\Admin;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Utils\FileUploader;
use App\Repository\AnswerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
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
    public function new(Request $request, Question $question = null, FileUploader $fileUploader): Response
    {
        if(!$question) {
            throw $this->createNotFoundException('Question introuvable');
        }
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();
            if(!is_null($answer->getImage())){
                $fileName = $fileUploader->upload($file);
                $answer->setImage($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $answer->setQuestion($question);
            $entityManager->persist($answer);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Réponse ajoutée avec succès'
                );

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
    public function edit(Request $request, $answerId, AnswerRepository $answerRepository, FileUploader $fileUploader): Response
    {
        $answer = $answerRepository->find($answerId);
        if(!$answer) {
            throw $this->createNotFoundException('Réponse introuvable');
        }

        $oldImage = $answer->getImage();
        if(!empty($oldImage)) {
            $answer->setImage(
                new File($this->getParameter('images_directory').'/'.$oldImage)
            );
        }

        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!is_null($answer->getImage())){
                $file = $form->get('image')->getData();
                $fileName = $fileUploader->upload($file);                
                $answer->setImage($fileName);
                if(!empty($oldImage)){
                    unlink(
                        $this->getParameter('images_directory') .'/'.$oldImage
                    );
                }
            } else {
                $answer->setImage($oldImage); //ancien nom de fichier
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'Réponse modifiée avec succès'
                );

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
            $filename = $answer->getImage();
            $question = $answer->getQuestion();
            $rightAnswer = $question->getRightAnswer();
            if($rightAnswer) {
                if($answer->getId() == $rightAnswer->getId())
                {
                    $question->setRightAnswer(null);
                }
            }            
            $entityManager->remove($answer);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Réponse supprimée avec succès'
                );
            if(!empty($filename)){
                unlink(
                    $this->getParameter('images_directory') .'/'.$filename
                );
            }
        }

        return $this->redirectToRoute('admin_answer_index', [
            'id' => $answer->getQuestion()->getId(),
            ]);
    }
}
