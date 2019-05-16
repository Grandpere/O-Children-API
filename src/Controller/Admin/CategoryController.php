<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Utils\FileUploader;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();
            if(!is_null($category->getImage())){
                $fileName = $fileUploader->upload($file);
                $category->setImage($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Catégorie ajoutée avec succès'
                );

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Category $category): Response
    {
        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Category $category, FileUploader $fileUploader): Response
    {
        $oldImage = $category->getImage();
        if(!empty($oldImage)) {
            $category->setImage(
                new File($this->getParameter('images_directory').'/'.$oldImage)
            );
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!is_null($category->getImage())){
                $file = $form->get('image')->getData();
                $fileName = $fileUploader->upload($file);                
                $category->setImage($fileName);
                if(!empty($oldImage)){
                    unlink(
                        $this->getParameter('images_directory') .'/'.$oldImage
                    );
                }
            } else {
                $category->setImage($oldImage); //ancien nom de fichier
            }
            
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'Catégorie modifiée avec succès'
                );

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $filename = $category->getImage();
            $entityManager->remove($category);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Catégorie supprimée avec succès'
                );
            if(!empty($filename)){
                unlink(
                    $this->getParameter('images_directory') .'/'.$filename
                );
            }
        }

        return $this->redirectToRoute('admin_category_index');
    }
}
