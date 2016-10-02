<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        if (!$request->isXmlHttpRequest()) {
            $lastUsername = $authenticationUtils->getLastUsername();
            return $this->render('AppBundle:Index:index.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]);
        } else {
            if ($error != null) {
                return $this->renderJson(['status'=>'error', 'error'=> $error->getMessage()]);
            } else {
                return $this->renderJson(['status'=>'ok']);
            }
        }
    }
    /**
     * @Route("/#registration", name="registration")
     */
    public function registrationAction()
    {
        return $this->render('AppBundle:Index:index.html.twig');
    }
}
