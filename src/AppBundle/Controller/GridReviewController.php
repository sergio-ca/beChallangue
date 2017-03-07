<?php
// src/AppBundle/Controller/GridReviewController.php
namespace AppBundle\Controller;
use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GridReviewController extends Controller {
    /**
     * @Route("/list/review")
     */
    public function gridAction() {
        return $this->render('grid/reviews.html.twig');
    }

    /**
     * @Route("/gridContent", name="_grid_reviews_content")
     */
    public function grid_review_content(){
        $data = $this->getReviews();
        $table_content = array();
        foreach ($data as $var) {
            $table_content[] = array('id'=>$var->getId(), 'cell'=> array($var->getId(), $var->getText(), $var->getMatches(), $var->getScore()));
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

    public function getReviews() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Reviews');
        return $repository->findAll();
    }
}