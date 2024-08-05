<script lang="ts" setup>

import {type Appointment} from "@/Components/Types/Appointment.ts";

const props = defineProps<{
    appointments: Appointment[],
    onClickAppointment: (appointmentId: string) => void;
    selectedAppointmentIds: string[]
}>();

const templateDataWithoutEmpty = (templateData: Record<string, string>) => {
    return Object.fromEntries(Object.entries(templateData).filter(([_, v]) => v != null))
}

</script>
<template>
    <v-row>
        <v-col
            v-for="appointment in appointments"
            :key="appointment.id"
            cols="12"
            md="8"
            lg="5"
            xl="4"
        >
            <v-card
                class="mt-4"
                @click="() => onClickAppointment(appointment.id)"
                variant="outlined"
            >
                <v-card-title>
                    <v-icon
                        size="small"
                        :icon="selectedAppointmentIds.includes(appointment.id)
                            ? 'mdi-checkbox-marked-circle'
                             : 'mdi-checkbox-blank-circle-outline'"
                    />
                    {{ appointment.caption }}
                </v-card-title>
                <v-card-text>
                    <v-list>
                        <v-list-item
                            v-for="[key, value] of Object.entries(templateDataWithoutEmpty(appointment.template_data))"
                            :key="key"
                        >
                            <template #title>
                                <span :style="{'white-space': key == 'information' ? 'break-spaces' : 'nowrap'}">
                                {{ value }}
                                </span>
                            </template>
                            <template #append>
                                <v-chip>{{ key }}</v-chip>
                            </template>
                        </v-list-item>
                    </v-list>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>
