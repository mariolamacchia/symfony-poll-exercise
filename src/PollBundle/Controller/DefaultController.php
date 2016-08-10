<?php

namespace PollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use PollBundle\Entity\Poll;
use PollBundle\Entity\Submitting;

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
     * @Method({"GET"})
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

        return $this->viewPoll($poll);
    }

    /**
     * @Route("/polls/{pollId}")
     * @Method({"POST"})
     */
    public function submitPollAction($pollId)
    {
        $repository = $this->getDoctrine()
            ->getRepository('PollBundle:Poll');

        $poll = $repository->findOneById($pollId);

        if (!$poll) {
            throw $this->createNotFoundException();
        }

        if ($poll->isEnded() || $this->isPollSubmitted($poll)) {
            throw new AccessDeniedHttpException();
        }

        $repository = $this->getDoctrine()
            ->getRepository('PollBundle:Question');
        $request = Request::createFromGlobals();
        $data = $request->request->all();
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        foreach ($data as $key => $value) {
            $question = $repository->findOneById($key);
            if (!$question) {
                throw $this->createNotFoundException();
            }

            if (gettype($value) === 'array') {
                $value = join(',', $value);
            }

            $s = new Submitting();
            $s->setUser($user);
            $s->setQuestion($question);
            $s->setValue($value);
            $em->persist($s);
        }

        $em->flush();

        return $this->singlePollAction($pollId);
    }

    private function viewPoll($poll)
    {
        $questions = $this->getPollsQuestions($poll->getId());
        $submittings = $this->getPollsUserSumbissions($poll->getId());

        return $this->render('PollBundle:Default:submit.html.twig', array (
            "questions"   => $questions,
            "poll"   => $poll,
            "submittings" => $submittings
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
