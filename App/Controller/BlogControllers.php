<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class BlogControllers extends AbstractController
{
    // This property is used by the marking store
    // 此属性被marking stroe所用
    public $marking;
    public $title;
    public $content;

    /**
     * 匹配 URL: /blog
     * @Route("/blog", name="blog_list")
     */
    public function list()
    {
        die("list");
    }

    public function getMarking()
    {
        return $this->marking;
    }

    public function setMarking($marking)
    {
        return $this->marking = $marking;
    }

    public static function getCurrentState()
    {

    }

    public static function setCurrentState()
    {

    }

    /**
     * 匹配 URL: /blog/*
     * @Route("/blog/{slug}", name="blog_show")
     * @param mixed $slug
     */
    public function show($slug)
    {
        echo "show" . $slug;
    }
}