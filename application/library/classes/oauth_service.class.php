<?php
    abstract class OauthService 
    {
        protected $client_id;
        protected $client_secret;
        protected $redirect_uri;
        
        public function __construct($client_id, $client_secret, $redirect_uri)
        {
            $this->client_id = $client_id;            
            $this->client_secret = $client_secret;
            $this->redirect_uri = $redirect_uri;
        }
        
        abstract public function getToken($code);
        
        abstract public function getUserData($token);
    }