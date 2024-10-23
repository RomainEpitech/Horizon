<?php

    namespace Horizon\Core\Env;

    use Exception;

    class EnvLoader 
    {
        private array $loadedVariables = [];
        private string $envPath;
        
        public function __construct(string $envPath = null) 
        {
            $this->envPath = $envPath ?? './.env';
        }
        
        /**
         * @throws Exception
         * @return bool
         */
        public function load(): bool 
        {
            if (!file_exists($this->envPath)) {
                throw new Exception("Le fichier .env n'existe pas: {$this->envPath}");
            }

            $lines = file($this->envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($lines as $lineNumber => $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                
                if (strpos($line, '=') !== false) {
                    list($name, $value) = array_map('trim', explode('=', $line, 2));
                    
                    if (isset($this->loadedVariables[$name])) {
                        throw new Exception(
                            sprintf(
                                "Variable d'environnement dupliquée '%s' détectée à la ligne %d. Précédemment définie à la ligne %d.",
                                $name,
                                $lineNumber + 1,
                                $this->loadedVariables[$name]['line']
                            )
                        );
                    }

                    $value = $this->cleanQuotes($value);
                    
                    $this->loadedVariables[$name] = [
                        'value' => $value,
                        'line' => $lineNumber + 1
                    ];
                    
                    $_ENV[$name] = $value;
                }
            }
            
            return true;
        }
        
        private function cleanQuotes(string $value): string 
        {
            if (
                (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
                (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)
            ) {
                return substr($value, 1, -1);
            }
            
            return $value;
        }

        public function getLoadedVariables(): array 
        {
            return array_map(function($item) {
                return $item['value'];
            }, $this->loadedVariables);
        }
    }