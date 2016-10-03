<?php
namespace AppBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\User;
use AppBundle\Form\RegistrationType;
use AppBundle\Controller\AbstractController;
use AppBundle\Helper\IdForm;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $entity = new User();
        $form   = $this->createRegistrationForm($entity);
        if (!$request->isXmlHttpRequest()) {
            $lastUsername = $authenticationUtils->getLastUsername();
            return $this->render('AppBundle:Index:index.html.twig', [
                'last_username' => $lastUsername,
                'error'  => $error,
                'entity' => $entity,
                'form'   => $form->createView(),
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
        $entity = new User();
        $form   = $this->createRegistrationForm($entity);
        return $this->render('AppBundle:Index:index.html.twig', [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }
    /**
     * Creates a form to registration a user entity.
     * @param User $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createRegistrationForm(User $entity) {
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
        $em = $this->getDoctrine()->getManager();
        $data = ['status' => 'error'];
        $entity = new User();
        $form = $this->createRegistrationForm($entity);
        $form->handleRequest($request);
        $helper = new IdForm();
        if ($form->isValid()) {
            $entity = $form->getData();
            $data = ['status' => 'ok', 'entity' => $entity];
            $em->persist($entity);
            $em->flush();
        } else {
            $data['errors'] = $helper->getFormErrors($form);
        }
        
        return $this->renderJson($data);
    }
}
