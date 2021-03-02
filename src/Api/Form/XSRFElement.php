<?php
namespace Neutrino\Api\Form;

class XSRFElement implements FormElement
{
    protected string $value = '';
    protected bool $valid = true;

    public function getKey(): string
    {
        return '_token';
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getError(): ?string
    {
        return !$this->isValid() ? 'Invalid Token XSRF !' : null;
    }

    /**
     * @param mixed|null $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function validate(): void
    {
        $this->valid = isset($_SESSION['__token-XSRF']) && $_SESSION['__token-XSRF'] === $this->value;
    }

    public function toHTML(): string
    {
        if($this->valid) {
            $_SESSION['__token-XSRF'] = md5(uniqid().mt_rand());
            return '<input name="'.$this->getKey().'" value="'.$_SESSION['__token-XSRF'].'" type="hidden" />';
        }
        return '<div>'.$this->getError().'</div>';
    }
}
