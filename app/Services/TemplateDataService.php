<?php

namespace App\Services;

use CTApi\Models\Calendars\Appointment\Appointment;

class TemplateDataService
{
    /**
     * @param TemplateKey[] $templateKeys
     */
    public function __construct(
        private readonly array $templateKeys
    )
    {
    }

    public function getRawValuesFromAppointment(Appointment $appointment): array
    {
        $data = [];
        foreach ($this->templateKeys as $templateKey) {
            $data[$templateKey->getKey()] = $templateKey->getValueRaw($appointment);
        }
        return $data;
    }

    public function getFormattedValuesFromAppointment(Appointment $appointment): array
    {
        $data = [];
        foreach ($this->templateKeys as $templateKey) {
            $data[$templateKey->getKey()] = $templateKey->getValueFormatted($appointment);
        }
        return $data;
    }

    public static function getDefault(): TemplateDataService
    {
        $dateFormatter = function(string|null $value){
            if($value == null){
                return null;
            }

            $dateOrFalse = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s\Z', $value);
            if($dateOrFalse === false){
                return null;
            }
            return $dateOrFalse->format("d.m.Y");
        };
        $timeFormatter = function(string|null $value){
            if($value == null){
                return null;
            }

            $dateOrFalse = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s\Z', $value);
            if($dateOrFalse === false){
                return null;
            }
            return $dateOrFalse->format("h:i");
        };

        return new TemplateDataService([
            (new TemplateKey('caption', fn(Appointment $appointment) => $appointment->getCaption())),
            (new TemplateKey('note', fn(Appointment $appointment) => $appointment->getNote())),
            (new TemplateKey('information', fn(Appointment $appointment) => $appointment->getInformation())),
            (new TemplateKey('link', fn(Appointment $appointment) => $appointment->getLink())),
            (new TemplateKey('startDate', fn(Appointment $appointment) => $appointment->getStartDate()))->withFormatter($dateFormatter),
            (new TemplateKey('endDate', fn(Appointment $appointment) => $appointment->getEndDate()))->withFormatter($dateFormatter),
            (new TemplateKey('startTime', fn(Appointment $appointment) => $appointment->getStartDate()))->withFormatter($timeFormatter),
            (new TemplateKey('endTime', fn(Appointment $appointment) => $appointment->getEndDate()))->withFormatter($timeFormatter),

            (new TemplateKey('calendar.name', fn(Appointment $appointment) => $appointment->getCalendar()?->getName())),
            (new TemplateKey('calendar.nameTranslated', fn(Appointment $appointment) => $appointment->getCalendar()?->getNameTranslated())),

            (new TemplateKey('address.meetingAt', fn(Appointment $appointment) => $appointment->getAddress()?->getMeetingAt())),
            (new TemplateKey('address.street', fn(Appointment $appointment) => $appointment->getAddress()?->getStreet())),
            (new TemplateKey('address.addition', fn(Appointment $appointment) => $appointment->getAddress()?->getAddition())),
            (new TemplateKey('address.district', fn(Appointment $appointment) => $appointment->getAddress()?->getDistrict())),
            (new TemplateKey('address.zip', fn(Appointment $appointment) => $appointment->getAddress()?->getZip())),
            (new TemplateKey('address.city', fn(Appointment $appointment) => $appointment->getAddress()?->getCity())),
            (new TemplateKey('address.country', fn(Appointment $appointment) => $appointment->getAddress()?->getCountry())),
        ]);
    }
}
