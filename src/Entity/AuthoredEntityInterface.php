<?php
/**
 * Created by PhpStorm.
 * User: nesri
 * Date: 29/05/2020
 * Time: 05:16
 */

namespace App\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

interface AuthoredEntityInterface
{
 public function setAuthor(UserInterface $user):AuthoredEntityInterface;
}