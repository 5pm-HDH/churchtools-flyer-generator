// Include Vuetify
import { createVuetify } from "vuetify";
import { aliases, mdi } from "vuetify/iconsets/mdi";

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
});
export default vuetifyPlugin;
