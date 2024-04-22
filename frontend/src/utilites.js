import axios from "axios";
import constants from "./constants";
import store from "./store";

const axiosApiInstance = axios.create({
  baseURL: `${constants.apiUrl}`,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

axiosApiInstance.interceptors.request.use((config) => {
  const token = store.state.auth.accessToken;
  // config.headers['x-localization'] = store.state.locale
  if (token) config.headers["Authorization"] = `Bearer ${token}`;

  return config;
});

axiosApiInstance.interceptors.response.use(
  (response) => {
    return response;
  },
  async (error) => {
    if (error.response.status == 401) {
      return new Promise((resolve, reject) => {
        store.dispatch("auth/unauthorized");
      });
    }
    return Promise.reject(error);
  }
);

export default axiosApiInstance;
