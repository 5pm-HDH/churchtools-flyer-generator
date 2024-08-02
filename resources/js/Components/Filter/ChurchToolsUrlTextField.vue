<script lang="ts" setup>

const props = defineProps<{
    onChurchToolsUrlSet: (isSet: boolean) => void;
}>();

import {onMounted, ref} from "vue";
import ajax from "@/ajax.js";

const localStorageUrlKey = "CHURCHTOOLS_URL";

const churchToolsUrl = ref<string|null>();

onMounted(() => {
    const url = localStorage.getItem(localStorageUrlKey);
    if(url){
        ajax.defaults.headers.common['ChurchTools-Url'] = url;
        churchToolsUrl.value = url;
        props.onChurchToolsUrlSet(true);
    }else{
        props.onChurchToolsUrlSet(false);
    }
})

const updateChurchToolsUrl = (url?: string) => {
    ajax.defaults.headers.common['ChurchTools-Url'] = url;
    churchToolsUrl.value = url;
    localStorage.setItem(localStorageUrlKey, url);
    if(url){
        props.onChurchToolsUrlSet(true);
    }else{
        props.onChurchToolsUrlSet(false);
    }
}

</script>
<template>
    <v-text-field
        label="ChurchTools Url"
        :model-value="churchToolsUrl"
        @update:model-value="url => updateChurchToolsUrl(url)"
    />
</template>
