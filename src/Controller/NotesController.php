<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotesController extends AbstractController
{
    #[Route('/notes', name: 'app_notes')]
    public function index(NoteRepository $noteRepository): Response
    {
        $notes = $noteRepository->findAll();

        return $this->render('notes/index.html.twig', [
            'notes' => $notes,
        ]);
    }

    #[Route('/notes/new', name: 'app_notes_new')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('app_notes');
        }

        return $this->render('notes/new.html.twig', ['form' => $form]);
    }

    #[Route('/notes/{id}', name: 'app_notes_view')]
    public function view(int $id, Request $request, NoteRepository $noteRepository, EntityManagerInterface $entityManager): Response
    {
        $note = $noteRepository->find($id);
        if (!$note) throw $this->createNotFoundException('Note not found');


        return $this->render('notes/view.html.twig', ['note' => $note]);
    }

    #[Route('/notes/{id}/edit', name: 'app_notes_edit')]
    public function edit(int $id, Request $request, NoteRepository $noteRepository, EntityManagerInterface $entityManager): Response
    {
        $note = $noteRepository->find($id);
        if (!$note) throw $this->createNotFoundException('Note not found');

        $form = $this->createForm(NoteType::class, $note);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('app_notes');
        }

        return $this->render('notes/edit.html.twig', ['form' => $form]);
    }

    #[Route('/notes/{id}/delete', name: 'app_notes_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, NoteRepository $noteRepository, EntityManagerInterface $entityManager): Response
    {
        $note = $noteRepository->find($id);
        if (!$note) throw $this->createNotFoundException('Note not found');

        if ($this->isCsrfTokenValid("note-" . $note->getId(), $request->request->get('_token'))) {
            $entityManager->remove($note);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_notes');
    }
}
