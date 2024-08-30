<?php

namespace core;

class View
{
    protected $view;
    protected $data = [];

    public function __construct($view, $data = [])
    {
        $this->data = $data;

        $this->data['BASEDIR'] = Request::getBaseUrl();

        if (file_exists('../src/modules/' . $view . '.php')) {
            $this->view = '../src/modules/' . $view . '.php';
        } else {
            echo "View {$view} não encontrada.";
        }
    }

    public function render()
    {
        extract($this->data);
        require_once $this->view;
    }

    public function renderTemplate()
    {
        extract($this->data);
        require_once '../src/template/template.php';
    }
}