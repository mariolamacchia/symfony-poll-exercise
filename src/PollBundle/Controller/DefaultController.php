<?php

namespace PollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PollBundle\Entity\Poll;

class DefaultController extends Controller
{
    /**
     * @Route("/polls")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('PollBundle:Poll');

        $openPolls = $repository
            ->createQueryBuilder('p')
            ->where('p.startDate >= :date')
            ->where('p.endDate < :date')
            ->setParameter(':date', date('Y-m-d'))
            ->getQuery()
            ->getResult();

        $closedPolls = $repository
            ->createQueryBuilder('p')
            ->where('p.endDate >= :date')
            ->setParameter(':date', date('Y-m-d'))
            ->getQuery()
            ->getResult();

        return $this->render('PollBundle:Default:index.html.twig', array (
            "openPolls"   => $openPolls,
            "closedPolls" => $closedPolls
        ));
    }
}
