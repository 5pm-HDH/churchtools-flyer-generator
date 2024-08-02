import {Calendar} from "./Calendar";

export type Appointment = {
    id: string;
    caption: string;
    note: string;
    version: string;
    calendar: Calendar;
    address: string|null;
    calculated_startDate: string;
    calculated_endDate: string;
    image?: {
        imageUrl: string;
    }
}
