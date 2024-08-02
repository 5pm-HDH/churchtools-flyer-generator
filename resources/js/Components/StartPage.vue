<script lang="ts" setup>

import FilterBar from "@/Components/Filter/FilterBar.vue";
import {Appointment} from "@/Components/Types/Appointment";
import AppointmentList from "@/Components/AppointmentList.vue";
import {ref} from "vue";

const appointments = ref<Appointment[]>([]);
const selectedAppointmentIds = ref<string[]>([]);

const onAppointmentsLoaded = (appointmentArray: Appointment[]) => {
    appointments.value = appointmentArray;
    selectedAppointmentIds.value = [];
}

const toggleAppointmentSelection = (appointmentId: string) => {
    if(selectedAppointmentIds.value.includes(appointmentId)){
        selectedAppointmentIds.value = selectedAppointmentIds.value.filter(id => id !== appointmentId);
    }else{
        selectedAppointmentIds.value = [...selectedAppointmentIds.value, appointmentId];
    }
}

</script>
<template>
    <v-app>
        <v-main>
            <v-container>
                <filter-bar :on-appointments="onAppointmentsLoaded" />

                <v-row>
                    <v-col sm="10">
                        <appointment-list
                            :appointments="appointments"
                            :on-click-appointment="toggleAppointmentSelection"
                            :selected-appointment-ids="selectedAppointmentIds"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
