import { defineStore } from 'pinia';

const carrinhoStore = defineStore('carrinho', {
    state: () => ({
        pedidos: [],
        total: 0,
        quantidade: 0,
    }),
    actions: {
        adicionarProduto (produto, quantidade = null)
        {
            if ( !this.carrinho.find( p => p.id === produto.id)) 
            {
                this.carrinho.push({...produto, quantidade: quantidade || 1})
            };
        },
        excluirProduto (id)
        {
            this.carrinho.filter( p => p.id !== id);
        },
        esvaziarCarrinho ()
        {
            this.carrinho = [];
        },
        atualizarQuantidade (id, quantidade)
        {
            const p = this.carrinho.find( p => p.id === id);
            
            if (p) p.quantidade = quantidade;
        },
        getters: {
            total: (state) => state.total,
            quantidade: (state) => state.quantidade,
            pedidos: (state) => state.pedidos,
        },
        persist: true // usar localStorage em vez de sessionStorage
    }
});

export default carrinhoStore;