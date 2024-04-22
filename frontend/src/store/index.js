import { createApp } from "vue";
import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";

// // base store
import state from "./state";
import getters from "./getters";
import mutations from "./mutations";
import actions from "./actions";

// // modules
import auth from "../store/modules/auth.module";
const store = createStore({
  plugins: [createPersistedState()],
  state,
  getters,
  mutations,
  actions,
  modules: {
    auth,
  },
});

export default store;
