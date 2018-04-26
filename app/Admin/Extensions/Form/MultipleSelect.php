<?php

namespace App\Admin\Extensions\Form;

class MultipleSelect extends \Encore\Admin\Form\Field\MultipleSelect
{

    /**
     * 
     * @param string $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    public function setLable($lable)
    {
        $this->label = $lable;
    }

}
