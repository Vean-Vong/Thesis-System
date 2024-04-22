import axios from "axios";
import router from "../../router";
import axiosApiInstance from "../../utilites";
import User from "../../view-model/user";

const state = {
  authenticated: false,
  user: {},
  accessToken: null,
};

const getters = {
  authenticated(state) {
    return state.authenticated;
  },
  user(state) {
    return new User(state.user);
  },
  accessToken(state) {
    return state.accessToken;
  },
};

const mutations = {
  SET_AUTH_USER(state, value) {
    state.user = value;
  },
  SET_AUTH_TOKEN(state, token) {
    state.accessToken = token.access_token;
  },
  SET_AUTHENTICATED(state, value) {
    state.authenticated = value;
  },
  REMOVE_AUTH_TOKEN(state) {
    state.accessToken = null;
  },
};

const actions = {
  login({ commit }, credential) {
    return new Promise((resolve, reject) => {
      axiosApiInstance
        .post("login", credential)
        .then((response) => {
          commit("SET_AUTHENTICATED", true);
          commit("SET_AUTH_TOKEN", response.data);
          resolve(response);
        })
        .catch((response) => {
          reject(response);
        });
    });
  },

  getUser({ commit }) {
    return new Promise((resolve, reject) => {
      axiosApiInstance
        .get("/user")
        .then((response) => {
          commit("SET_AUTH_USER", response.data.user);
          resolve();
        })
        .catch(() => {
          reject();
        });
    });
  },

  logout({ commit }) {
    // axiosApiInstance.get("logout").then((response) => {
    commit("SET_AUTHENTICATED", false);
    commit("REMOVE_AUTH_TOKEN");
    router.push({ name: "login" });
    // });
  },

  unauthorized({ commit }, state) {
    return new Promise((resolve, reject) => {
      commit("SET_AUTHENTICATED", false);
      commit("REMOVE_AUTH_TOKEN");
      router.push({ name: "login" });
      resolve();
    });
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
