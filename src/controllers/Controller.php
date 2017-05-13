<?php

namespace App\Controllers;

abstract class Controller
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        return $this->container->get($name);
    }

    public function validate()
    {
        if($this->validator->validate())
        {
            return true;
        }

        $_SESSION['errors'] = $this->validator->errors();

        return false;
    }

}