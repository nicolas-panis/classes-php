<?php 

        $mysqli = new mysqli("localhost", "root", "", "classes");

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
                global $mysqli;
                $sql = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES ('$log', '$pass', '$mail', '$prenom', '$nom')";
                if (mysqli_query($mysqli, $sql)) {
                    echo "Nouveau enregistrement créé avec succès";
                } 
                else {
                    echo "Erreur : " . $sql . "<br>" . mysqli_error($mysqli);
                }
                mysqli_close($mysqli);

                return array($log, $pass, $mail, $prenom, $nom);
            }
            
            public function connect($log, $pass){
                $this->login = $log;
                $this->password = $pass;

                global $mysqli;
                $sql = "SELECT login, password, email, firstname, lastname FROM utilisateurs";
                
                $result = $mysqli->query($sql);
                while($row = $result->fetch_assoc()) {
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
                global $mysqli;
                $sql = "DELETE FROM utilisateurs WHERE login = '$this->login'";

                $mysqli->query($sql);

                $this->login = "";
                $this->password = "";
                $this->email = "";
                $this->firstname = "";
                $this->lastname = "";
                unset($_SESSION['user']);
                session_destroy();
            }

            public function update($login, $password, $email, $firstname, $lastname){
                global $mysqli;
                $sql = "UPDATE utilisateurs 
                SET login = '$login', password = '$password', email = '$email', firstname = '$firstname', lastname = '$lastname'
                WHERE login = '$this->login'";

                $mysqli->query($sql);
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
                global $mysqli;
                $sql = "SELECT login, password, email, firstname, lastname FROM utilisateurs";

                $result = $mysqli->query($sql);
                while($row = $result->fetch_assoc()) {
                    if($row["login"] == $this->login && $row["password"] == $this->password){
                    $this->login = $row["login"];
                    $this->password = $row["password"];
                    $this->email = $row["email"];
                    $this->firstname = $row["firstname"];
                    $this->lastname = $row["lastname"];
                    //echo "mail " . $email . " prenom " . $firstname . " nom " . $lastname;
                    }
                  }
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


        if (!isset($_SESSION['user'])){
            $_SESSION['user'] = "";
            session_start();
            
        }


        $test = new User();

        //$test->register('nerok', '1234', 'mail@gmail.com', 'nicolas', 'panis');

        $test->connect('nerok', '1234');
        echo "Bonjour " . $_SESSION['user'];
        echo $test->isConnected();
        $test->disconnect();
        

        echo "<br />";

        $test->connect('test', '1234');
        echo "Bonjour " . $_SESSION['user'];
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