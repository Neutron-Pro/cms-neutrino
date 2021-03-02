<?php
namespace Neutrino\Api\Form;

class SubmitElement implements FormElement
{
    private string $value;
    private string $key;

    public function __construct(string $value = 'Send', string $key = 'submitted')
    {
        $this->value = $value;
        $this->key   = $key;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function isValid(): bool
    {
        return true;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getError(): ?string
    {
        return null;
    }

    public function setValue($value): void
    {}

    public function validate(): void
    {}

    public function toHTML(): string
    {
        return '<input type="submit" name="'.$this->getKey().'" value="'.$this->getValue().'" />';
    }
}
