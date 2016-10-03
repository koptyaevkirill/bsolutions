<?php
namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Helper\IdForm;
use AppBundle\Form\RegistrationType;

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
    /**
     * Creates a form to registration a user entity.
     * @param User $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createRegistrationForm(Company $entity) {
        $form = $this->createForm(new RegistrationType(), $entity, [
            'action' => $this->generateUrl('registration_post'),
            'method' => 'POST'
        ]);
        return $form;
    }
    /**
     * Creates a new User entity.
     * @Route("/registrations", name="registration_post")
     * @Method("POST")
     */
    public function createAction(Request $request) {
        $data = ['status' => 'error'];
        $entity = new User();
        $form = $this->createRegistrationForm($entity);
        $form->handleRequest($request);
        $helper = new IdForm();
        if ($form->isValid()) {
//            $response = API::getInstance()->post('register', $entity->jsonSerialize());
//            if ($response->status == 'ok') {
//                $data['status'] = 'ok';
//                $data['_username'] = $entity->getEmail();
//                $data['_password'] = $entity->getPassword();
//            } else {
//                $data['errors'] = $helper->translateErrors($response->errors, $this->get('translator'));
//            }
        } else {
            $data['errors'] = $helper->getFormErrors($form);
        }
        return $this->renderJson($data);
    }
}
