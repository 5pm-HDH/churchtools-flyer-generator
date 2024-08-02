<script lang="ts" setup>

import {type Appointment} from "@/Components/Types/Appointment";

const props = defineProps<{
    appointments: Appointment[],
    onClickAppointment: (appointmentId: string) => void;
    selectedAppointmentIds: string[]
}>();

</script>
<template>
    <div>
        <v-card
            v-for="appointment in appointments"
            :key="appointment.id"
            :elevation="2"
            :variant="selectedAppointmentIds.includes(appointment.id) ? 'tonal' : 'outlined'"
            class="mt-4"
            @click="() => onClickAppointment(appointment.id)"
        >
            <v-card-title>{{ appointment.caption }}</v-card-title>
            <v-card-text>
                <li>
                    <span class="font-weight-bold">Start Datum:</span> {{ appointment.calculated_startDate}}
                </li>
                <li>
                    <span class="font-weight-bold">Beschreibung:</span> {{ appointment.note ?? '-'}}
                </li>
                <li>
                    <span class="font-weight-bold">Adresse:</span> {{ appointment.address ?? '-'}}
                </li>
                <li v-if="appointment.image">
                    <span class="font-weight-bold">Bild:</span> <v-img max-height="200px" :src="appointment.image.imageUrl" />
                </li>
            </v-card-text>
        </v-card>
    </div>
</template>
