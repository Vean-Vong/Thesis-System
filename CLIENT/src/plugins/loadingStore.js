import { defineStore } from 'pinia'

export const useLoadingStore = defineStore('loading', {
  state: () => ({
    loading: false,
  }),

  actions: {
    setLoading(value) {
      if (value) {
        this.loading = value
      }
    },
  },

  persist: {
    storage: localStorage,
  },

  // persist: true,
})
