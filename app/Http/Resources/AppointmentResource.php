<?php

namespace App\Http\Resources;

use App\Services\TemplateDataService;
use CTApi\Models\Calendars\Appointment\Appointment;
use CTApi\Traits\Model\FillWithData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Appointment
 */
class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->toData();
        $templateDataService = TemplateDataService::getDefault();
        $data["template_data"] = $templateDataService->getFormattedValuesFromAppointment($this->resource);
        return $data;
    }
}
