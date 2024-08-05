<script lang="ts" setup>

import {onMounted, ref} from "vue";
import ajax from "@/ajax.js";
import ChurchToolsUrlDialog from "@/Components/ChurchToolsUrlDialog.vue";
import GenerateFlyerStepper from "@/Components/GenerateFlyerStepper.vue";

const LOCAL_STORAGE_URL_KEY = "FLYER_GENERATOR_CHURCHTOOLS_URL";
const LOCAL_STORAGE_SITE_NAME_KEY = "FLYER_GENERATOR_CHURCHTOOLS_SITE_NAME";
const churchToolsUrl = ref<string | null>(null);
const churchToolsSiteName = ref<string | null>(null);

onMounted(() => {
    const url = localStorage.getItem(LOCAL_STORAGE_URL_KEY);
    const siteName = localStorage.getItem(LOCAL_STORAGE_SITE_NAME_KEY);
    if (url && url != "" && siteName && siteName != "") {
        setChurchToolsUrl(url, siteName, false);
    }
});

const setChurchToolsUrl = (url: string, siteName: string, connectionIsMocked: boolean) => {
    churchToolsUrl.value = url;
    churchToolsSiteName.value = siteName;
    ajax.defaults.headers.common['ChurchTools-Url'] = url;
    if(!connectionIsMocked){
        localStorage.setItem(LOCAL_STORAGE_URL_KEY, url);
        localStorage.setItem(LOCAL_STORAGE_SITE_NAME_KEY, siteName);
    }else{
        ajax.defaults.headers.common['ChurchTools-Mock'] = true;
    }
}

const logout = () => {
    churchToolsUrl.value = null;
    churchToolsSiteName.value = null;
    ajax.defaults.headers.common['ChurchTools-Url'] = null;
    ajax.defaults.headers.common['ChurchTools-Mock'] = null;
    localStorage.setItem(LOCAL_STORAGE_URL_KEY, "");
}

const onChurchToolsUrlSet = (url: string, siteName: string | null, connectionIsMocked: boolean) => {
    setChurchToolsUrl(url, siteName ?? url, connectionIsMocked);
}
</script>
<template>

    <v-app>
        <v-main>
            <v-app-bar>
                <template #title>
                    Flyer Generator
                </template>
                <v-spacer/>
                <span class="mr-4">{{ churchToolsSiteName ?? '' }}</span>
                <v-btn icon="mdi-logout" v-if="!!churchToolsUrl" @click="logout"/>
            </v-app-bar>

            <v-container>

                <v-row>
                    <v-col cols="0" lg="2" xl="3"><!-- Center Dialog --></v-col>
                    <v-col cols="12" lg="8" xl="6">
                        <ChurchToolsUrlDialog v-if="!churchToolsUrl" :on-church-tools-url-set="onChurchToolsUrlSet"/>
                    </v-col>
                </v-row>

                <GenerateFlyerStepper v-if="churchToolsUrl"/>
            </v-container>
        </v-main>
    </v-app>
</template>
