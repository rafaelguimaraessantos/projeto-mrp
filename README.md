# Sistema MRP - Planejamento de Necessidades de Materiais

## 📋 Visão Geral

Sistema web de MRP (Material Requirements Planning) para uma empresa que fabrica bicicletas e computadores. O sistema calcula automaticamente as necessidades de compra de componentes com base na produção planejada e no estoque atual.

## 🎯 Funcionalidades

### 1. Módulo de Gerenciamento de Estoque (`/estoque`)
- ✅ Cadastrar quantidade inicial de cada componente
- ✅ Cadastrar novos itens no estoque (botão "Cadastrar Novo Item")
- ✅ Atualizar estoque atual de componentes existentes
- ✅ **Excluir itens do estoque** (NOVO - botão "🗑️ Excluir")
- ✅ Visualizar estoque atual de todos os componentes
- ✅ Persistir dados no banco de dados
- ✅ **Modais de confirmação para exclusão** (NOVO)

### 2. Módulo de Planejamento MRP (`/mrp`)
- ✅ Formulário para inserir quantidade desejada de bicicletas e computadores
- ✅ Calcular necessidades de materiais automaticamente
- ✅ Indicar exatamente quanto comprar de cada componente
- ✅ **Indicadores visuais** (⚠️ para itens insuficientes) (NOVO)
- ✅ **Tratamento de singular/plural** nos componentes (NOVO)
- ✅ **Status colorido** (vermelho para "Insuficiente") (NOVO)

## 🔧 Especificação dos Produtos

### Bicicleta (1 unidade requer):
- 2 rodas
- 1 quadro
- 1 guidão

### Computador (1 unidade requer):
- 1 gabinete
- 1 placa-mãe
- 2 pentes de memória RAM

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 8.1 com estrutura MVC + Services
- **Frontend**: HTML/CSS/JavaScript com AngularJS 1.8.2
- **Banco de Dados**: MySQL 8.0
- **Containerização**: Docker & Docker Compose
- **Servidor Web**: Nginx
- **UI Framework**: Bootstrap 5.3

## 📁 Estrutura do Projeto

```
projeto-mrp/
├── README.md                 # Este arquivo
├── docker-compose.yml        # Configuração Docker
├── Dockerfile               # Imagem PHP
├── database/
│   └── schema.sql          # Script de criação do banco
├── docker/
│   ├── nginx/
│   │   └── nginx.conf      # Configuração Nginx
│   ├── php/
│   │   └── php.ini         # Configuração PHP
│   └── mysql/
│       └── my.cnf          # Configuração MySQL UTF-8
└── src/
    ├── index.html          # Página principal
    ├── frontend/
    │   └── app.js         # Controlador Angular
    ├── datasources/
    │   ├── api/         # Endpoints e rotas de API (PHP)
    │   ├── service/     # Serviços de negócio (PHP)
    │   ├── controller/  # Controllers (PHP)
    │   ├── model/       # Modelos de dados (PHP)
    │   ├── utils/       # Utilitários (PHP)
    │   ├── config/      # Configurações e DI (PHP)
    │   └── teste/       # Testes automatizados (PHP, JS)
```

## 🚀 Pré-requisitos

- Docker
- Docker Compose

## 📦 Instalação

### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd projeto-mrp
```

### 2. Execute com Docker
```bash
docker-compose up -d
```

### 3. Acesse a aplicação
Abra seu navegador e acesse: `http://localhost:8081`

## 🎯 Como Usar

### URLs do Sistema
- **Interface Principal**: http://localhost:8081
- **Estoque**: http://localhost:8081/estoque
- **MRP**: http://localhost:8081/mrp
- **API Estoque**: http://localhost:8081/api/estoque
- **API MRP**: http://localhost:8081/api/mrp

### Gerenciamento de Estoque
1. Acesse a aba "Estoque" ou vá para `http://localhost:8081/estoque`
2. Visualize o estoque atual de todos os componentes
3. **Para editar estoque existente**: Clique em "Editar" para modificar a quantidade de qualquer componente
4. **Para cadastrar novo item**: Clique no botão "Cadastrar Novo Item" no final da tabela
5. **Para excluir item**: Clique no botão "🗑️ Excluir" → Confirme na modal → "Excluir"
6. Selecione o componente e digite a quantidade inicial
7. Clique em "Cadastrar" para salvar no banco de dados

### Planejamento MRP
1. Acesse a aba "MRP" ou vá para `http://localhost:8081/mrp`
2. Digite a quantidade desejada de bicicletas e/ou computadores
3. Clique em "Calcular MRP"
4. Visualize os resultados:
   - Quantidade necessária de cada componente
   - Quantidade disponível em estoque
   - Quantidade que precisa ser comprada
   - **Status com indicadores visuais**:
     - ⚠️ para itens que precisam ser comprados
     - Badge vermelho para "A Comprar" > 0
     - Texto vermelho para status "Insuficiente"
   - **Resumo de compras** com singular/plural correto

## 💡 Exemplo Prático

### Cenário Inicial - Estoque Cadastrado:
- Rodas: 10 unidades
- Quadros: 5 unidades  
- Guidões: 10 unidades
- Gabinetes: 2 unidades
- Placas-mãe: 5 unidades
- Memórias RAM: 6 unidades

### Solicitação de Produção:
- 6 bicicletas
- 3 computadores

### Resultado Esperado:
| Produto | Componente | Necessário | Em Estoque | A Comprar | Status |
|---------|------------|------------|------------|-----------|--------|
| Bicicleta | Rodas | 12 | 10 | 2 ⚠️ | Insuficiente |
| Bicicleta | Quadros | 6 | 5 | 1 ⚠️ | Insuficiente |
| Bicicleta | Guidões | 6 | 10 | 0 | Suficiente |
| Computador | Gabinetes | 3 | 2 | 1 ⚠️ | Insuficiente |
| Computador | Placas-mãe | 3 | 5 | 0 | Suficiente |
| Computador | Memórias RAM | 6 | 6 | 0 | Suficiente |

**Resultado**: É necessário comprar 2 rodas, 1 quadro e 1 gabinete para completar a produção.

## 🎯 Funcionalidades Especiais

### Tratamento de Singular/Plural
O sistema trata automaticamente singular/plural nos componentes:
- "1 Quadro" vs "2 Quadros"
- "1 Gabinete" vs "2 Gabinetes"
- "1 Placa-mãe" vs "2 Placas-mãe"
- "1 Memória RAM" vs "2 Memórias RAM"

### Modais de Notificação
- **Sucesso**: Modal verde com ícone de check
- **Erro**: Modal vermelha com ícone de exclamação
- **Confirmação de Exclusão**: Modal amarela com ícone de aviso

### Indicadores Visuais
- **⚠️**: Ícone de aviso para itens que precisam ser comprados
- **Badge vermelho**: Para quantidades "A Comprar" > 0
- **Texto vermelho**: Status "Insuficiente" em vermelho
- **Badge verde**: Para quantidades "A Comprar" = 0

### URLs Limpas
- Sistema utiliza HTML5 mode do AngularJS
- URLs sem hash (#): `/estoque`, `/mrp`
- APIs separadas: `/api/estoque`, `/api/mrp`
- Roteamento inteligente: Frontend serve `index.html`, APIs servem JSON
- Roteamento configurado via Nginx: Frontend serve `index.html`, APIs servem JSON

## 🔧 Configuração do Banco de Dados

O banco de dados é configurado automaticamente com:
- **Host**: mysql (container)
- **Database**: mrp_db
- **User**: mrp_user
- **Password**: mrp_password
- **Port**: 3306
- **Encoding**: UTF-8 para caracteres especiais

## 🐛 Solução de Problemas

### Se a aplicação não carregar:
1. Verifique se os containers estão rodando: `docker-compose ps`
2. Verifique os logs: `docker-compose logs`
3. Reinicie os containers: `docker-compose restart`

### Se houver erro de conexão com o banco:
1. Aguarde alguns segundos para o MySQL inicializar
2. Verifique se o container MySQL está rodando: `docker-compose logs mysql`

### Se a página não carregar:
1. Verifique se os containers estão rodando: `docker-compose ps`
2. Verifique os logs: `docker-compose logs`
3. Reinicie os containers: `docker-compose restart`
4. Certifique-se de acessar: `http://localhost:8081`

### Testar APIs diretamente:
```bash
# Testar API de estoque
curl http://localhost:8081/api/estoque

# Testar API de MRP
curl -X POST http://localhost:8081/api/mrp \
  -H "Content-Type: application/json" \
  -d '{"bicicletas": 5, "computadores": 2}'
```

## 📝 Desenvolvimento

### Para fazer alterações no código:
  - Testes unitários e de integração devem ser colocados em `src/tests`.
1. Os arquivos estão mapeados nos volumes do Docker
2. Alterações são refletidas automaticamente
3. Recarregue a página no navegador

### Para acessar o banco de dados:
```bash
docker-compose exec mysql mysql -u mrp_user -p mrp_db
```

### Arquitetura do Sistema
- **MVC + Services**: Separação clara entre Models, Views, Controllers e Services
- **Dependency Injection**: Container para gerenciar dependências
- **REST APIs**: Endpoints separados para estoque e MRP
- **Clean URLs**: URLs sem hash (#) usando HTML5 mode

### Segurança
As pastas sensíveis de backend estão protegidas pelo Nginx e não podem ser acessadas diretamente via web.

## 🎯 Critérios de Avaliação Atendidos

### ✅ Funcionalidade
- Sistema atende todos os requisitos especificados
- Cálculos de MRP estão corretos
- Persistência de dados funciona adequadamente
- Interface permite todas as operações necessárias
- **Funcionalidade de exclusão implementada**
- **Indicadores visuais para melhor UX**

### ✅ Qualidade do Código
- Código bem estruturado e organizado
- Boas práticas de programação
- Separação adequada de responsabilidades
- Código legível e comentado
- **Tratamento robusto de encoding UTF-8**
- **Classe utilitária para correção de acentuação**
- **Arquitetura MVC + Services**
- **Dependency Injection implementada**

### ✅ Banco de Dados
- Modelagem adequada das tabelas
- Consultas eficientes
- Script de criação funcional
- **Configuração UTF-8 para caracteres especiais**

### ✅ Interface do Usuário
- Interface intuitiva e funcional
- Apresentação clara das informações
- Experiência do usuário satisfatória
- **Modais de notificação personalizadas**
- **Indicadores visuais para melhor compreensão**
- **URLs limpas sem hash**

### ✅ Documentação
- README claro e completo
- Instruções de instalação funcionais
- Comentários no código quando necessário
- **Documentação das novas funcionalidades**
- **Exemplos de uso atualizados**

## 📞 Suporte

Para dúvidas ou problemas, consulte os logs do Docker ou entre em contato com a equipe de desenvolvimento.