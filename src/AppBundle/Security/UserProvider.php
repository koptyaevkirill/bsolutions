<?php
namespace AppBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use AppBundle\Entity\User;

class UserProvider implements UserProviderInterface {
    private $em;
    public function __construct($manager) {
        $this->em = $manager;
    }
    /**
     * @param string $username
     * @return false|User|\Symfony\Component\Security\Core\User\UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function loadUserByUsername($username) {
        if(empty($username)) {
            throw new UsernameNotFoundException('Empty username or password');
        }
        
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(
            ['email' => $username]
        );
        /*$user = new User();
        $user->setEmail('w@tut.by');
        $user->setName('Якушевский Виталий Викторович');
        $user->setPassword('w');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPersonalNumber('123');
        $user->setAccountNumber('123');
        $this->em->persist($user);
        $this->em->flush();
        exit();*/
        return $user;
    }

    /**
     * метод проверяет вид сущности пользователя (ведь их может быть много)
     *
     * @param UserInterface $user
     *
     * @return User|UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException( sprintf( 'Instances of "%s" are not supported.', get_class( $user ) ) );
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Метод проверки класса пользователя
     * нужен чтобы Symfony использовал правильный класс Пользователя для получения объекта пользователя
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'AppBundle\\Entity\\User';
    }
}