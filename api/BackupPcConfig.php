<?php  

    class BackupPcConfig {
        
        private $config;
        
        public function __construct(array $config = array()) {
            
            $defaults = array(
                'path_to_tarhely_backups' => '../tests/var/lib/backuppc/pc/',
                'path_to_mysql_backups'   => '../tests/var/lib/backuppc/sqlbackup/',
                'backups_file_name'       => 'backups'    
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