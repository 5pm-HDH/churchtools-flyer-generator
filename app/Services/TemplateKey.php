<?php

namespace App\Services;

use CTApi\Models\Calendars\Appointment\Appointment;

class TemplateKey
{
    protected \Closure $appointmentExtractor;
    protected \Closure $formatter;

    public function __construct(
        protected string $key,
        callable $appointmentExtractor,
    )
    {
        $this->appointmentExtractor = $appointmentExtractor;
        $this->formatter = function($value){
            return $value;
        };
    }

    public function withFormatter(callable $formatter): TemplateKey
    {
        $this->formatter = $formatter;
        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValueRaw(Appointment $appointment)
    {
        return call_user_func($this->appointmentExtractor, $appointment);
    }

    public function getValueFormatted(Appointment $appointment)
    {
        $value = $this->getValueRaw($appointment);
        return call_user_func($this->formatter, $value);
    }
}
