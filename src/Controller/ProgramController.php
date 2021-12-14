<?php
//src/Controller/programController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProgramType;
use App\Service\Slugify;

/**
* @Route("/program", name="program_")
*/
class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     * 
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',[
            'website' => 'Wild Séries',
            'programs' => $programs]
        );
    }

    /**
     * The controller for the program add form
     *
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, Slugify $slugify) : Response
    {
        // Create a new Program Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            // Persist Program Object
            $entityManager->persist($program);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to programs list
            return $this->redirectToRoute('program_index');
        }
            // Render the form
        return $this->render('program/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

     /**
 * Getting a program by id
 * @Route("/{slug}", name="show", methods={"GET"})
 */
    public function show(Program $program):Response
    {
        return $this->render('program/show.html.twig', [
            'program'=>$program
        ]);
    }

    /**
     *@Route("/{program_id}/season/{season_id}", name= "season_show")
     *@ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}})
     *@ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_id": "id"}})
     */
    public function showSeason(Program $program, Season $season): Response
    {
       return $this->render('program/season_show.html.twig', [
           'program' => $program,
           'season' => $season,
       ]);
    }

     /**
     * @Route("/{program_id}/season/{season_id}/episode/{episode_id}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_id": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode_id": "id"}})
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('/program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}