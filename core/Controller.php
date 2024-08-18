<?php 

namespace core;
class Controller
{
    public function render($view, $data = [])
    {
        if (file_exists('../src/modules/' . $view . '.php')) {
            extract($data);
            require '../src/modules/' . $view . '.php';
        } else {
            echo "View {$view} não encontrada.";
        }
    }

    public function renderTemplate($view, $data = [])
    {
        extract($data);
        require_once '../src/template/template.php';
    }
}