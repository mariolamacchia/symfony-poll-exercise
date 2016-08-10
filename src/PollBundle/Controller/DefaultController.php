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

        if (!$poll) {
            throw $this->createNotFoundException();
        }

        if ($poll->isEnded()) {
            return $this->seePollResultAction();
        }

        $submitted = false;
        if ($this->isPollSubmitted($pollId)) {
            $submitted = true;
        }

        return $this->submitPollAction($poll, $submitted);
    }

    private function submitPollAction($poll, $submitted)
    {
        $questions = $this->getPollsQuestions($poll->getId());

        return $this->render('PollBundle:Default:submit.html.twig', array (
            "questions"   => $questions,
            "poll"   => $poll,
            "submitted" => $submitted
        ));
    }

    private function isPollSubmitted($pollId)
    {
        $result = $this->getPollsUserSumbissions($pollId);
        return (count($result) > 0);
    }

    private function seePollResultAction()
    {
        die(dump($this->getPollsSubmissions($pollId)));
    }

    private function getPollsQuestions($pollId)
    {
        return $this->getDoctrine()
            ->getRepository('PollBundle:Question')
            ->findByPoll($pollId);
    }

    private function getPollsSumbissions($pollId)
    {
        return $this->getDoctrine()
            ->getEntityManager()
            ->createQuery(
                'SELECT q, s FROM PollBundle:Submitting s
                JOIN s.question q
                WHERE q.poll = :pollid'
            )->setParameter('pollid', $pollId)
            ->getResult();
    }

    private function getPollsUserSumbissions($pollId)
    {
        $user = $this->getUser();
        return $this->getDoctrine()
            ->getEntityManager()
            ->createQuery(
                'SELECT q, s FROM PollBundle:Submitting s
                JOIN s.question q
                WHERE q.poll = :pollid
                AND s.user = :userid'
            )->setParameter('pollid', $pollId)
            ->setParameter('userid', $user->getId())
            ->getResult();
    }
}
