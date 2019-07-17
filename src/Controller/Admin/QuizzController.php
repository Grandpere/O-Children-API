<?php

namespace App\Controller\Admin;

use App\Entity\Quizz;
use App\Form\QuizzType;
use App\Utils\FileUploader;
use App\Repository\QuizzRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/quizzs", name="admin_quizz_")
 */
class QuizzController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(QuizzRepository $quizzRepository): Response
    {
        return $this->render('admin/quizz/index.html.twig', [
            'quizzs' => $quizzRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $quizz = new Quizz();
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();
            if(!is_null($quizz->getImage())){
                $fileName = $fileUploader->upload($file);
                $quizz->setImage($fileName);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quizz);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Quizz ajouté avec succès'
                );

            return $this->redirectToRoute('admin_quizz_index');
        }

        return $this->render('admin/quizz/new.html.twig', [
            'quizz' => $quizz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Quizz $quizz): Response
    {
        return $this->render('admin/quizz/show.html.twig', [
            'quizz' => $quizz,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Quizz $quizz, FileUploader $fileUploader): Response
    {
        $oldImage = $quizz->getImage();
        if(!empty($oldImage)) {
            $quizz->setImage(
                new File($this->getParameter('images_directory').'/'.$oldImage)
            );
        }

        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!is_null($quizz->getImage())){
                $file = $form->get('image')->getData();
                $fileName = $fileUploader->upload($file);                
                $quizz->setImage($fileName);
                if(!empty($oldImage)){
                    unlink(
                        $this->getParameter('images_directory') .'/'.$oldImage
                    );
                }
            } else {
                $quizz->setImage($oldImage); //ancien nom de fichier
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'Quizz modifié avec succès'
                );

            return $this->redirectToRoute('admin_quizz_index');
        }

        return $this->render('admin/quizz/edit.html.twig', [
            'quizz' => $quizz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Quizz $quizz): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizz->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $filename = $quizz->getImage();
            $entityManager->remove($quizz);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Quizz supprimé avec succès'
                );
            if(!empty($filename)){
                unlink(
                    $this->getParameter('images_directory') .'/'.$filename
                );
            }
        }

        return $this->redirectToRoute('admin_quizz_index');
    }
}
