<script lang="ts" setup>

import {ref} from "vue";
import ajax from "@/ajax.js";

const props = defineProps<{
    onChurchToolsUrlSet: (url: string, siteName: string | null, connectionIsMocked: boolean) => void;
}>();

const url = ref<string | null>(null);
const verificationInProgress = ref<boolean>(false);
const errorMessage = ref<string | null>(null);
const verificationSuccessful = ref<boolean>(false);

const verifyUrl = async (connectionIsMocked: boolean) => {
    if(connectionIsMocked){
        url.value = 'https://test-data.church.tools/'
    }

    try {
        if (!!url.value) {
            verificationInProgress.value = true;
            errorMessage.value = null;
            const response = await ajax.post('/api/verify-url', {
                churchToolsUrl: url.value
            }, {
                headers: {
                    'ChurchTools-Mock': connectionIsMocked ? true : null,
                }
            });
            verificationSuccessful.value = true;
            setTimeout(() => {
                props.onChurchToolsUrlSet(url.value ?? '', response.data.data.shortName ?? null, connectionIsMocked);
                verificationSuccessful.value = false;
            }, 3000);
        }
    } catch (e) {
        errorMessage.value = e.response.data.error;
    } finally {
        verificationInProgress.value = false;
    }
}

</script>
<template>
    <v-card :elevation="5" class="mt-10" :loading="verificationSuccessful">
        <v-card-title>
            ChurchTools Flyer Generator
        </v-card-title>
        <v-card-text>
            <div class="mt-2 mb-6">
                Mit dem FlyerGenerator kannst du aus den Terminen, die du in ChurchTools hinterlegst ganz einfach Fyler und Grafiken erstellen.
                Du kannst entweder Testdaten verwenden oder dich direkt mit deiner ChurchTools URL verbinden:
            </div>
            <v-text-field
                label="ChurchTools URL"
                :color="verificationSuccessful ? 'success' : undefined"
                :append-icon="verificationSuccessful ? 'mdi-check' : undefined"
                v-model="url"
                :disabled="verificationInProgress"
                :error="!!errorMessage"
                :error-messages="errorMessage"
                @keydown.enter="() => verifyUrl(false)"
            />
            <div class="d-flex">
                <v-spacer/>
                <v-btn
                    class="mr-2"
                    variant="text"
                    :disabled="verificationSuccessful"
                    @click="() => verifyUrl(true)"
                >
                    Testdaten verwenden
                </v-btn>
                <v-btn
                    variant="outlined"
                    :color="verificationSuccessful ? 'success' : undefined"
                    :prepend-icon="verificationSuccessful ? 'mdi-check' : undefined"
                    :disabled="!url || verificationSuccessful"
                    :loading="verificationInProgress"
                    @click="() => verifyUrl(false)">
                    ChurchTools verbinden
                </v-btn>
            </div>
        </v-card-text>
    </v-card>
</template>
