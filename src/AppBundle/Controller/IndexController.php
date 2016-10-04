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
                'form'   => $form->createView()
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
        $data = ['status' => 'error'];
        $entity = new User();
        $form = $this->createRegistrationForm($entity);
        $form->handleRequest($request);
        $helper = new IdForm();
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $form->getData();
            $hash = md5(uniqid($entity->getEmail(), true));
            $entity->setHash($hash);
            $data = ['status' => 'ok', 'entity' => $entity];
            $em->persist($entity);
            $em->flush();
            $message = \Swift_Message::newInstance()
                ->setSubject('Confirm registration')
                ->setFrom('koptyaevkiril@gmail.com')
                ->setTo($entity->getEmail())
                ->setBody(
                    $this->renderView('Emails/registration.html.twig', [
                        'name' => $entity->getName(),
                        'hash' => $entity->getHash()
                    ]),
                    'text/html'
                );
            $this->get('mailer')->send($message);
        } else {
            $data['errors'] = $helper->getFormErrors($form);
        }
        
        return $this->renderJson($data);
    }
    /**
     * Creates a new User entity.
     * @Route("/{email}/valid", name="email_valid")
     * @Method("POST")
     */
    public function emailValidAction($email)
    {
        $data = ['status' => 'error'];
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(
            ['email' => $email]
        );
        if($user != NULL) {
            $data = ['status' => 'error', 'error' => 'This email is already in use'];
        } else {
            $data = ['status' => 'ok'];
        }
        return $this->renderJson($data);
    }
    /**
     * Creates a new User entity.
     * @Route("/{hash}/confirm", name="email_confirm")
     * @Method("GET")
     */
    public function emailConfirmAction($hash)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:User')->findOneBy(
            ['hash' => $hash]
        );
        if($entity != NULL) {
            $entity->setIsConfirm(TRUE);
            $em->persist($entity);
            $em->flush();
        }
        return $this->redirectToRoute('homepage', array('confirm' => 1), 301);
    }
}
