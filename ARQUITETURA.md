# 📋 Arquitetura do Sistema MRP

## 🏗️ Padrão MVC (Model-View-Controller)

### ✅ **Estrutura Atual - CORRETA**

```
src/
├── frontend/           # VIEW (AngularJS)
│   └── app.js         # Controlador Angular
├── backend/
│   ├── models/        # MODEL (Acesso a Dados)
│   │   ├── Estoque.php
│   │   └── MRP.php
│   ├── services/      # SERVICES (Lógica de Negócio)
│   │   ├── EstoqueService.php
│   │   └── MRPService.php
│   ├── controllers/   # CONTROLLER (Controle de Requisições)
│   │   ├── EstoqueController.php
│   │   └── MRPController.php
│   ├── api/          # Endpoints REST
│   │   ├── estoque.php
│   │   └── mrp.php
│   ├── config/       # Configurações
│   │   ├── database.php
│   │   └── Container.php
│   └── utils/        # Utilitários
│       └── EncodingUtils.php
└── index.html        # VIEW (Interface Principal)
```

## 🎯 **Separação de Responsabilidades**

### **1. MODEL (src/backend/models/)**
**Responsabilidade**: Acesso a dados e persistência

#### ✅ **Estoque.php**
```php
class Estoque {
    private $conn;           // Encapsulamento
    private $table_name;     // Propriedades privadas
    
    public function getAll()     // Métodos públicos
    public function update()     // CRUD operations
    public function create()     // Business logic
    public function getByComponente()
}
```

#### ✅ **MRP.php**
```php
class MRP {
    private $estoque;        // Dependência injetada
    
    public function calcularNecessidades()  // Business logic
    public function getProdutos()          // Data access
    public function getComponentes()       // Data access
}
```

### **2. SERVICES (src/backend/services/)**
**Responsabilidade**: Lógica de negócio e regras da aplicação

#### ✅ **EstoqueService.php**
```php
class EstoqueService {
    private $estoque;        // Dependência do Model
    
    public function listarEstoque()     // Business logic
    public function atualizarEstoque()  // Business logic
    public function cadastrarEstoque()  // Business logic
    public function verificarDisponibilidade() // Business rules
}
```

#### ✅ **MRPService.php**
```php
class MRPService {
    private $mrp;            // Dependência do Model
    private $estoqueService; // Dependência do Service
    
    public function calcularNecessidades()  // Business logic
    public function gerarRelatorio()       // Business logic
    public function validarQuantidades()   // Business rules
}
```

### **3. CONTROLLER (src/backend/controllers/)**
**Responsabilidade**: Processar requisições HTTP e coordenar Services

#### ✅ **EstoqueController.php**
```php
class EstoqueController {
    private $estoqueService;    // Dependência do Service
    
    public function index()      // GET - Listar
    public function update()     // POST - Atualizar
    public function create()     // POST - Criar
    public function handleRequest() // Roteamento
}
```

#### ✅ **MRPController.php**
```php
class MRPController {
    private $mrpService;        // Dependência do Service
    
    public function calcular()     // POST - Calcular MRP
    public function handleRequest() // Roteamento
}
```

### **4. VIEW (src/frontend/ + index.html)**
**Responsabilidade**: Interface do usuário

#### ✅ **AngularJS (app.js)**
```javascript
app.controller('MRPController', function($scope, $http) {
    // Controle da interface
    $scope.carregarEstoque()    // Consome API
    $scope.editEstoque()        // Interação UI
    $scope.calcularMRP()        // Processamento
});
```

#### ✅ **HTML (index.html)**
```html
<!-- Interface responsiva -->
<div ng-controller="MRPController">
    <!-- Tabelas, formulários, modais -->
</div>
```

## 🔧 **Padrões OO Implementados**

### ✅ **1. Injeção de Dependência**
```php
class EstoqueController {
    private $estoqueService;
    
    public function __construct() {
        $this->estoqueService = Container::getEstoqueService();
    }
}
```

### ✅ **2. Encapsulamento**
```php
class EstoqueService {
    private $estoque;        // Propriedade privada
    
    public function listarEstoque() {  // Interface pública
        // Lógica de negócio
    }
}
```

### ✅ **2. Herança (se necessário)**
```php
// Base class para Models
abstract class BaseModel {
    protected $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
}

class Estoque extends BaseModel {
    // Herda $conn e métodos base
}
```

### ✅ **3. Polimorfismo**
```php
// Interface comum para Controllers
interface ControllerInterface {
    public function handleRequest($method, $data);
}

class EstoqueController implements ControllerInterface {
    public function handleRequest($method, $data) {
        // Implementação específica
    }
}
```

### ✅ **3. Responsabilidade Única**
```php
class EstoqueService {
    // Responsável apenas pela lógica de negócio do estoque
}

class MRPService {
    // Responsável apenas pela lógica de negócio do MRP
}
```

## 🚀 **Fluxo de Requisição**

### **1. Frontend (AngularJS)**
```javascript
$http.post('backend/api/estoque.php', data)
    .then(function(response) {
        // Processa resposta
    });
```

### **2. API Endpoint**
```php
// src/backend/api/estoque.php
$controller = new EstoqueController();
$response = $controller->handleRequest($method, $data);
echo json_encode($response);
```

### **3. Controller**
```php
// src/backend/controllers/EstoqueController.php
public function handleRequest($method, $data) {
    switch ($method) {
        case 'GET': return $this->index();
        case 'POST': return $this->create($data);
    }
}
```

### **4. Service**
```php
// src/backend/services/EstoqueService.php
public function cadastrarEstoque($componente_id, $quantidade) {
    // Validações de negócio
    // Lógica de negócio
    // Chama Model para persistir
}
```

### **5. Model**
```php
// src/backend/models/Estoque.php
public function create($componente_id, $quantidade) {
    // Acesso ao banco de dados
    return $success;
}
```

## 📊 **Benefícios da Arquitetura**

### ✅ **1. Separação de Responsabilidades**
- **Models**: Acesso a dados e persistência
- **Services**: Lógica de negócio e regras
- **Controllers**: Controle de requisições HTTP
- **Views**: Interface do usuário

### ✅ **2. Manutenibilidade**
- Código organizado e modular
- Fácil de testar e debugar
- Baixo acoplamento

### ✅ **3. Escalabilidade**
- Fácil adicionar novos recursos
- Reutilização de código
- Padrões consistentes

### ✅ **4. Testabilidade**
- Services podem ser testados isoladamente
- Controllers podem ser mockados
- Models focados apenas em dados
- APIs bem definidas

## 🎯 **Conclusão**

**A arquitetura está CORRETA e segue os padrões MVC, Services e OO:**

- ✅ **MVC + Services bem implementado**
- ✅ **Injeção de Dependência aplicada**
- ✅ **Orientação a Objetos correta**
- ✅ **Separação clara de responsabilidades**
- ✅ **Código limpo e organizado**
- ✅ **Padrões de projeto aplicados**

O sistema está bem estruturado e pronto para evolução! 🚀 