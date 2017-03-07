<?php
// src/AppBundle/Controller/GridCriteriaController.php
namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GridCriteriaController extends Controller {
    /**
     * @Route("/list/criteria")
     */
    public function gridAction() {
        return $this->render('grid/criteria.html.twig');
    }
    /**
     * @Route("/gridTopicsContent", name="_grid_topics_content")
     */
    public function grid_topics_content(){
        $data = $this->getTopics();
        $table_content = array();

        foreach ($data as $var) {
            $table_content[] = array('id'=>$var->getId(), 'cell'=> array($var->getId(), $var->getWord()));
        }
        $gridData = array(
            'total'=>1,
            'page'=>1,
            'records'=>1,
            'rows'=>$table_content
        );
        return new \Symfony\Component\HttpFoundation\Response(json_encode($gridData), 200
            , array('content-type'=>'application/json')
        );
    }
    /**
     * @Route("/gridPositiveContent", name="_grid_positive_content")
     */
    public function grid_positive_content(){
        $data = $this->getPositiveWords();
        $table_content = array();

        foreach ($data as $var) {
            $table_content[] = array('id'=>$var->getId(), 'cell'=> array($var->getId(), $var->getWord()));
        }
        $gridData = array(
            'total'=>1,
            'page'=>1,
            'records'=>1,
            'rows'=>$table_content
        );
        return new \Symfony\Component\HttpFoundation\Response(json_encode($gridData), 200
            , array('content-type'=>'application/json')
        );
    }
    /**
     * @Route("/gridNegativeContent", name="_grid_negative_content")
     */
    public  function  grid_negative_content(){
        $data = $this->getNegativeWords();
        $table_content = array();
        foreach ($data as $var) {
            $table_content[] = array('id'=>$var->getId(), 'cell'=> array($var->getId(), $var->getWord()));
        }
        $gridData = array(
            'total'=>1,
            'page'=>1,
            'records'=>1,
            'rows'=>$table_content
        );
        return new \Symfony\Component\HttpFoundation\Response(json_encode($gridData), 200
            , array('content-type'=>'application/json')
        );
    }

    public function getNegativeWords() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:NegativeWords');
        return $repository->findAll();;
    }

    public function getPositiveWords() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:PositiveWords');
        return $repository->findAll();
    }
    public function getTopics() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Topics');
        return $repository->findAll();
    }
}