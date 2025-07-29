# 🚀 Instruções Rápidas - Sistema MRP

## ⚡ Execução Rápida

### 1. Iniciar o Sistema
```bash
docker-compose up -d
```

### 2. Acessar a Aplicação
Abra seu navegador e acesse: **http://localhost:8081**

### 3. Testar o Sistema
1. **Aba Estoque**: Visualize e edite o estoque atual
2. **Aba MRP**: Digite quantidades e calcule necessidades

## 📊 Exemplo de Teste

### Dados de Entrada:
- **Estoque Inicial**:
  - Rodas: 10 unidades
  - Quadros: 5 unidades
  - Guidões: 10 unidades
  - Gabinetes: 2 unidades
  - Placas-mãe: 5 unidades
  - Memórias RAM: 6 unidades

- **Produção Solicitada**:
  - 6 bicicletas
  - 3 computadores

### Resultado Esperado:
| Produto | Componente | Necessário | Em Estoque | A Comprar | Status |
|---------|------------|------------|------------|-----------|--------|
| Bicicleta | Rodas | 12 | 10 | 2 | ⚠️ |
| Bicicleta | Quadros | 6 | 5 | 1 | ⚠️ |
| Bicicleta | Guidões | 6 | 10 | 0 | ✅ |
| Computador | Gabinetes | 3 | 2 | 1 | ⚠️ |
| Computador | Placas-mãe | 3 | 5 | 0 | ✅ |
| Computador | Memórias RAM | 6 | 6 | 0 | ✅ |

**Resumo**: É necessário comprar 2 rodas, 1 quadro e 1 gabinete.

## 🛠️ Comandos Úteis

### Verificar Status dos Containers
```bash
docker-compose ps
```

### Ver Logs
```bash
docker-compose logs
```

### Parar o Sistema
```bash
docker-compose down
```

### Reiniciar
```bash
docker-compose restart
```

## 🐛 Solução de Problemas

### Se a página não carregar:
1. Aguarde 30 segundos para o MySQL inicializar
2. Verifique: `docker-compose ps`
3. Recarregue a página

### Se houver erro de conexão:
1. Verifique se a porta 8081 está livre
2. Execute: `docker-compose down && docker-compose up -d`
3. Certifique-se de acessar: `http://localhost:8081`

## 📝 Funcionalidades

### ✅ Gerenciamento de Estoque
- Visualizar estoque atual
- Editar quantidades
- Persistência automática

### ✅ Cálculo MRP
- Interface intuitiva
- Cálculos automáticos
- Resumo de compras necessárias
- Status visual (✅/⚠️)

## 🎯 Tecnologias
- **Backend**: PHP 8.1
- **Frontend**: AngularJS + Bootstrap
- **Banco**: MySQL 8.0
- **Containerização**: Docker

---

**Sistema pronto para uso! 🎉** 