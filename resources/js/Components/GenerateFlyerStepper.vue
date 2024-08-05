<script lang="ts" setup>

import {computed, ref} from "vue";
import FilterBar from "@/Components/SelectAppointments/FilterBar.vue";
import {type Appointment} from "./Types/Appointment";
import AppointmentList from "@/Components/SelectAppointments/AppointmentList.vue";
import TemplateList from "@/Components/SelectTemplates/TemplateList.vue";
import GenerateFlyerList from "@/Components/GenerateFlyer/GenerateFlyerList.vue";

const step = ref<number>(1);
const items = computed<string[]>(() => {
    return [
        "Termine auswählen" + (selectedAppointmentIds.value.length > 0 ? " (" + selectedAppointmentIds.value.length + ")" : ""),
        "Template wählen",
        "Flyer generieren"
    ]
})

const appointments = ref<Appointment[]>([]);
const selectedAppointmentIds = ref<string[]>([]);
const selectedAppointments = computed(() => {
    return appointments.value.filter(appointment => selectedAppointmentIds.value.includes(appointment.id));
});
const selectedTemplateId = ref<string | "CUSTOM_UPLOAD">('CUSTOM_UPLOAD');
const customTemplateFile = ref<File[]>([]);

const onAppointmentLoaded = (appointmentArray: Appointment[]) => {
    appointments.value = appointmentArray;
    selectedAppointmentIds.value = [];
}

const toggleAppointmentSelection = (appointmentId: string) => {
    if (selectedAppointmentIds.value.includes(appointmentId)) {
        selectedAppointmentIds.value = selectedAppointmentIds.value.filter(id => id !== appointmentId);
    } else {
        selectedAppointmentIds.value = [...selectedAppointmentIds.value, appointmentId];
    }
}


</script>
<template>
    <v-stepper v-model="step" :items="items" next-text="Weiter" prev-text="Zurück" :editable="true">
        <template v-slot:item.1>
            <h5 class="text-h5">Termine auswählen</h5>
            <FilterBar :on-appointments="onAppointmentLoaded"></FilterBar>
            <AppointmentList
                :appointments="appointments"
                :on-click-appointment="(appointmentId: string) => toggleAppointmentSelection(appointmentId)"
                :selected-appointment-ids="selectedAppointmentIds"
            />
        </template>
        <template v-slot:item.2>
            <h5 class="text-h5">Template auswählen</h5>
            <TemplateList
                :selected-template-id="selectedTemplateId"
                :on-click-template="templateId => selectedTemplateId = templateId"
            >
                <template #upload-form>
                    <v-file-input
                        v-model="customTemplateFile"
                    />
                </template>

            </TemplateList>
        </template>
        <template v-slot:item.3>
            <h5 class="text-h5">Flyer generieren</h5>
            <GenerateFlyerList
                :custom-template-file="customTemplateFile"
                :selected-template-id="selectedTemplateId"
                :selected-appointments="selectedAppointments"
            />
        </template>
    </v-stepper>
</template>
