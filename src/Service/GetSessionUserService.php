<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;

class GetSessionUserService
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getUser()
    {
        return $this->security->getUser();
    }
}