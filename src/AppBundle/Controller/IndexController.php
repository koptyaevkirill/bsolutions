<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller {
    /**
     * @Route("/", name="homepage")
     * @Template("AppBundle:Index:index.html.twig")
     */
    public function indexAction(Request $request) {
        return [];
    }
}
