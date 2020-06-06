import axios from 'axios'
// Local Storage Service
import LocalStorageService from './localstorageservice'

const localStorageService = LocalStorageService.getService()

const instance = axios.create({
    baseURL: process.env.MIX_APP_API_URL,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.head.querySelector(
          'meta[name="csrf-token"]').content,
    },
});

instance.interceptors.request.use(
  config => {
      const token = localStorageService.getAccessToken()
      if (token) {
          config.headers['Authorization'] = 'Bearer ' + token
      }
      // config.headers['Content-Type'] = 'application/json';
      return config
  },
  error => {
      Promise.reject(error)
  })

instance.interceptors.response.use((response) => {
      // Do something with response data
      return response
  },
  function (error) {
      const originalRequest = error.config
      if (error.response.status === 401 && !originalRequest._retry) {
          originalRequest._retry = true
          axios.post('/token/refresh',
            {
                'refresh_token': localStorageService.getRefreshToken(),
            }).then(res => {
              if (res.status === 201) {
                  // 1) put token to LocalStorage
                  localStorageService.setToken(res.data)

                  // 2) Change Authorization header
                  axios.defaults.headers.common['Authorization'] = 'Bearer ' +
                    localStorageService.getAccessToken()

                  // 3) return originalRequest object with Axios.
                  return axios(originalRequest)
              }
          })
      }

      // return Error object with Promise
      return Promise.reject(error)
  })

export default instance;