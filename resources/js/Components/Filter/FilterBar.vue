<script lang="ts" setup>

import ajax from "@/ajax.js";
import {type Appointment} from "@/Components/Types/Appointment";
import ChurchToolsUrlTextField from "@/Components/Filter/ChurchToolsUrlTextField.vue";
import {ref, watch, watchEffect} from "vue";
import {type Calendar} from "@/Components/Types/Calendar";

const props = defineProps<{
    onAppointments: (appointments: Appointment[]) => void;
}>();

const churchToolsUrlIsSet = ref<boolean>(false);
const calendarIsLoading = ref<boolean>(false);
const calendars = ref<Calendar[]>([]);
const selectedCalendarId = ref<string | null>(null);
const appointmentIsLoading = ref<boolean>(false);
const appointments = ref<Appointment[]>([]);

const churchToolsUrlChanges = (value: boolean) => {
    churchToolsUrlIsSet.value = value;
    if (value === true) {
        loadCalendars();
    }
}

const loadCalendars = async () => {
    calendarIsLoading.value = true;
    selectedCalendarId.value = null;
    const response = await ajax.get('/api/calendars');
    calendars.value = response.data.data;
    calendarIsLoading.value = false;
}

const loadAppointments = async (calendarId: string) => {
    appointmentIsLoading.value = true;
    const response = await ajax.get('/api/calendars/' + calendarId + "/appointments");
    appointments.value = response.data.data;
    props.onAppointments(appointments.value);
    appointmentIsLoading.value = false;
}

watchEffect(() => {
    if (selectedCalendarId.value) {
        loadAppointments(selectedCalendarId.value);
    }
});

</script>
<template>
    <v-card
        :elevation="2"
    >
        <v-card-text>
            <church-tools-url-text-field
                :on-church-tools-url-set="churchToolsUrlChanges"
            />
            <v-select
                label="Kalender"
                v-model="selectedCalendarId"
                :items="calendars"
                item-title="name"
                item-value="id"
                single-line
            ></v-select>
        </v-card-text>
    </v-card>
</template>
