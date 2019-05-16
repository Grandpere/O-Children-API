<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin_")
 */
class InterfaceController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $this->addFlash(
            'info',
            'Bonjour '.$this->getUser()->getFirstname().', comment allez vous ?'
            );
        return $this->render('admin/interface/index.html.twig', [
            'controller_name' => 'InterfaceController',
        ]);
    }
}
