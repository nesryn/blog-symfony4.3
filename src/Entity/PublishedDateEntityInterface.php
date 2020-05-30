<?php
/**
 * Created by PhpStorm.
 * User: nesri
 * Date: 29/05/2020
 * Time: 05:24
 */

namespace App\Entity;


interface PublishedDateEntityInterface
{
    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface;

}