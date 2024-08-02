<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChurchToolsModelResourceCollection;
use CTApi\CTConfig;
use CTApi\Models\Calendars\Appointment\AppointmentRequest;
use CTApi\Models\Calendars\Calendar\CalendarRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoadCalendarController extends Controller
{
    public function loadCalendars(Request $request): JsonResponse
    {
        $calendars = CalendarRequest::all();
        return (new ChurchToolsModelResourceCollection($calendars))->response();
    }

    public function loadAppointments(int $calendarId, Request $request): JsonResponse
    {
        $appointments = AppointmentRequest::forCalendar($calendarId)->get();
        $appointmentsUnique = [];
        foreach ($appointments as $appointment) {
            $appointmentsUnique[$appointment->getId()] = $appointment;
        }
        return (new ChurchToolsModelResourceCollection(array_values($appointmentsUnique)))->response();
    }
}
