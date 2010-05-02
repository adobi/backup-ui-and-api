<?php  

    class BackupPcApi {
        
        private $config;
        
        public function __construct($config) {
            
            $this->config = $config->get();
        }
        
        public function get($params) {
            
            $uri = array();
            foreach($params as $name=>$value) {
                $uri[] = $name.'='.urlencode($value);
            }
            $uri = join('&', $uri);
            
            //echo $this->config['api_url'] . '?' . $uri;
            return 
                json_decode(
                    file_get_contents(
                        $this->config['api_url'] . '?' . $uri
                    )
                );
        }
    }


?>