// @ts-ignore
import Toast from "@/helper/toast";
import router from "@/router";
// @ts-ignore
import { useAuthStore } from '@/store/module/auth.module';
import axios from "axios";
import Swal from 'sweetalert2';

const api = axios.create({
  baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`,
  headers: {
    "content-type": "application/json",
    "accept": "application/json",
    "Access-Control-Allow-Origin": `${import.meta.env.VITE_API_BASE_URL}`
  },
});

// Add a request interceptor
api.interceptors.request.use(
  function (config) {
    // @ts-ignore
    config.headers.Authorization = `Bearer ${useAuthStore().accessToken}`;
    return config;
  },
  function (error) {
    Swal.fire({
      text: error.response.data?.message,
      icon: 'error',
        confirmButtonText: 'Got it',
        timer: 3500
    })    
    return Promise.reject(error.response.data.message);
  }
);

// Add a response interceptor
api.interceptors.response.use(
  function (response) {

    if(response.data.status === 201) {
      Swal.fire({
        text: response.data.message,
        icon: 'error',
        confirmButtonText: 'Got it',
        timer: 3500
      })      
      return Promise.reject(response)
    } else if(response.data.status === 200 && response.data.message) {      
      Toast.fire({
        icon: 'success',
        title: response.data?.message,
      })
    } else if(response.data.status === 202 && response.data.message) {
      Swal.fire({
        text: response.data.message,
        icon: 'error',
        confirmButtonText: 'Got it',        
      })      
    }
    return response;
  },
  function (error) {
    if (error.response.status === 401) {      
      useAuthStore().logout()
      router.push({name: "login"});
    } else if (error.response.status === 404) {
      router.push('/error');
    } else if(error.response.status === 403) {
      router.go(-1);
    }
    Swal.fire({
      text: error.response.data.message,
      icon: 'error',
      confirmButtonText: 'Got it',
      timer: 3500
    })
    return Promise.reject(error.response.data.message);
  }
);

export default api;
