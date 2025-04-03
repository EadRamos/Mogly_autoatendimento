import axios from 'axios';
import authStore from '@store/auth.js';

const api = axios.create({
    baseURL: import.meta.env.VITE_HOST_API || 'http://localhost/api',
    timeout: 5000,
});

api.interceptors.request.use((config) => {
    const auth = authStore();

    // checa se um usuario está logado
    if (auth.token) config.headers.authorization = `Bearer ${auth.token}`;
    // checar se uma mesa esta logada (cliente)
    else if (auth.clienteToken) config.headers.authorization = `Bearer ${auth.clienteToken}`;

    return config;
}, 
(error) => {
    return Promise.reject(error);
});

api.interceptors.response.use((response) => response, (error) => {

    // checa se houve erro no token de login no servidor
    if(error.response.status === 401) {
        const auth = authStore();

        auth.deslogar();
    }

    return Promise.reject(error);
});

export default api;
