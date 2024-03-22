<?php

namespace Rrim\PhpUserManagement\Service;

use Rrim\PhpUserManagement\Domain\Session;
use Rrim\PhpUserManagement\Domain\User;
use Rrim\PhpUserManagement\Repository\SessionRepository;
use Rrim\PhpUserManagement\Repository\UserRepository;

class SessionService
{
    public static string $COOKIE_NAME = 'X-RRIM-SESSION';
    public SessionRepository $sessionRepository;
    public UserRepository $userRepository;


    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }

    public function create(string $user_id) :Session
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $user_id;

        $this->sessionRepository->save($session);
        setcookie(self::$COOKIE_NAME,$session->id,time()+(60*60*24*30),"/");

        return $session;
        
    }

    public function destroy()
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME ?? ''];
        $this->sessionRepository->deleteById($sessionId);
        setcookie(self::$COOKIE_NAME,'',1,"/");

        
    }

    public function current() : ?User
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';


        $session = $this->sessionRepository->findById($sessionId);
        if ($session == null){
            return null;
        }
        return $this->userRepository->findById($session->user_id);
        
    }

}