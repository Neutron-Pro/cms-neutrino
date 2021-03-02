<?php
namespace Neutrino\Api\Form;

interface FormElement {

    public function getKey(): string;
    public function isValid(): bool;

    /**
     * @return mixed|null
     */
    public function getValue();
    public function getError(): ?string;

    /**
     * @param mixed|null $value
     */
    public function setValue($value): void;

    public function validate(): void;

    public function toHTML(): string;
}
