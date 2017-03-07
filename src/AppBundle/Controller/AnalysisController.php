<?php
// src/AppBundle/Controller/AnalysisController.php
namespace AppBundle\Controller;
use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\GridCriteriaController;
use AppBundle\Controller\DatabaseController;



class AnalysisController extends Controller {

    /**
     * @Route("/run/analysis")
     */
    public function analysis () {
        $reviews = $this->getReviews();
        foreach ($reviews as $id => $review) {
            $reviewPortions = $this->splice_review($review);
            $result = array();
            $score  = 0;
            foreach ($reviewPortions as $data) {
                $detected_topics = $this->detect_topics($data);
                $positive_result = $this->detect_positive($data, $detected_topics);
                $negative_result = $this->detect_negative($data, $detected_topics);
                $detected_topics = '';
                if ($positive_result){
                    array_push($result, trim($positive_result));
                    $score++;
                }
                if ($negative_result){
                    array_push($result, trim($negative_result));
                    $score--;
                }
            }
            $matches = implode(', ',$result);
            $this->updateReview($score, $matches, $id);
        }
        //return $this->redirectToRoute('/list/review/');
        return $this->redirect('http://localhost:8000/list/review');

    }
    function splice_review ($data) {
        return array_map('trim', preg_split("/[.,]+/", strtolower($data)));

    }
    public function detect_topics($data) {
        $topics = $this->getTopics();
        foreach ($topics as $topics_to_check) {
            if (preg_match("/\b$topics_to_check\b/", $data)) {
                $result = $topics_to_check;
            }
        }
       return (isset($result)) ? $result : null ;
    }

    public function detect_positive ($data, $concatenar = '') {
        $positive = $this->getPositiveWords();
        foreach ($positive as $positive_to_check) {
            if(preg_match("/\b$positive_to_check\b/", $data, $resultados)){
                $result = $concatenar.' '.$resultados[0];
            }
        }
        return (isset($result)) ? $result : null ;
    }

    public function detect_negative ($data, $concatenar = '') {
        $negative = $this->getNegativeWords();
        foreach ($negative as $negative_to_check) {
            if(preg_match("/\b$negative_to_check\b/", $data,$resultados)){
                $result = $concatenar.' '.$resultados[0];
            }
        }
        return (isset($result)) ? $result : null ;
    }

    public function getNegativeWords() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:NegativeWords');
        $data = $repository->findAll();
        $result = array();
        foreach ($data as $var) {
            $result[] = $var->getWord();
        }
        return $result;
    }

    public function getPositiveWords() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:PositiveWords');
        $data = $repository->findAll();
        $result = array();
        foreach ($data as $var) {
            $result[] = $var->getWord();
        }
        return $result;
    }
    public function getTopics() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Topics');
        $data = $repository->findAll();
        $result = array();
        foreach ($data as $var) {
            $result[] = $var->getWord();
        }
        return $result;
    }
    public function getReviews() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Reviews');
        $data = $repository->findAll();
        $result = array();
        foreach ($data as $var) {
            $result[$var->getId()] = $var->getText();
        };
        return $result;
    }
    public function updateReview ($score, $matches, $id) {
        $conn = $this->get('database_connection');
        $sql = 'UPDATE reviews set score = ? , matches = ?  WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $score);
        $stmt->bindValue(2, $matches);
        $stmt->bindValue(3, $id);
        $stmt->execute();
    }
}
