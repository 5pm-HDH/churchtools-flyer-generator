<script lang="ts" setup>

import ajax from "@/ajax.js";
import {type Appointment} from "@/Components/Types/Appointment.ts";
import {computed, onMounted, ref} from "vue";
import {type Calendar} from "@/Components/Types/Calendar.ts";

const props = defineProps<{
    onAppointments: (appointments: Appointment[]) => void;
}>();

const calendarIsLoading = ref<boolean>(false);
const calendars = ref<Calendar[]>([]);
const selectedCalendarId = ref<string | null>(null);
const selectedFromDate = ref<Date | null>(new Date(new Date().getTime() - 7 * 24 * 60 * 60 * 1000));
const selectedToDate = ref<Date | null>(new Date(new Date().getTime() + 7 * 24 * 60 * 60 * 1000));
const appointmentIsLoading = ref<boolean>(false);
const appointments = ref<Appointment[]>([]);

const filterIsComplete = computed(() => {
    return selectedCalendarId.value != null && selectedFromDate.value != null && selectedToDate.value != null;
})

onMounted(() => {
    loadCalendars();
});

const loadCalendars = async () => {
    calendarIsLoading.value = true;
    selectedCalendarId.value = null;
    const response = await ajax.get('/api/calendars');
    calendars.value = response.data.data;

    // Select first calendar if possible
    if (calendars.value.length > 0) {
        selectedCalendarId.value = calendars.value[0].id;
    }

    calendarIsLoading.value = false;
}

const loadAppointments = async () => {
    if (!filterIsComplete.value) {
        return;
    }
    try {
        appointmentIsLoading.value = true;
        const response = await ajax.get('/api/calendars/' + selectedCalendarId.value + "/appointments?fromDate=" + selectedFromDate.value?.toISOString() + "&toDate=" + selectedToDate.value?.toISOString());
        appointments.value = response.data.data;
        props.onAppointments(appointments.value);
    } finally {
        appointmentIsLoading.value = false;
    }
}

</script>
<template>
    <v-row>
        <v-col>

            <v-card
                :elevation="4"
                variant="outlined"
                class="mt-4"
            >
                <v-card-title>Terminfilter:</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" xs="8" sm="8" md="6" lg="4">
                            <v-select
                                label="Kalender*"
                                v-model="selectedCalendarId"
                                :items="calendars"
                                item-title="name"
                                item-value="id"
                                single-line
                                :loading="calendarIsLoading"
                                :disabled="calendarIsLoading"
                            ></v-select>
                        </v-col>
                        <v-col cols="12" xs="6" sm="5" md="3" lg="2">
                            <v-date-input
                                label="Von-Datum*"
                                v-model="selectedFromDate"
                            />
                        </v-col>
                        <v-col cols="12" xs="6" sm="5" md="3" lg="2">
                            <v-date-input
                                label="Bis-Datum*"
                                v-model="selectedToDate"
                            />
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-spacer/>
                        <v-btn
                            variant="outlined"
                            class="mr-2 mb-2"
                            :disabled="!filterIsComplete"
                            :loading="appointmentIsLoading"
                            @click="loadAppointments"
                        >
                            Termine laden
                        </v-btn>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>
