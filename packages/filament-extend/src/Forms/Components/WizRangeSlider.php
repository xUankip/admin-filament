<?php

namespace Wiz\FilamentExtend\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Support\Arr;

class WizRangeSlider extends Field
{
    protected float|int|Closure|null $min          = null;
    protected float|int|Closure|null $max          = null;
    protected float|int|Closure|null $step         = 1;
    protected bool|Closure           $displaySteps = true;
    protected bool                   $stepsAssoc   = false;
    protected array                  $steps        = [];

    protected string $view    = 'wiz-filament-extend::forms.components.range-slider';
    private bool     $showMin = false;

    /**
     * Sets the step value
     *
     * @param float $step
     * @return self
     */
    public function step(float|int|Closure $step): self
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Sets the min value
     *
     * @param float $min
     * @return self
     */
    public function min(float|int|Closure $min): self
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Sets the max value
     *
     * @param float $max
     * @return self
     */
    public function max(float|int|Closure $max): self
    {
        $this->max = $max;

        return $this;
    }


    /**
     * @return mixed null | int | float
     */
    public function getMin(): mixed
    {
        return $this->evaluate($this->min);
    }

    /**
     * @return mixed null | int | float
     */
    public function getMax(): mixed
    {
        return $this->evaluate($this->max);
    }

    public function getStep(): int|float
    {
        return $this->evaluate($this->step);
    }

    public function getShowMin(): bool
    {
        return $this->showMin;
    }

    public function showMin(bool $show = true): static
    {
        $this->showMin = $show;
        return $this;
    }

}
