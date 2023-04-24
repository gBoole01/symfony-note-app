<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
