export const useMainStore = defineStore("main", {
  state: () => ({
   darkMode: null
  }),
  getters: {
    themeMode () {
        return this.darkMode || 'light'
    }
  },
  actions: {
    setDarkMode(payload) {
       this.darkMode = payload;
    }
  },
  persist: true,
});
