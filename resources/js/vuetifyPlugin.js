// Include Vuetify
import {createVuetify} from "vuetify";
import {aliases, mdi} from "vuetify/iconsets/mdi";
import 'vuetify/styles'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import {VDateInput} from "vuetify/labs/components";

const vuetifyPlugin = createVuetify({
    icons: {
        defaultSet: "mdi",
        aliases,
        sets: {
            mdi,
        },
    },
    defaults: {
        global: {
            density: "compact",
        },
    },
    VButton: {
        variant: "outlined",
    },
    components: {
        VDateInput
    }
});
export default vuetifyPlugin;
