<?php
/**
 * Created by PhpStorm.
 * User: Kamil
 * Date: 2018-06-12
 * Time: 21:37
 */

class User {
    // Static REPOSITORY methods
    static public function GetAllUsers(mysqli $conn) {
        $sql = "SELECT * FROM Users";
        $result = $conn->query($sql);
        $toReturn = [];
        if ($result != false) {
            foreach($result as $row) {
                $newUser = new User();
                $newUser->id = $row['id'];
                $newUser->login = $row['login'];
                $newUser->hassedPassword = $row['hassed_password'];
                $newUser->name = $row['name'];
                $newUser->surname = $row['surname'];
                $newUser->score = $row['score'];

                $toReturn[] = $newUser;
            }
        }
        return $toReturn;
    }

    static public function LogIn(mysqli $conn, $login, $password) {
        $toReturn = null;
        $sql = "SELECT * FROM Users WHERE login='{$login}'";
        $result = $conn->query($sql);
        if ($result != false) {
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $loggedUser = new User();
                $loggedUser->id = $row['id'];
                $loggedUser->login = $row['login'];
                $loggedUser->hassedPassword = $row['hassed_password'];
                $loggedUser->name = $row['name'];
                $loggedUser->surname = $row['surname'];
                $loggedUser->score = $row['score'];
                if ($loggedUser->verifyPassword($password)) {
                    $toReturn = $loggedUser;
                }
            }
        }
        return $toReturn;
    }

    //Attributes
    private $id;
    private $login;
    private $hassedPassword;
    private $name;
    private $surname;
    private $score;

    //Functions
    public function __construct() {
        $this->id = -1;
        $this->login = '';
        $this->hassedPassword = '';
        $this->name = '';
        $this->surname = '';
        $this->score = 0;
    }

    public function getId() {
        return $this->id;
    }

    public function setLogin($newLogin) {
        $this->login = $newLogin;
    }

    public function checkLogin (mysqli $conn, $newLogin) {
        $sql = "SELECT * FROM Users WHERE login='$newLogin'";
        $result = $conn->query($sql);
        if ($result != false) {
            if ($result->num_rows == 0) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setPassword($newPassword1, $newPassword2) {
        if ($newPassword1 != $newPassword2) {
            return false;
        }
        $hassedPassword = password_hash($newPassword1, PASSWORD_BCRYPT);
        $this->hassedPassword = $hassedPassword;
        return true;
    }

    public function setName($newName) {
        $this->name = $newName;
    }

    public function getName() {
        return $this->name;
    }

    public function setSurname($newSurname) {
        $this->surname = $newSurname;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setScore($newScore) {
        $this->score = $newScore;
    }

    public function getScore() {
        return $this->score;
    }

    public function saveToDB(mysqli $conn) {
        if ($this->id == -1) {
            //insert new row to DB
            $sql = "INSERT INTO Users (login, hassed_password, name, surname, score)
                    VALUES ('{$this->login}','{$this->hassedPassword}','{$this->name}','{$this->surname}','{$this->score}')";
            $result = $conn->query($sql);
            if ($result == TRUE) {
                $this->id = $conn->insert_id;
                return true;
            } else {
                return false;
            }
        } else {
            //update row in DB
            $sql = "UPDATE Users SET login = '{$this->login}',
                                     hassed_password='{$this->hassedPassword}',
                                     name = '{$this->name}',
                                     surname = '{$this->surname}',
                                     score = '{$this->score}',
                                     WHERE id={$this->id}";
            $result = $conn->query($sql);
            return $result;
        }
    }

    public function loadFromDB(mysqli $conn, $id) {
        $sql = "SELECT * FROM Users WHERE id = $id";
        $result = $conn->query($sql);
        if ($result != FALSE) {
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $this->id = $row['id'];
                $this->login = $row['login'];
                $this->hassedPassword = $row['hassed_password'];
                $this->name = $row['name'];
                $this->surname = $row['surname'];
                $this->score = $row['score'];
                return true;
            }
        }
        return false;
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->hassedPassword);
    }

    //Counting functions

}
?>