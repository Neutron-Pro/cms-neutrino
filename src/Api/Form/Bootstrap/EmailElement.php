<?php
namespace Neutrino\Api\Form\Bootstrap;

class EmailElement extends TextElement
{
    protected string $type = 'email';

    public function validate(): void
    {
        parent::validate();
        if (empty($this->error) && !empty($this->value) && !filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            $this->error = 'This email is invalid !';
        }
    }
}
