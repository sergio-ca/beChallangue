<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EvaluateController extends Controller {

    function main ($reviews) {
        foreach ($reviews as $review) {
            $splicedReview = $this->splice_review($review);
            $result = array();
            $score  = 0;
            foreach ($splicedReview as $data) {
                $detected_topics = $this->detect_topics($data);
                $positive_result = $this->detect_positive($data, $detected_topics);
                $negative_result = $this->detect_negative($data, $detected_topics);
                $detected_topics = '';
                isset($positive_result) ? array_push($result, trim($positive_result)): '';
                isset($negative_result) ? array_push($result, trim($negative_result)): '';
            }
        }
    }

    function splice_review ($data) {
        return array_map('trim', preg_split("/[.,]+/", strtolower($data)));
    }

    function detect_negative ($data, $detected_topics = '') {
        foreach ($negative as $negative_to_check) {
            if(preg_match("/\b$negative_to_check\b/", $data,$resultados)){
                $result = $detected_topics.' '.$resultados[0];
            }
        }
        return (isset($result)) ? $result : null ;
    }

    function detect_positive ($data, $detected_topics = '') {
        foreach ($positive as $positive_to_check) {
            if(preg_match("/\b$positive_to_check\b/", $data,$resultados)){
                $result = $detected_topics.' '.$resultados[0];
            }
        }
        return (isset($result)) ? $result : null ;
    }

    function detect_topics($data) {
        foreach ($topics as $topics_to_check) {
            if (preg_match("/\b$topics_to_check\b/", $data)) {
                $result = $topics_to_check;
            }
        }
        return (isset($result)) ? $result : null ;
    }
}
