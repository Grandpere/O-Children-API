<?php

namespace App\Controller\Admin;

use App\Entity\World;
use App\Form\WorldType;
use App\Utils\FileUploader;
use App\Repository\WorldRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $world = new World();
        $form = $this->createForm(WorldType::class, $world);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $file = $form->get('image')->getData();
            if(!is_null($world->getImage())){
                $fileName = $fileUploader->upload($file);
                $world->setImage($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($world);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Monde ajouté avec succès'
                );

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
    public function edit(Request $request, World $world, FileUploader $fileUploader): Response
    {
        $oldImage = $world->getImage();
        if(!empty($oldImage)) {
            $world->setImage(
                new File($this->getParameter('images_directory').'/'.$oldImage)
            );
        }
        
        $form = $this->createForm(WorldType::class, $world);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!is_null($world->getImage())){
                $file = $form->get('image')->getData();
                $fileName = $fileUploader->upload($file);                
                $world->setImage($fileName);
                if(!empty($oldImage)){
                    unlink(
                        $this->getParameter('images_directory') .'/'.$oldImage
                    );
                }
            } else {
                $world->setImage($oldImage); //ancien nom de fichier
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'Monde modifié avec succès'
                );

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
            $filename = $world->getImage();
            $entityManager->remove($world);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Monde supprimé avec succès'
                );
            if(!empty($filename)){
                unlink(
                    $this->getParameter('images_directory') .'/'.$filename
                );
            }
        }

        return $this->redirectToRoute('admin_world_index');
    }
}
