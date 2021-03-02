<?php
namespace Neutrino\Api\Form;

class TextElement implements FormElement
{
    protected string $type = 'text';
    protected ?string $value = null;
    protected string $key;
    protected ?string $id;
    protected ?string $label;
    protected string $placeHolder;
    protected ?string $error = null;
    protected array $filters;

    public function __construct(
        ?string $label, string $key, ?string $id = null, array $filters = [],
        string $placeHolder = ''
    ){
        $this->key = $key;
        $this->id = $id;
        $this->label = $label;
        $this->filters = $filters;
        $this->placeHolder = $placeHolder;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function isValid(): bool
    {
        return empty($this->error);
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function validate(): void
    {
        if (empty($this->value) && ($this->filters['required'] ?? true)) {
            $this->error = 'This field is required.';
            return;
        }
        if(!empty($this->value)) {
            if (isset($this->filters['min'])) {
                if(strlen($this->value) < $this->filters['min']) {
                    $this->error = 'There are not enough characters.';
                    return;
                }
            }
            if (isset($this->filters['max'])) {
                if(strlen($this->value) > $this->filters['max']) {
                    $this->error = 'There are too many characters.';
                    return;
                }
            }
            if (isset($this->filters['matches'])) {
                if (!preg_match($this->filters['matches'], $this->value)) {
                    $this->error = 'An error has occurred';
                    return;
                }
            }
        }
    }

    public function toHTML(): string
    {
        $html = '';
        if(!empty($this->label)) {
            $html .= '<label'.(!empty($this->id) ? ' id="'.$this->id.'"' : '').'>'.$this->label.'</label>';
        }
        if (!empty($this->error)) {
            $html .= '<span>'.$this->error.'</span>';
        }
        $html .= '<input type="'.$this->type.'" name="'.$this->key.'" placeholder="" value="'.$this->value.'"'
            .(!empty($this->id) ? ' id="'.$this->id.'"' : '').'/>';
        return $html;
    }
}
