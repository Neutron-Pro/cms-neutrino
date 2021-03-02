<?php
namespace Neutrino\Api\Form\Bootstrap;

class TextAreaElement extends \Neutrino\Api\Form\TextAreaElement
{
    public function toHTML(): string
    {
        $error = !empty($this->error);
        $html = '<div class="form-group'.($error ? ' has-danger' : '').'">';

        if (!empty($this->label)) {
            $html .= '<label class="form-control-label"'
                .(!empty($this->id) ? ' id="'.$this->id.'"' : '')
                .'>'.$this->label.'</label>';
        }

        $html .= '<textarea class="form-control'.($error ? ' is-invalid' : '').'"'
            .(!empty($this->id) ? ' id="'.$this->id.'"' : '')
            .' placeholder="'.$this->placeHolder.'"'
            .' name="'.$this->getKey().'"'
            .' rows="'.$this->rows.'"'.(!empty($this->columns) ? ' cols="'.$this->columns.'"' : '')
            .'>';
        $html .= $this->value;
        $html .= '</textarea>';

        if ($error) {
            $html .= '<div class="invalid-feedback">'.$this->error.'</div>';
        }
        return $html.'</div>';
    }
}
