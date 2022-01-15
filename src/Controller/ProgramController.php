<?php
//src/Controller/programController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProgramType;
use App\Service\Slugify;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
    public function index(Request $request, ProgramRepository $programRepository): Response
    {
        $form = $this->createForm(SearchProgramType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $programs = $programRepository->findLikeName($search);
        } else {
            $programs = $programRepository->findAll();
        }
    
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
            'form' => $form->createView(),
        ]);
    }

    /**
     * The controller for the program add form
     *
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer) : Response
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
            $program->setOwner($this->getUser());
            // Persist Program Object
            $entityManager->persist($program);
            // Flush the persisted object
            $entityManager->flush();
            
            $email = (new Email())
                ->from('admin_wild-series@example.com')
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));

        $mailer->send($email);
            // Finally redirect to programs list
            return $this->redirectToRoute('program_index');
        }
            // Render the form
        return $this->render('program/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

     /**
 * @Route("/{slug}", methods={"GET"}, name="show")
 */
    public function show(Program $program, Slugify $slugify):Response
    {
        $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
        return $this->render('program/show.html.twig', [
            'program'=>$program
        ]);
    }

    /**
     *@Route("/{program_id}/season/{season_id}", name= "season_show")
     *@ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id" = "id"}})
     *@ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_id" = "id"}})
     */
    public function showSeason(Program $program, Season $season): Response
    {
       return $this->render('season/season_show.html.twig', [
           'program' => $program,
           'season' => $season,
       ]);
    }

     /**
     * @Route("/{program_id}/season/{season_id}/episode/{episode_id}",
     * requirements={"program"="\d+", "season"="\d+", "episode"="\d+"},
     * methods={"GET", "POST"}, name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id" = "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_id" = "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode_id" = "id"}})
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('/program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        // Check wether the logged in user is the owner of the program
        if (!($this->getUser() == $program->getOwner())) {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Only the owner can edit the program!');
        }

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getSlug(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }
}