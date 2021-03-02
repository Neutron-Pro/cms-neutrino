<?php
namespace Neutrino\Api\Form;

class TextAreaElement extends TextElement
{
    protected int $rows;
    protected ?int $columns;

    public function __construct(
        ?string $label, string $key, ?string $id = null, array $filters = [],
        string $placeHolder = '', int $rows = 5, ?int $columns = null
    ){
        parent::__construct($label, $key, $id, $filters, $placeHolder);
        $this->rows = $rows;
        $this->columns = $columns;
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
        $html .= '<textarea name="'.$this->getKey()
            .'" placeholder="'.$this->placeHolder.'"'.(!empty($this->id) ? ' id="'.$this->id.'"' : '')
            .' rows="'.$this->rows.'"'.(!empty($this->columns) ? ' cols="'.$this->columns.'"' : '')
            .'>';
        $html .= $this->value;
        $html .= '</textarea>';
        return $html;
    }
}
