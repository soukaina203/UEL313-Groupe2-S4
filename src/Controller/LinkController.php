<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\Keyword;
use App\Form\LinkType;
use App\Repository\KeywordRepository;
use App\Repository\LinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/link')]
final class LinkController extends AbstractController
{
    #[Route(name: 'app_link_index', methods: ['GET'])]
    public function index(LinkRepository $linkRepository): Response
    {
        return $this->render('link/index.html.twig', [
            'links' => $linkRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_link_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, KeywordRepository $keywordRepository): Response
    {
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $keywordName = $form->get('keyword')->getData();
            $keyword = $keywordRepository->findOneBy(['name' => $keywordName]);

            
            if (!$keyword) {
                $keyword = new Keyword();
                $keyword->setName($keywordName);
                $entityManager->persist($keyword);
            }

            $link->setKeyword($keyword);

            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('adminLink_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('link/new.html.twig', [
            'link' => $link,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_link_show', methods: ['GET'])]
    public function show(Link $link): Response
    {
        return $this->render('link/show.html.twig', [
            'link' => $link,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_link_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Link $link, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('adminLink_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('link/edit.html.twig', [
            'link' => $link,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_link_delete', methods: ['POST'])]
public function delete(Request $request, Link $link, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete' . $link->getId(), $request->request->get('_token'))) {
        $entityManager->remove($link);
        $entityManager->flush();
    }

    return $this->redirectToRoute('adminLink_index', [], Response::HTTP_SEE_OTHER);
}
}
