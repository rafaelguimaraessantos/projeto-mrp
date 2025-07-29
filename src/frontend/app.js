var app = angular.module('mrpApp', ['ngRoute']);

// Configurar HTML5 mode
app.config(['$locationProvider', function($locationProvider) {
    $locationProvider.html5Mode(true);
    $locationProvider.hashPrefix('');
}]);

app.controller('MainController', function($scope, $http, $location) {
    // Variáveis de estado
    $scope.activeTab = 'estoque';
    $scope.loading = false;
    $scope.calculando = false;
    $scope.estoque = [];
    $scope.resultadoMRP = [];
    $scope.mrpData = {
        bicicletas: 0,
        computadores: 0
    };
    $scope.editando = {};
    $scope.resumoCompras = '';

    // Inicializar aplicação
    $scope.init = function() {
        $scope.carregarEstoque();
    };

    // Definir aba ativa
    $scope.setActiveTab = function(tab) {
        $scope.activeTab = tab;
        
        // Atualizar URL usando $location
        if (tab === 'estoque') {
            $location.path('/estoque');
            $scope.carregarEstoque();
        } else if (tab === 'mrp') {
            $location.path('/mrp');
        }
    };

    // Carregar estoque
    $scope.carregarEstoque = function() {
        $scope.loading = true;
        
        $http.get('/api/estoque')
            .then(function(response) {
                if (response.data.status === 'success') {
                    $scope.estoque = response.data.data;                    
                } else {
                    console.error('Erro ao carregar estoque:', response.data.message);
                }
            })
            .catch(function(error) {
                console.error('Erro na requisição:', error);
            })
            .finally(function() {
                $scope.loading = false;
            });
    };

    // Editar estoque
    $scope.editEstoque = function(item) {

        // Garantir que todos os campos existam
        $scope.editando = {
            componente_id: item.componente_id || 0,
            componente: item.componente || 'Componente não encontrado',
            quantidade: item.quantidade || 0
        };
        
        // Abrir modal
        var modal = new bootstrap.Modal(document.getElementById('editEstoqueModal'));
        modal.show();
        
        // Atualizar diretamente o conteúdo do modal
        setTimeout(function() {
            var componenteDisplay = document.getElementById('componente-display');
            var componenteId = document.getElementById('componente-id');
            var quantidadeInput = document.getElementById('quantidade-input');
            var btnSalvar = document.getElementById('btn-salvar');
            
            if (componenteDisplay) {
                componenteDisplay.textContent = $scope.editando.componente;
            }
            if (componenteId) {
                componenteId.textContent = $scope.editando.componente_id;
            }
            if (quantidadeInput) {
                quantidadeInput.value = $scope.editando.quantidade;
            }
            
            // Adicionar event listener ao botão salvar
            if (btnSalvar) {
                // Remover event listeners anteriores
                btnSalvar.onclick = null;
                
                // Adicionar novo event listener
                btnSalvar.onclick = function() {
                    $scope.salvarEstoque();
                };
            }
        }, 100);
    };

    // Salvar estoque
    $scope.salvarEstoque = function() {
        
        // Pegar o valor do input diretamente
        var quantidadeInput = document.getElementById('quantidade-input');
        var novaQuantidade = quantidadeInput ? parseInt(quantidadeInput.value) : $scope.editando.quantidade;

        $http.post('/api/estoque', {
            componente_id: $scope.editando.componente_id,
            quantidade: novaQuantidade
        })
        .then(function(response) {
            
            if (response.data.status === 'success') {
                // Fechar modal de edição
                var modal = bootstrap.Modal.getInstance(document.getElementById('editEstoqueModal'));
                modal.hide();
                
                // Recarregar estoque
                $scope.carregarEstoque();
                
                // Mostrar modal de sucesso
                setTimeout(function() {
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                }, 300);
            } else {
                // Mostrar modal de erro
                document.getElementById('error-message').textContent = 'Erro ao atualizar estoque: ' + response.data.message;
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            }
        })
        .catch(function(error) {
            console.error('Erro na requisição:', error);
            // Mostrar modal de erro
            document.getElementById('error-message').textContent = 'Erro ao atualizar estoque. Verifique o console para mais detalhes.';
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        });
    };

    // Calcular MRP
    $scope.calcularMRP = function() {
        if (!$scope.mrpData.bicicletas && !$scope.mrpData.computadores) {
            document.getElementById('error-message').textContent = 'Por favor, insira pelo menos uma quantidade para calcular o MRP.';
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
            return;
        }

        $scope.calculando = true;
        $scope.resultadoMRP = [];
        $scope.resumoCompras = '';

        $http.post('/api/mrp', {
            bicicletas: parseInt($scope.mrpData.bicicletas) || 0,
            computadores: parseInt($scope.mrpData.computadores) || 0
        })
        .then(function(response) {
            if (response.data.status === 'success') {
                $scope.resultadoMRP = response.data.data;
                $scope.gerarResumoCompras();
            } else {
                document.getElementById('error-message').textContent = 'Erro ao calcular MRP: ' + response.data.message;
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            }
        })
        .catch(function(error) {
            console.error('Erro na requisição:', error);
            document.getElementById('error-message').textContent = 'Erro ao calcular MRP. Verifique o console para mais detalhes.';
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        })
        .finally(function() {
            $scope.calculando = false;
        });
    };

    // Função para tratar singular/plural dos componentes
    $scope.formatarComponente = function(quantidade, componente) {
        // Mapeamento de componentes para singular
        var singularMap = {
            'Quadros': 'Quadro',
            'Rodas': 'Roda',
            'Guidões': 'Guidão',
            'Gabinetes': 'Gabinete',
            'Placas-mãe': 'Placa-mãe',
            'Memórias RAM': 'Memória RAM'
        };
        
        // Se quantidade é 1, usar singular
        if (Number(quantidade) === 1) {
            return singularMap[componente] || componente;
        }
        
        // Se quantidade > 1, usar plural (nome original)
        return componente;
    };

    // Gerar resumo de compras
    $scope.gerarResumoCompras = function() {
        var compras = [];
        
        $scope.resultadoMRP.forEach(function(item) {
            if (item.a_comprar > 0) {
                var componenteFormatado = $scope.formatarComponente(item.a_comprar, item.componente);
                compras.push(item.a_comprar + ' ' + componenteFormatado);
            }
        });
        
        if (compras.length > 0) {
            $scope.resumoCompras = 'É necessário comprar: ' + compras.join(', ') + ' para completar a produção.';
        } else {
            $scope.resumoCompras = 'Todos os componentes estão disponíveis em estoque suficiente!';
        }
    };

    // Abrir modal de cadastro
    $scope.abrirModalCadastro = function() {
        // Limpar formulário
        document.getElementById('componente-select').value = '';
        document.getElementById('quantidade-cadastro').value = '';
        
        // Abrir modal
        var modal = new bootstrap.Modal(document.getElementById('cadastrarEstoqueModal'));
        modal.show();
        
        // Adicionar event listener ao botão cadastrar
        setTimeout(function() {
            var btnCadastrar = document.getElementById('btn-cadastrar');
            if (btnCadastrar) {
                btnCadastrar.onclick = function() {
                    $scope.cadastrarEstoque();
                };
            }
        }, 100);
    };

    // Cadastrar novo item no estoque
    $scope.cadastrarEstoque = function() {
        var componenteId = document.getElementById('componente-select').value;
        var quantidade = document.getElementById('quantidade-cadastro').value;
        
        if (!componenteId || !quantidade) {
            document.getElementById('error-message').textContent = 'Por favor, preencha todos os campos.';
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
            return;
        }
        
        console.log('Cadastrando estoque:', { componente_id: componenteId, quantidade: quantidade });
        
        $http.post('/api/estoque', {
            action: 'create',
            componente_id: parseInt(componenteId),
            quantidade: parseInt(quantidade)
        })
        .then(function(response) {
            console.log('Resposta da API:', response.data);
            if (response.data.status === 'success') {
                // Fechar modal de cadastro
                var modal = bootstrap.Modal.getInstance(document.getElementById('cadastrarEstoqueModal'));
                modal.hide();
                
                // Recarregar estoque
                $scope.carregarEstoque();
                
                // Mostrar modal de sucesso
                setTimeout(function() {
                    document.getElementById('success-message').textContent = 'Item cadastrado com sucesso no estoque!';
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                }, 300);
            } else {
                document.getElementById('error-message').textContent = 'Erro ao cadastrar estoque: ' + response.data.message;
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            }
        })
        .catch(function(error) {
            console.error('Erro na requisição:', error);
            document.getElementById('error-message').textContent = 'Erro ao cadastrar estoque. Verifique o console para mais detalhes.';
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        });
    };

    // Excluir item do estoque
    $scope.excluirEstoque = function(item) {
        // Armazenar o item para usar na confirmação
        $scope.itemParaExcluir = item;
        
        // Atualizar mensagem da modal
        document.getElementById('confirm-delete-message').textContent = 
            'Tem certeza que deseja excluir o item "' + item.componente + '" do estoque?';
        
        // Mostrar modal de confirmação
        var confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        confirmModal.show();
        
        // Adicionar event listener ao botão de confirmação
        setTimeout(function() {
            var btnConfirmDelete = document.getElementById('btn-confirm-delete');
            if (btnConfirmDelete) {
                btnConfirmDelete.onclick = function() {
                    confirmModal.hide();
                    $scope.executarExclusao();
                };
            }
        }, 100);
    };

    // Executar a exclusão após confirmação
    $scope.executarExclusao = function() {
        if (!$scope.itemParaExcluir) {
            return;
        }
        
        console.log('Excluindo item:', $scope.itemParaExcluir);
        
        $http.post('/api/estoque', {
            action: 'delete',
            componente_id: $scope.itemParaExcluir.componente_id
        })
        .then(function(response) {
            console.log('Resposta da API:', response.data);
            if (response.data.status === 'success') {
                // Recarregar estoque
                $scope.carregarEstoque();
                
                // Mostrar modal de sucesso
                setTimeout(function() {
                    document.getElementById('success-message').textContent = response.data.message;
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                }, 300);
            } else {
                document.getElementById('error-message').textContent = 'Erro ao excluir item: ' + response.data.message;
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            }
        })
        .catch(function(error) {
            console.error('Erro na requisição:', error);
            document.getElementById('error-message').textContent = 'Erro ao excluir item do estoque. Verifique o console para mais detalhes.';
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        });
    };

    // Inicializar quando a página carregar
    $scope.init();
    
    // Verificar URL inicial
    var path = $location.path();
    if (path === '/estoque') {
        $scope.activeTab = 'estoque';
        $scope.carregarEstoque();
    } else if (path === '/mrp') {
        $scope.activeTab = 'mrp';
    }
}); 