<?php

namespace core;

class View
{
    protected $view;
    protected $data = [];

    public function __construct($view, $data = [])
    {
        $this->data = $data;

        if (file_exists('../src/modules/' . $view . '.php')) {
            $this->view = '../src/modules/' . $view . '.php';
        } else {
            echo "View {$view} não encontrada.";
        }
    }

    public function render()
    {
        require_once $this->view;
    }

    public function renderTemplate($view, $data = [])
    {
        extract($data);
        require_once '../src/template/template.php';
    }
}