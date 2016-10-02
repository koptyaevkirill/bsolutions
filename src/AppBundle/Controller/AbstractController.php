<?php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\HttpFoundation\ResponseJson;
abstract class AbstractController extends Controller {
   
    /**
     * @param any $data
     * @param int $code
     * @return ResponseJson 
     */
    protected function renderJson($data, $code = 200) {
        return new ResponseJson($data, $code);
    }
}
