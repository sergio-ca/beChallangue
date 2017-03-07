<?php
// src/AppBundle/Controller/FormController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Criteria;
use AppBundle\Entity\NegativeWords;
use AppBundle\Entity\PositiveWords;
use AppBundle\Entity\Reviews;
use AppBundle\Entity\Topics;
use AppBundle\Entity\CsvFile;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use AppBundle\Form\UserType;
use AppBundle\Controller\CsvController;

use Doctrine\ORM\Query;


class FormController extends Controller {
    /**
     * @Route("/create/review")
     */
    public function csvForm (Request $request) {

        $csvFile = new CsvFile();
        $form = $this->createForm(UserType::class, $csvFile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $csvFile->getFile();
            $fileName = md5(uniqid()) . '.csv';
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );
            $csvFile->setFile($fileName);
            $file_path= $this->getParameter('brochures_directory').'/'.$fileName;
            $this->read_csv($file_path);
            return $this->successReview();
        }
        return $this->render('list/createReview.html.twig', array('form' => $form->createView(),));
    }

    /**
     * @Route("/create/criteria")
     */
    public function loadCreateCriteria(Request $request) {

        $form = $this->createFormBuilder()
            ->add('Type', ChoiceType::class, array(
                'choices'  => array(
                    'Topic' => 'TOPICS',
                    'Positive' => 'PositiveWord',
                    'Negative' => 'NegativeWord',
                )))
            ->add('word',   TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            switch ($data['Type']) {
                case 'Topic':
                    $this->inserTopics($data['word']);
                    break;
                case 'Positive':
                    $this->insertPositiveWord($data['word']);
                    break;
                case 'Negative':
                    $this->insertNegativeWord($data['word']);
                    break;
            }
            return $this->successCriteria();
        }
        return $this->render('list/createCriteria.html.twig', array('form' => $form->createView(),
        ));
    }

    public function inserTopics($data) {

        $topic = new Topics();
        $topic->setWord($data);
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();
        } catch (\Doctrine\DBAL\DBALException $e){

        }
        return new Response('Saved new Topic with id '.$topic->getId());
    }

    public function insertPositiveWord($data) {

        $positiveword = new PositiveWords();
        $positiveword->setWord($data);
        $em = $this->getDoctrine()->getManager();
        $em->persist($positiveword);
        $em->flush();
        return new Response('Saved new Positive word with id '.$positiveword->getId());
    }

    public function insertNegativeWord($data) {

        $negativeword = new NegativeWords();
        $negativeword->setWord($data);
        $em = $this->getDoctrine()->getManager();
        $em->persist($negativeword);
        $em->flush();
        return new Response('Saved new Negative word with id '.$negativeword->getId());
    }

    public function insertReviews($data) {
        $reviews = new Reviews();
        $reviews->setText($data);
        $em = $this->getDoctrine()->getManager();
        $em->persist($reviews);
        $em->flush();
        return new Response('Saved new Review with id '.$reviews->getId());
    }

    public function getReviews() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Reviews');
        $data = $repository->findAll();
        return $data;
    }

    public function successReview() {
        return $this->render('grid/reviews.html.twig');
    }

    public function successCriteria() {
        return $this->render('grid/criteria.html.twig');
    }

    function read_csv ($file) {
        $data = array();
        if (($gestor = fopen("$file", "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, ";")) !== FALSE) {
                array_push($data, $datos[0]);
            }
            fclose($gestor);
        }
        foreach ($data as $reviews){
            $this->insertReviews($reviews);
        }
        unlink($file);
    }
}