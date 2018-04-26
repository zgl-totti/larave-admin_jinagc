<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Admin\Extensions\Form;

/**
 * Description of Select
 *
 * @author malijie
 */
class Select extends \Encore\Admin\Form\Field\Select
{

    /**
     * 
     * @param string $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    public function setLable($lable) {
        $this->label = $lable;
    }
}
