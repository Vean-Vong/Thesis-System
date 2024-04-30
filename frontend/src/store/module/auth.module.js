import User from '../../class/User'
import Toast from '../../helper/toast.ts'
import router from '../../router'
import api from '../../utils/utilites'
export const useAuthStore = defineStore('auth', {
  state: () => ({
    _authenticated: false,
    _user: {},
    _accessToken: null,    
  }),
  getters: {
    authenticated() {
      return this._authenticated
    },
    user() {      
      return new User(this._user)
    },
    accessToken() {
      return this._accessToken
    },
  },
  actions: {
    login(payload) {
      api
        .post('/login', payload)
        .then(res => {
          this._authenticated = true
          this._accessToken = res.data.access_token
          this._user = res.data?.user          
          router.push({ name: 'index' })
          Toast.fire({
            icon: 'success',
            title: 'Signed in successfully',
          })
        })
        .catch(err => {
          console.log(err)
        })
    },
    logout() {
      this._authenticated = false
      this._accessToken = null
      router.push('/login')
      Toast.fire({
        icon: 'success',
        title: 'Log out successfully',
      })
    },
  },
  persist: {
    storage: sessionStorage,
  },
})
