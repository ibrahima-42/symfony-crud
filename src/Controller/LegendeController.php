<?php

namespace App\Controller;

use App\Entity\Legende;
use App\Form\LegendeType;
use App\Repository\LegendeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/legende')]
final class LegendeController extends AbstractController
{
    #[Route(name: 'app_legende_index', methods: ['GET'])]
    public function index(LegendeRepository $legendeRepository): Response
    {
        return $this->render('legende/index.html.twig', [
            'legendes' => $legendeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_legende_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $legende = new Legende();
        $form = $this->createForm(LegendeType::class, $legende);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($legende);
            $entityManager->flush();

            return $this->redirectToRoute('app_legende_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('legende/new.html.twig', [
            'legende' => $legende,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_legende_show', methods: ['GET'])]
    public function show(Legende $legende): Response
    {
        return $this->render('legende/show.html.twig', [
            'legende' => $legende,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_legende_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Legende $legende, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LegendeType::class, $legende);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_legende_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('legende/edit.html.twig', [
            'legende' => $legende,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_legende_delete', methods: ['POST'])]
    public function delete(Request $request, Legende $legende, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$legende->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($legende);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_legende_index', [], Response::HTTP_SEE_OTHER);
    }
}
