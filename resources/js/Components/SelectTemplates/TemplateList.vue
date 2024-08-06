<script lang="ts" setup>

import {onMounted, ref} from "vue";
import type {Template} from "@/Components/Types/Template.ts";
import ajax from "@/ajax";

const props = defineProps<{
    onClickTemplate: (templateId: string) => void;
    selectedTemplateId: string;
}>();

const templatesIsLoading = ref<boolean>(false);
const templates = ref<Template[]>([]);
const showCustomTemplateHintDialog = ref<boolean>(false);

onMounted(async () => {
    try {
        templatesIsLoading.value = true;
        const response = await ajax.get('/api/templates');
        templates.value = response.data.data;
    } finally {
        templatesIsLoading.value = false;
    }
})

</script>
<template>
    <v-row>
        <v-col
            cols="12"
            v-if="templatesIsLoading"
        >
            <v-skeleton-loader type="table-row"/>
        </v-col>

        <v-col
            cols="12"
            md="8"
            lg="7"
        >
            <v-card
                class="mt-4"
                @click="() => onClickTemplate('CUSTOM_UPLOAD')"
                variant="outlined"
            >
                <v-card-title>
                    <div class="d-flex">

                        <v-icon
                            size="small"
                            :icon="selectedTemplateId == 'CUSTOM_UPLOAD'
                            ? 'mdi-checkbox-marked-circle'
                             : 'mdi-checkbox-blank-circle-outline'"
                        />
                        Template hochladen
                        <v-spacer/>
                        <v-btn variant="outlined" prepend-icon="mdi-information-outline"
                               @click="() => showCustomTemplateHintDialog = true">Hinweis
                        </v-btn>
                    </div>
                </v-card-title>
                <v-card-text>
                    <slot name="upload-form"></slot>
                </v-card-text>
            </v-card>
        </v-col>

        <v-dialog v-model="showCustomTemplateHintDialog" width="auto">
            <v-card
                max-width="800"
                prepend-icon="mdi-information-outline"
                title="Hinweis zum Erstellen eines Templates"
            >
                <v-card-text>
                    <p>Als Template muss eine Word-Datei hochgeladen werden, die als Platzhalter die Template Codes
                        verwendet.</p>
                    <p class="mt-2">Der Template Code wird innerhalb eines Dollarzeichen und geschweiften Klammer
                        gesetzt. Beispiel für den Template Code "caption":
                        <pre>${caption}</pre>
                    </p>
                    <p class="mt-2">Welche Template Codes für einen Termin zur Verfügung stehen hängt von den gepflegten
                        Daten in ChurchTools ab. Die verfügbaren Template Codes werden an den Terminen mit
                        angezeigt:</p>
                    <img :src="'img/example-template-codes.png'" alt="Screenshot Template Codes"
                         style="max-height: 250px"/>
                    <v-alert class="mt-4" type="info" variant="outlined" title="Tipp für den Anfang">Lade für den Anfang
                        am besten ein schon bestehendes Template herunter und passe es nach deinen Anforderungen an.
                    </v-alert>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-col
            v-for="template in templates"
            :key="template.id"
            cols="12"
            md="8"
            lg="7"
        >
            <v-card
                class="mt-4"
                @click="() => onClickTemplate(template.id)"
                variant="outlined"
            >
                <v-card-title>

                    <div class="d-flex">
                        <v-icon
                            size="small"
                            :icon="selectedTemplateId == template.id
                            ? 'mdi-checkbox-marked-circle'
                             : 'mdi-checkbox-blank-circle-outline'"
                        />
                        {{ template.name }}
                        <v-spacer/>
                        <v-btn variant="outlined" append-icon="mdi-download" target="_blank"
                               :href="template.templateSrc">Download
                        </v-btn>
                    </div>
                </v-card-title>
                <v-card-text>
                    <v-img v-if="template.imgSrc" max-height="300" :src="template.imgSrc"/>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>
