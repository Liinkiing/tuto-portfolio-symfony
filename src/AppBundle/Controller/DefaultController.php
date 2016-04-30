<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $categoryRepo = $this->getDoctrine()->getRepository('AppBundle:Category');
        $project = new Project();
        $project->setAuthor($userRepo->find(1))
        ->setTitle('Je suis un titre')
        ->setDescription('Mon premier projet !')
        ->setImageUrl('http://www.wallpapereast.com/static/images/abstract_wallpaper_1080p_by_supersaejang-d7ajj1p.png')
        ->addCategory($categoryRepo->findOneBy(['name' => 'Jeuxvidéos']))
        ->addCategory($categoryRepo->findOneBy(['name' => 'Science']));
        $em->persist($project);
        $em->flush();
        return $this->render('default/index.html.twig', ['project' => $project]);
    }
}
