<h2>DIRETORIOS E ARQUIVOS</h2>
- docker-compose.dev.yml é responsável pelas imagens e conteinerização da aplicação em desenvolvimento
- docker-compose.prod.yml é responsável pelas imagens e conteinerização da aplicação em produção

- nginx/dev.conf representa as configurações do nginx para desenvolvimeto
- nginx/prod.conf representa as configurações do nginx para produção
- frontend/ representa o diretorio da aplicação vue
- backend/ representa o diretorio da api

<h2>COMANDOS</h2>
**docker**
``` docker-compose -f docker-compose.dev.yml up --build // sobe e monta os containeres de desenvolvimento ```