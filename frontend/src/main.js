import { createApp } from "vue";
import "./style.css";
import App from "./App.vue";
import axios from "axios";
import router from "./router";
import VueAxios from "vue-axios";
import { Quasar } from "quasar";
import "@quasar/extras/material-icons/material-icons.css";
import "quasar/src/css/index.sass";

import store from "./store";
import { Notify } from "quasar";
createApp(App)
  .use(router)
  .use(VueAxios, axios)
  .use(Quasar, { plugins: { Notify } })
  .use(store)
  .mount("#app");
