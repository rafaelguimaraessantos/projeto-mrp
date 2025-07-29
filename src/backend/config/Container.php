<?php

/**
 * Container de Dependências
 * Gerencia a injeção de dependências da aplicação
 */
class Container {
    private static $instances = [];
    
    /**
     * Registrar uma instância no container
     */
    public static function register($name, $instance) {
        self::$instances[$name] = $instance;
    }
    
    /**
     * Obter uma instância do container
     */
    public static function get($name) {
        if (!isset(self::$instances[$name])) {
            throw new Exception("Serviço '$name' não encontrado no container");
        }
        
        return self::$instances[$name];
    }
    
    /**
     * Verificar se um serviço existe no container
     */
    public static function has($name) {
        return isset(self::$instances[$name]);
    }
    
    /**
     * Configurar serviços padrão da aplicação
     */
    public static function configure() {
        // Registrar serviços
        require_once __DIR__ . '/../services/EstoqueService.php';
        require_once __DIR__ . '/../services/MRPService.php';
        
        self::register('estoqueService', new EstoqueService());
        self::register('mrpService', new MRPService());
    }
    
    /**
     * Obter EstoqueService
     */
    public static function getEstoqueService() {
        return self::get('estoqueService');
    }
    
    /**
     * Obter MRPService
     */
    public static function getMRPService() {
        return self::get('mrpService');
    }
}
?> 