<?php

namespace App\Controller;

use App\Entity\PagesAdmin;
use App\Form\PagesAdminType;
use App\Entity\Produit;
use App\Repository\PagesAdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/pages')]
class PagesAdminController extends AbstractController
{
    #[Route('/', name: 'app_pages_admin_index', methods: ['GET'])]
    public function index(PagesAdminRepository $pagesAdminRepository): Response
    {
        return $this->render('pages_admin/index.html.twig', [
            'pages_admins' => $pagesAdminRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pages_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PagesAdminRepository $pagesAdminRepository): Response
    {
        $pagesAdmin = new PagesAdmin();
        $form = $this->createForm(PagesAdminType::class, $pagesAdmin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pagesAdminRepository->add($pagesAdmin, true);

            return $this->redirectToRoute('app_pages_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages_admin/new.html.twig', [
            'pages_admin' => $pagesAdmin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pages_admin_show', methods: ['GET'])]
    public function show(PagesAdmin $pagesAdmin): Response
    {
        return $this->render('pages_admin/show.html.twig', [
            'pages_admin' => $pagesAdmin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pages_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PagesAdmin $pagesAdmin, Produit $produit, PagesAdminRepository $pagesAdminRepository): Response
    {
        $form = $this->createForm(PagesAdminType::class, $pagesAdmin);
        $form->handleRequest($request);
   

        if ($form->isSubmitted() && $form->isValid()) {
            $pagesAdminRepository->add($pagesAdmin, true);

            return $this->redirectToRoute('app_pages_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages_admin/edit.html.twig', [
            'pages_admin' => $pagesAdmin,
            'form' => $form,
         
        ]);
    }

    #[Route('/{id}', name: 'app_pages_admin_delete', methods: ['POST'])]
    public function delete(Request $request, PagesAdmin $pagesAdmin, PagesAdminRepository $pagesAdminRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pagesAdmin->getId(), $request->request->get('_token'))) {
            $pagesAdminRepository->remove($pagesAdmin, true);
        }

        return $this->redirectToRoute('app_pages_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
