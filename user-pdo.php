<?php             

    $pdo = new PDO("mysql:host=localhost;dbname=classes", 'root', '');

        class User {
            private $id = 0;
            public $login = "";
            public $password = "";
            public $email = "";
            public $firstname = "";
            public $lastname = "";

            

            public function __construct(){
                $this->login = "";
                $this->password = "";
                $this->email = "";
                $this->firstname = "";
                $this->lastname = "";
            }

            public function register($log, $pass, $mail, $prenom, $nom){
                global $pdo;

                $sql = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (:login, :password, :email, :firstname, :lastname)";
                $envoi = $pdo->prepare($sql);
                $exec = $envoi->execute(array(":login"=>$log, ":password"=>$pass, ":email"=>$mail, ":firstname"=>$prenom, ":lastname"=>$nom));
                if($exec){
                    echo 'Données insérées';
                }
                else{
                    echo "Échec de l'opération d'insertion";
                }
                return array($log, $pass, $mail, $prenom, $nom);
            }
            
            public function connect($log, $pass){
                $this->login = $log;
                $this->password = $pass;

                global $pdo;
                $sql = "SELECT login, password, email, firstname, lastname FROM utilisateurs";
                $envoi = $pdo->prepare($sql);
                $envoi->execute();
                $result = $envoi->fetchAll();

                foreach($result as $row) {
                    if($row["login"] == $log && $row["password"] == $pass){
                    $this->email = $row["email"];
                    $this->firstname = $row["firstname"];
                    $this->lastname = $row["lastname"];
                    //echo "mail " . $email . " prenom " . $firstname . " nom " . $lastname;
                    }
                  }

                  $_SESSION['user'] = $this->getFirstname();
                
            }

            public function disconnect(){
                $this->login = "";
                $this->password = "";
                $this->email = "";
                $this->firstname = "";
                $this->lastname = "";

                unset($_SESSION['user']);
                session_destroy();
                
            }

            public function delete(){
                global $pdo;
                $sql = "DELETE FROM utilisateurs WHERE login = :login";

                $envoi = $pdo->prepare($sql);
                $envoi->execute(array(":login" => $this->login));

                $this->login = "";
                $this->password = "";
                $this->email = "";
                $this->firstname = "";
                $this->lastname = "";
                unset($_SESSION['user']);
                session_destroy();
            }

            public function update($login, $password, $email, $firstname, $lastname){
                global $pdo;
                $sql = "UPDATE utilisateurs 
                SET login = '$login', password = '$password', email = '$email', firstname = '$firstname', lastname = '$lastname'
                WHERE login = '$this->login'";

                $envoi = $pdo->prepare($sql);

                $envoi->execute();
            }

            public function isConnected(){
                if($_SESSION['user'] == $this->firstname){
                    return true;
                }
                else{
                    return false;
                }
            }

            public function getAllInfos(){
                
                return array($this->login, $this->password, $this->email, $this->firstname, $this->lastname);
            }

            public function getLogin(){
                return $this->login;
            }

            public function getEmail(){
                return $this->email;
            }

            public function getFirstname(){
                return $this->firstname;
            }

            public function getLastname(){
                return $this->lastname;
            }
            
        }

        $test = new User();

        //$test->register('nerok', '1234', 'mail@gmail.com', 'nicolas', 'panis');

        $test->connect('nerok', '1234');
        echo "Bonjour " . $_SESSION['user'];
        //echo $test->isConnected();
        $test->disconnect();

        $test->connect('test', '1234');
        //$test->delete();

        $infos = $test->getAllInfos();
        /*for($i = 0; isset($infos[$i]); $i++){
            echo $infos[$i];
            echo " ";
        }*/

        //$test->update('test', '1234', 'testmodif@gmail.com', 'test2', 'test1');
        //$infos = $test->getAllInfos();
        /*for($i = 0; isset($infos[$i]); $i++){
            echo $infos[$i];
        }*/

    ?>