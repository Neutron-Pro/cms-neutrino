<?php


namespace Neutrino\Api\Form\Bootstrap;

class SubmitElement extends \Neutrino\Api\Form\SubmitElement
{
    public function toHTML(): string
    {
        return '<button type="submit" name="'.$this->getKey().'" class="btn btn-primary">'
            .$this->getValue().'</button>';
    }
}
