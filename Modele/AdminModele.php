<?php


namespace modele;


use utilitaires\PersistanceBD;
use utilitaires\Boniche;
use metier\Admin;

class AdminModele {
    public function __construct(){
    }

    public function isAdmin(){
       
        // Supression des warnings
        if(isset($_SESSION['role'])){
            $role = $_SESSION['role'];
        }
        else return null;
            
        if(isset($_SESSION['login'])){
            $login = $_SESSION['login'];
        }
        else return null;
            
        
        $login = Boniche::NettoyageLOGIN($login);
                
        if(isset($role)&&isset($login)&&$role=='admin'){
            return new Admin($role, $login);
        }
        else {return null;}
    }
    
    public function connecter($login, $mdp){    
        $login = Boniche::NettoyageLOGIN($login);
        $mdp = Boniche::NettoyageLOGIN($mdp);
        $mdp = md5($mdp);
        
        $dal = new PersistanceBD();
        $result = $dal->authentifier($login, $mdp);
        
        if($result == 1){
            $_SESSION['role']='admin';
            $_SESSION['login']=$login;
            return true;
        }
        return false;
    }
    
    public function deconnecter(){
        unset($_SESSION);
        session_destroy();
    }
} 