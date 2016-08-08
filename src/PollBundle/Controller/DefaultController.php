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

    /**
     * @Route("/polls/{pollId}")
     */
    public function singlePollAction($pollId)
    {
        $repository = $this->getDoctrine()
            ->getRepository('PollBundle:Poll');

        $poll = $repository->findOneById($pollId);

        if ($poll->isEnded()) {
            return $this->seePollResultAction();
        }

        if ($this->isPollSubmitted($pollId)) {
            return $this->seePollAction();
        }

        return $this->writePollAction();
    }

    private function isPollSubmitted($pollId)
    {
        $user = $this->getUser();
        $result = $this->getDoctrine()
            ->getEntityManager()
            ->createQuery(
                'SELECT q, s FROM PollBundle:Submitting s
                JOIN s.question q
                WHERE q.poll = :pollid
                AND s.user = :userid'
            )->setParameter('pollid', $pollId)
            ->setParameter('userid', $user->getId())
            ->getResult();

        return (count($result) > 0);
    }

    private function writePollAction()
    {
        die(dump('fill in'));
    }

    private function seePollAction()
    {
        die(dump('see your poll'));
    }

    private function seePollResultAction()
    {
        die(dump('see results'));
    }
}
