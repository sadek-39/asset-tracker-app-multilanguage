import axios from 'axios';

const API_CONFIG = {
    GO_BACKEND: 'http://localhost:8080/api',
    JAVA_BACKEND: 'http://localhost:8085/api',
};

// Default to Go backend
const api = axios.create({
    baseURL: localStorage.getItem('backend_url') || API_CONFIG.GO_BACKEND,
});

api.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export const setBackend = (url) => {
    localStorage.setItem('backend_url', url);
    api.defaults.baseURL = url;
};

export default api;
