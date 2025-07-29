<?php

/**
 * Classe utilitária para tratamento de encoding e acentuação
 * Resolve problemas como "GuidÃµes" -> "Guidões"
 */
class EncodingUtils {
    
    /**
     * Converte string para UTF-8 se necessário
     * @param string $string String para converter
     * @return string String em UTF-8
     */
    public static function ensureUTF8($string) {
        if (empty($string)) {
            return $string;
        }
        
        // Detecta se já está em UTF-8
        if (mb_check_encoding($string, 'UTF-8')) {
            return $string;
        }
        
        // Tenta detectar o encoding atual
        $encoding = mb_detect_encoding($string, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        
        if ($encoding) {
            return mb_convert_encoding($string, 'UTF-8', $encoding);
        }
        
        // Fallback: assume ISO-8859-1 (Latin-1)
        return mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');
    }
    
    /**
     * Limpa e normaliza strings com acentuação
     * @param string $string String para normalizar
     * @return string String normalizada
     */
    public static function normalizeString($string) {
        $string = self::ensureUTF8($string);
        
        // Remove caracteres de controle
        $string = preg_replace('/[\x00-\x1F\x7F]/', '', $string);
        
        // Normaliza espaços
        $string = preg_replace('/\s+/', ' ', $string);
        
        return trim($string);
    }
    
    /**
     * Converte array de dados para UTF-8
     * @param array $data Array de dados
     * @return array Array com encoding UTF-8
     */
    public static function convertArrayToUTF8($data) {
        if (!is_array($data)) {
            return self::ensureUTF8($data);
        }
        
        $result = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $result[$key] = self::convertArrayToUTF8($value);
            } else {
                $result[$key] = self::ensureUTF8($value);
            }
        }
        
        return $result;
    }
    
    /**
     * Verifica se uma string tem problemas de encoding
     * @param string $string String para verificar
     * @return bool True se tem problemas
     */
    public static function hasEncodingIssues($string) {
        return !mb_check_encoding($string, 'UTF-8') || 
               preg_match('/[\x80-\xFF].*[\x80-\xFF]/', $string);
    }
    
    /**
     * Corrige nomes de componentes comuns
     * @param string $componente Nome do componente
     * @return string Nome corrigido
     */
    public static function fixComponentName($componente) {
        $correcoes = [
            'GuidÃµes' => 'Guidões',
            'Placas-mÃ£e' => 'Placas-mãe',
            'MemÃ³rias RAM' => 'Memórias RAM',
            'Gabinetes' => 'Gabinetes',
            'Rodas' => 'Rodas',
            'Quadros' => 'Quadros'
        ];
        
        $componente = self::ensureUTF8($componente);
        
        return $correcoes[$componente] ?? $componente;
    }
}
?> 