<script lang="ts" setup>

import {ref} from "vue";
import ajax from "@/ajax";

const props = defineProps<{
    selectedAppointmentIds: string[],
    calendarId: string|null,
}>();

const file = ref<File[]>([]);
const isLoading = ref<boolean>(false);

const uploadFlyer = async () => {

    let formData = new window.FormData();

    console.log("LENGTH OF FILES: ", file.value.length);

    formData.append("file", file.value[0]);
    formData.append("selectedAppointmentIdList", props.selectedAppointmentIds.join(","));
    formData.append("calendarId", props.calendarId);

    try{
        isLoading.value = true;
        const response = await ajax.post("/api/create_flyer", formData);
    }finally {
        isLoading.value = false;
    }

}

</script>
<template>
    <div class="mt-6">
        <v-file-input
            v-model="file"
        />
        <v-btn @click="uploadFlyer" :disabled="selectedAppointmentIds.length === 0 || file.length === 0 || !calendarId">Flyer erstellen</v-btn>
    </div>
</template>
