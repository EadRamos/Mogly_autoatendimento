import axios from 'axios';
import { defineStore } from 'pinia';

const authStore = defineStore('auth', {
    state: () => ({
        token: null,
        clienteToken: null,
        usuario: null,
        permissoes: []
    }),
    actios: {
        async login(credenciais) {
            try {
                const response = await axios.post('/login', credenciais);
                const { token, usuario, permissoes } = response.data;

                this.token = token;
                this.usuario = usuario;
                this.permissoes = permissoes;

                return true
            } catch (error) {
                return Promise.reject(error); // troca por throw error caso problema
            }
        },

        async clienteLogin(mesaId, comandaId, restauranteId) {
            try {
                const response = await axios.post('/cliente_login', {mesaId, comandaId, restauranteId});
                const { dados, assinatura, permissoes } = response.data;

                this.clienteToken = JSON.stringify({dados, assinatura});
                this.permissoes = permissoes;

                return true;
            } catch (error) {
                return Promise.reject(error); // troca por throw error caso problema
            }
        },

        deslogar() {
            this.token = null;
            this.clienteToken = null;
            this.usuario = null;
            this.permissoes = null;
        },
    },
    getters: {
        logado: (state) => !!state.token || !!state.clienteToken,
        podeAcessar: (state) => {
            return (permissao) => state.permissoes.find( p => p === permissao);
        },
    }
});

export default authStore;