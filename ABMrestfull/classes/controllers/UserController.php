<?php
class UserController extends AbstractController

// tenemos array de la ruta long 2,  si longitud ==2
// coger el segundo valor del arary, y mirar si hay funcion con ese nombre, si hay lo llamas y sino error
{
    protected $userDB;
    public function __construct() {
        
        $this->userDB = userSPDO::singleton();
        $this->userDB->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        $this->userDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function usuaris($request) {
        if (strtolower($request->method) == 'get' && count($request->url_elements) == 1) {
            
            try {
                //Podria retornar totes les dades, però per raons de "seguretat", no retorno la contrassenya
                $stmt = $this->userDB->prepare("SELECT nom,cognoms,user,id_usuari FROM restful");
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;


                        
            }
            catch(PDOException $e) {
                return "Error1: " . $e->getMessage();
            }
        } else if ( count($request->url_elements) > 1) {
         $funcion = ( $request->url_elements);
           /*$funcion =array_pop ( $request->url_elements);*/
           $patata = $this->$funcion[1]($request);
                       
            return $patata /*"Error2: " . $request->method . " no permés"*/;
        }  
        else {
            return "Error3";
        }
    }

    //independiente
    public function crearUsuari($request) {
        if (strtolower($request->method) == 'post' && count($request->url_elements) == 2) {
            
            $stmt = $this->userDB->prepare("INSERT INTO restful (nom,cognoms,user,pass) VALUES (?,?,?,?)");
            
            $stmt->bindParam(1, $request->parameters['nom']);
            $stmt->bindParam(2, $request->parameters['cognoms']);
            $stmt->bindParam(3, $request->parameters['user']);
            $stmt->bindParam(4, $request->parameters['pass']);
            
            if ($stmt->execute()) {
                return "Usuari creat satisfacròriament";
            } else {
                return "Error al crear l'usuari";
            }
        } else if (strtolower($request->method) != 'post') {
            return "Error: " . $request->method . " no permés";
        } else {
            
            return "Error: No s'ha dintroduïr cap parametre a través de la URL";
        }
    }
    public function login($request) {
        if (strtolower($request->method) == 'post' && count($request->url_elements) == 2) {
            
            $stmt = $this->userDB->prepare("SELECT nom FROM restful WHERE nom=? AND pass=?");
            
            $stmt->bindParam(1, $request->parameters['nom']);
            $stmt->bindParam(2, $request->parameters['pass']);
            
            $stmt->execute();
            
            if ($result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0)) {
                
                return "Benvingut " . $result[0];
            } else {
                return "Usuari o contrassenya incorrectes";
            }
        } else if (strtolower($request->method) != 'post') {
            return "Error: " . $request->method . " no permés";
        } else {
            
            return "Error: No s'ha dintroduïr cap parametre a través de la URL";
        }
    }
    public function actualitzarNom($request) {
        if (strtolower($request->method) == 'put' && count($request->url_elements) == 3) {
            
            try {
                
                $stmt = $this->userDB->prepare("UPDATE restful SET nom=? WHERE id_usuari=?");
                
                $stmt->bindParam(1, $request->parameters['nom']);
                $stmt->bindParam(2, $request->url_elements[2]);
                $stmt->execute();
                if ($count = $stmt->rowCount() == 1) {
                    return "Usuari actualitzat satisfacròriament";
                } else {
                    return "Error a l'actualitzar l'usuari";
                }
            }
            
            catch(PDOException $e) {
                return "Error: " . $e->getMessage();
            }
        } else if (strtolower($request->method) != 'put') {
            return "Error: " . $request->method . " no permés";
        } else {
            
            return "Error: Només has d'introduïr l'id de l\'usuari ";
        }
    }

    //usuaris/crearUsuari

    public function esborrarUsuari($request) {
        if (strtolower($request->method) == 'delete' && count($request->url_elements) == 3) {
            
            try {
                
                $stmt = $this->userDB->prepare("DELETE FROM restful WHERE id_usuari=?");
                
                $stmt->bindParam(1, $request->url_elements[2]);
                
                $stmt->execute();
                if ($count = $stmt->rowCount() == 1) {
                    return "Usuari esborrat satisfacròriament";
                } else {
                    return "Error a l'esborrar l'usuari";
                }
            }
            
            catch(PDOException $e) {
                return "Error: " . $e->getMessage();
            }
        } else if (strtolower($request->method) != 'delete') {
            return "Error: " . $request->method . " no permés";
        } else {
            
            return "Error: Només has d'introduïr l'id de l\'usuari ";
        }
    }
}
