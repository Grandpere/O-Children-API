<?php

namespace App\Controller\Admin;

use App\Entity\Puzzle;
use App\Form\PuzzleType;
use App\Repository\PuzzleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function new(Request $request): Response
    {
        $puzzle = new Puzzle();
        $form = $this->createForm(PuzzleType::class, $puzzle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($puzzle);
            $entityManager->flush();

            return $this->redirectToRoute('admin_puzzle_index');
        }

        return $this->render('admin/puzzle/new.html.twig', [
            'puzzle' => $puzzle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Puzzle $puzzle): Response
    {
        return $this->render('admin/puzzle/show.html.twig', [
            'puzzle' => $puzzle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Puzzle $puzzle): Response
    {
        $form = $this->createForm(PuzzleType::class, $puzzle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Puzzle $puzzle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$puzzle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($puzzle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_puzzle_index');
    }
}
