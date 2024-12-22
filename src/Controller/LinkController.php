<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\Keyword;
use App\Form\LinkType;
use App\Repository\KeywordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/link')]
class LinkController extends AbstractController
{
    #[Route('/new', name: 'link_new', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('link_index');
        }

        return $this->render('link/new.html.twig', [
            'link' => $link,
            'form' => $form->createView(),
        ]);
    }
}
