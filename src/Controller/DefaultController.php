<?php
//src/Controller/defaultController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(ProgramRepository $repository): Response
    {
        $programs = $repository->findAll();
        return $this->render('index.html.twig', [
            'programs' => $programs,
        ]);
    }
}