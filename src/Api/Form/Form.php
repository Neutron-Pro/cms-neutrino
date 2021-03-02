<?php
namespace Neutrino\Api\Form;

class Form
{
    /**
     * @var FormElement[]
     */
    protected array $elements;

    protected array $values;

    protected string $action;
    protected string $method;

    /**
     * Form constructor.
     * @param array $values
     * @param string $action
     * @param string $method
     * @param FormElement|bool $secureXSRF
     */
    public function __construct(
        array $values = [], string $action = '',
        string $method = 'POST', $secureXSRF = true
    ){
        $this->values = $values;
        $this->method = $method;
        $this->action = $action;

        if ($secureXSRF) {
            if (is_bool($secureXSRF)) {
                $this->add(new XSRFElement());
            } else {
                $this->add($secureXSRF);
            }
        }
    }

    public function isSubmit(): bool
    {
        return !empty($this->values);
    }

    public function isValid(): bool
    {
        foreach ($this->elements as $element) {
            if (!$element->isValid()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        if (isset($this->elements[$key])) {
            return $this->elements[$key]->getValue();
        }
        return null;
    }

    public function add(FormElement $element): self
    {
        if ($this->isSubmit()) {
            if (isset($this->values[$element->getKey()])) {
                $element->setValue($this->values[$element->getKey()]);
            }
            $element->validate();
        }
        $this->elements[$element->getKey()] = $element;
        return $this;
    }

    public function toHTML(): string
    {
        $html = '<form action="'.$this->action.'" method="'.$this->method.'" enctype="multipart/form-data">';
        foreach ($this->elements as $element) {
            $html .= $element->toHTML();
        }
        return $html.'</form>';
    }
}
