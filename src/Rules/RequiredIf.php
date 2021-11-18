<?php declare(strict_types=1);

namespace Somnambulist\Components\Validation\Rules;

/**
 * Class RequiredIf
 *
 * @package    Somnambulist\Components\Validation\Rules
 * @subpackage Somnambulist\Components\Validation\Rules\RequiredIf
 */
class RequiredIf extends Required
{
    protected bool $implicit = true;
    protected string $message = 'rule.required_if';

    public function fillParameters(array $params): self
    {
        $this->params['field']  = array_shift($params);
        $this->params['values'] = $params;

        return $this;
    }

    public function check($value): bool
    {
        $this->assertHasRequiredParameters(['field', 'values']);

        $anotherAttribute  = $this->parameter('field');
        $definedValues     = $this->parameter('values');
        $anotherValue      = $this->attribute()->value($anotherAttribute);
        $requiredValidator = $this->validation->getFactory()->rule('required');

        if (in_array($anotherValue, $definedValues)) {
            $this->setAttributeAsRequired();

            return $requiredValidator->check($value);
        }

        return true;
    }
}
