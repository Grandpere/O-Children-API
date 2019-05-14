<?php

namespace App\Controller\Admin;

use App\Entity\World;
use App\Form\WorldType;
use App\Repository\WorldRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/world", name="admin_world_")
 */
class WorldController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(WorldRepository $worldRepository): Response
    {
        return $this->render('admin/world/index.html.twig', [
            'worlds' => $worldRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $world = new World();
        $form = $this->createForm(WorldType::class, $world);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($world);
            $entityManager->flush();

            return $this->redirectToRoute('admin_world_index');
        }

        return $this->render('admin/world/new.html.twig', [
            'world' => $world,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(World $world): Response
    {
        return $this->render('admin/world/show.html.twig', [
            'world' => $world,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, World $world): Response
    {
        $form = $this->createForm(WorldType::class, $world);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_world_index', [
                'id' => $world->getId(),
            ]);
        }

        return $this->render('admin/world/edit.html.twig', [
            'world' => $world,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, World $world): Response
    {
        if ($this->isCsrfTokenValid('delete'.$world->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($world);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_world_index');
    }
}
