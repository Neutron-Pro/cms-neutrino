<?php
namespace Neutrino\Api\Form\Bootstrap;

class XSRFElement extends \Neutrino\Api\Form\XSRFElement
{
    public function toHTML(): string
    {
        if($this->valid) {
            $_SESSION['__token-XSRF'] = md5(uniqid().mt_rand());
            return '<input name="'.$this->getKey().'" value="'.$_SESSION['__token-XSRF'].'" type="hidden" />';
        }
        return '<div class="alert alert-dismissible alert-danger">'
            .'<button type="button" class="close" data-dismiss="alert">&times;</button>'
            .$this->getError()
            .'</div>';
    }
}
