import axios from 'axios'
const instance = axios.create({
    baseURL: import.meta.env.VITE_URL,
    headers: {
        "Accept": "application/json",
        timeout: 1000,
    },
});
export default instance;
