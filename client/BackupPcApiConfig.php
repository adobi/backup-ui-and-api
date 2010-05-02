<?php  

    class BackupPcApiConfig {
        
        private $config;
        
        public function __construct(array $config = array()) {
            
            $defaults = array(
                'api_url'               => 'http://127.0.0.1/webserver/ws_backup/api/api.php'
            );
            
            if(empty($config)) {
                
                $this->config = $defaults;
            }
            else {
                
                $this->config = array_merge($defaults, $config);
            }
        }
        
        public function get() {
            return $this->config;
        }
    }

?>