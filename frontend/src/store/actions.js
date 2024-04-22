export default {
  // changeLanguage({ commit }, locale)
  // {
  //     commit('CHANGE_LANGUAGE', locale)
  // },
  pushNotification({ commit }, { message, type }) {
    commit("PUSH_NOTIFICATION", { message, type });
  },

  removeNotification({ commit }, notification) {
    commit("REMOVE_NOTIFICATION", notification);
  },
};
