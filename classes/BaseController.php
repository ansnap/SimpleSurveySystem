<?php

/**
 * Ancestor of controllers
 */
abstract class BaseController
{

    protected $registry;

    public function __construct($registry)
    {

        $this->registry = $registry;
    }

    abstract public function index();

    public function view($args)
    {
        $file = SITE_PATH . 'templates' . DIRSEP . 'base.php';
        include($file);
    }

}
