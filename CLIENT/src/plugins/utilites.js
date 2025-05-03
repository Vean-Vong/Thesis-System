/* eslint-disable import/no-unresolved */
/* eslint-disable import/extensions */
// @ts-ignores
// eslint-disable-next-line import/no-unresolved, import/extensions
import router from '@/router'

// @ts-ignore
import axios from 'axios'
import { useAuthStore } from './auth.module'

import Toast from '@/helper/toast'

const api = axios.create({
  baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`,
  headers: {
    'content-type': 'application/json',
    accept: 'application/json',
    'Access-Control-Allow-Origin': `${import.meta.env.VITE_API_BASE_URL}`,
  },
})

// Add a request interceptor
api.interceptors.request.use(
  function (config) {
    // @ts-ignore
    config.headers.Authorization = `Bearer ${useAuthStore()._accessToken}`
    config.headers['x-localization'] = window.localStorage.getItem('lang')
    // eslint-disable-next-line newline-before-return
    return config
  },
  function (error) {
    Toast.fire({
      icon: 'error',
      title: error.data?.message,
    })

    return Promise.reject(error.response.data.message)
  },
)

let failedQueue = []

const processQueue = (error = null) => {
  if (error)
    failedQueue.forEach(prom => {
      prom.reject(error)
    })
  else
    failedQueue.forEach(prom => {
      prom.resolve()
    })

  failedQueue = []
}

// Add a response interceptor
api.interceptors.response.use(
  function (response) {
    if (response.data.status === 201) {
      Toast.fire({
        icon: 'error',
        title: response.data?.message,
      })

      return Promise.reject(response)
    } else if (response.data.status === 200 && response.data.message) {
      Toast.fire({
        icon: 'success',
        title: response.data?.message,
      })
    } else if (response.data.status === 202 && response.data.message) {
      Toast.fire({
        icon: 'error',
        title: response.data?.message,
      })
    }

    return response
  },

  function (error) {
    const originalRequest = error.config

    if (error.response.status === 401 && !originalRequest?._retry) {
      originalRequest._retry = true

      if (useAuthStore()._isTokenRefreshing) {
        // eslint-disable-next-line promise/no-promise-in-callback
        return new Promise(function (resolve, reject) {
          failedQueue.push({ resolve, reject })
        })
          .then(() => {
            return api(originalRequest)
          })
          .catch(_error => {
            // eslint-disable-next-line promise/no-return-wrap
            return Promise.reject(_error)
          })
      }

      return new Promise((resolve, reject) => {
        // eslint-disable-next-line promise/no-promise-in-callback
        useAuthStore()
          .refreshToken()
          .then(() => {
            processQueue()
            resolve(api(originalRequest))
          })
          .catch(_error => {
            router.push({ name: 'login' })
            reject(_error)
          })
      })
    } else if (error.response.status === 404) {
      router.push({ name: 'not-found' })
    } else if (error.response.status === 403) {
      router.push({ name: 'not-authorized' })
    }

    Toast.fire({
      icon: 'error',
      title: error.response.data?.message,
    })

    return Promise.reject(error.response.data.message)
  },
)

export default api
