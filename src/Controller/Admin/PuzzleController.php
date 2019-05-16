<?php

namespace App\Controller\Admin;

use App\Entity\Puzzle;
use App\Form\PuzzleType;
use App\Utils\FileUploader;
use App\Repository\PuzzleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/puzzle", name="admin_puzzle_")
 */
class PuzzleController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(PuzzleRepository $puzzleRepository): Response
    {
        return $this->render('admin/puzzle/index.html.twig', [
            'puzzles' => $puzzleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $puzzle = new Puzzle();
        $form = $this->createForm(PuzzleType::class, $puzzle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();
            if(!is_null($puzzle->getImage())){
                $fileName = $fileUploader->upload($file);
                $puzzle->setImage($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($puzzle);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Puzzle ajouté avec succès'
                );

            return $this->redirectToRoute('admin_puzzle_index');
        }

        return $this->render('admin/puzzle/new.html.twig', [
            'puzzle' => $puzzle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Puzzle $puzzle): Response
    {
        return $this->render('admin/puzzle/show.html.twig', [
            'puzzle' => $puzzle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Puzzle $puzzle, FileUploader $fileUploader): Response
    {
        $oldImage = $puzzle->getImage();
        if(!empty($oldImage)) {
            $puzzle->setImage(
                new File($this->getParameter('images_directory').'/'.$oldImage)
            );
        }
        
        $form = $this->createForm(PuzzleType::class, $puzzle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!is_null($puzzle->getImage())){
                $file = $form->get('image')->getData();
                $fileName = $fileUploader->upload($file);                
                $puzzle->setImage($fileName);
                if(!empty($oldImage)){
                    unlink(
                        $this->getParameter('images_directory') .'/'.$oldImage
                    );
                }
            } else {
                $puzzle->setImage($oldImage); //ancien nom de fichier
            }
            
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'Puzzle modifié avec succès'
                );

            return $this->redirectToRoute('admin_puzzle_index', [
                'id' => $puzzle->getId(),
            ]);
        }

        return $this->render('admin/puzzle/edit.html.twig', [
            'puzzle' => $puzzle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Puzzle $puzzle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$puzzle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $filename = $puzzle->getImage();
            $entityManager->remove($puzzle);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Puzzle supprimé avec succès'
                );
            if(!empty($filename)){
                unlink(
                    $this->getParameter('images_directory') .'/'.$filename
                );
            }
        }

        return $this->redirectToRoute('admin_puzzle_index');
    }
}
