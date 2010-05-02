<?php  

    class BackupPcApiConfig {
        
        private $config;
        
        public function __construct(array $config = array()) {
            
            $defaults = array(
                'api_url'               => 'http://localhost/webserver/ws_backup/api/api.php',
                'api_servers_list'      => '?request=servers-list',
                'api_revisions_list'    => '?request=revisions-list'   
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