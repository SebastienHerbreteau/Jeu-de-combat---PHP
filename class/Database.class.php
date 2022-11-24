<?php
class Database
{
    public string $host;
    public string $user;
    public string $password;
    public string $dbName;
    public PDOStatement $request;
    public PDO $connexion;

    public function __construct(string $host, string $dbName, string $user, string $password)
    {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
    }


    public function connect(): PDO
    {
        $this->connexion = new PDO(
            "mysql:host=$this->host;
            charset=utf8;
            dbname=$this->dbName;",
            "$this->user",
            "$this->password"
        );
        return $this->connexion;
    }

    public function prepReq(string $query, array $array = []): PDOStatement
    {
        $this->request = $this->connexion->prepare($query);
        $this->request->execute($array);
        return $this->request;
    }

    public function create(string $name,  array $array = []): PDOStatement
    {
        $this->request = $this->connexion->prepare("INSERT INTO personnage (nom, PV, power, PA) VALUES ('$name',100, 0, 0)");
        $this->request->execute($array);
        return $this->request;
    }

    public function read(string $name, array $array = []): PDOStatement
    {
        $this->request = $this->connexion->prepare("SELECT * FROM personnage WHERE nom LIKE '$name'");
        $this->request->execute($array);
        return $this->request;
    }

    public function update(string $name, string $PV, array $array = []): PDOStatement
    {
        $this->request = $this->connexion->prepare("UPDATE personnage SET PV = '$PV' WHERE nom LIKE '$name'");
        $this->request->execute($array);
        return $this->request;
    }

    public function delete(string $name, array $array = []): PDOStatement
    {
        $this->request = $this->connexion->prepare("DELETE FROM personnage WHERE nom LIKE '$name' ");
        $this->request->execute($array);
        return $this->request;
    }

    public function fetchData()
    {
        return  $this->request->fetchAll();
    }
}
