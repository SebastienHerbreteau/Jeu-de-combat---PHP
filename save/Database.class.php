<?php
class Database
{
    public string $host;
    public string $user;
    public string $password;
    public string $dbName;
    public PDOStatement $request;
    public PDO $connexion;
    public int $pv = 100;
    public int $force = 0;
    public int $pa = 0;


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


    public function fetchData()
    {
        return  $this->request->fetchAll();
    }
    // public function createPerso($pseudo, int $pv, int $force, int $pa)
    // {
    //     // exe requete       
    // }
}
