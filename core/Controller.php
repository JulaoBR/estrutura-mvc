<?php 

namespace core;
class Controller
{
    protected $view;

    public function loadView($view, $data = [])
    {
        $this->view = new View($view, $data);
        $this->view->renderTemplate();
    }
}