<script lang="ts" setup>

import type {Appointment} from "@/Components/Types/Appointment.ts";
import {computed, ref} from "vue";
import ajax from "@/ajax";
import type {AxiosResponse} from "axios";

const props = defineProps<{
    selectedTemplateId: string | "CUSTOM_UPLOAD",
    customTemplateFile: File[],
    selectedAppointments: Appointment[],
}>();

const templateIsMissing = computed(() => {
    return props.selectedTemplateId === 'CUSTOM_UPLOAD' && props.customTemplateFile.length === 0;
});

const flyerIsGeneratingForAppointmentIds = ref<string[]>([]);

const generateFlyerForAppointment = async (appointment: Appointment) => {
    try {
        flyerIsGeneratingForAppointmentIds.value = [...flyerIsGeneratingForAppointmentIds.value, appointment.id];

        let response: AxiosResponse;
        if (props.selectedTemplateId === 'CUSTOM_UPLOAD') {
            let formData = new window.FormData();
            formData.append("file", props.customTemplateFile[0]);
            formData.append("appointmentId", appointment.id);
            formData.append("calendarId", appointment.calendar.id);

            response = await ajax.post("/api/templates/custom/create_flyer", formData, {responseType: 'blob'});

        } else {
            response = await ajax.post("/api/templates/" + props.selectedTemplateId + "/create_flyer", {
                appointmentId: appointment.id,
                calendarId: appointment.calendar.id
            }, {responseType: 'blob'});
        }
        const link = document.createElement('a');
        const href = window.URL.createObjectURL(response.data);
        link.href = href;
        link.download = "Flyer-Export.docx";
        document.body.appendChild(link);
        link.click();
        // Clean Up
        window.URL.revokeObjectURL(href);
        document.body.removeChild(link);
    } finally {
        flyerIsGeneratingForAppointmentIds.value = flyerIsGeneratingForAppointmentIds.value.filter(id => id !== appointment.id);
    }
}


</script>
<template>

    <div class="mt-4">
        <v-row>
            <v-col v-if="selectedAppointments.length === 0" cols="12" lg="10" xl="8">
                <v-alert type="info">
                    Es muss in Schritt 1 mindestens ein Termin ausgewählt werden.
                </v-alert>
            </v-col>
        </v-row>

        <v-row>
            <v-col v-if="templateIsMissing" cols="12" lg="10" xl="8">
                <v-alert type="info">
                    Es muss in Schritt 2 ein Standardtemplate ausgewählt sein oder bei einem eigenen Template eine Template-Datei
                    ausgewählt sein.
                </v-alert>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12" md="10" lg="8" xl="6">
                <v-list>
                    <v-list-item
                        v-for="appointment in selectedAppointments"
                        :key="appointment.id"
                        :title="appointment.caption"
                    >
                        <template #append>
                            <v-btn
                                :disabled="templateIsMissing"
                                variant="outlined"
                                append-icon="mdi-download"
                                :loading="flyerIsGeneratingForAppointmentIds.includes(appointment.id)"
                                @click="() => generateFlyerForAppointment(appointment)"
                            >
                                Flyer generieren
                            </v-btn>
                        </template>
                    </v-list-item>
                </v-list>
            </v-col>
        </v-row>

    </div>
</template>
