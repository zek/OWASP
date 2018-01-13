<?php

namespace App\Database;

class Manager
{

    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    private $name;

    /**
     * connect to the SQLite database
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->pdo = new \PDO("sqlite:" . $name);
    }

    public function destroy()
    {
        unlink($this->name);
        $this->pdo = new \PDO("sqlite:" . $this->name);
    }

    /**
     * create tables
     */
    public function createTables()
    {
        $commands = [
            'CREATE TABLE IF NOT EXISTS users ( user_id INTEGER PRIMARY KEY, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_admin INTEGER NOT NULL )',
            'CREATE TABLE staff (  
		staff_id  INTEGER PRIMARY KEY,  
		name     varchar(30) NOT NULL, 
		surname      varchar(30) NOT NULL, 
		email		varchar(70) NOT NULL, 
		phone		  varchar(20) NOT NULL
);'
        ];
        foreach ($commands as $command) {
            $this->pdo->exec($command);
        }
    }

    /**
     * get the table list in the database
     */
    public function getTableList()
    {
        $stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type = 'table' ORDER BY name");
        $tables = [];
        while ($row = $stmt->fetchArray()) {
            $tables[] = $row['name'];
        }
        return $tables;
    }

    /**
     * seeds tables
     */
    public function seed()
    {
        $users = [
            ["student", "student123", 0],
            ["admin", "youcanneversee", 1],
        ];
        $qry = $this->pdo->prepare('INSERT INTO users (username, password, is_admin) VALUES (?, ?, ?)');
        foreach ($users as $user) {
            $qry->execute($user);
        }

        $staff = [
            ['Henry', 'Holmes', 'hholmes0@dailymail.co.uk', '48-(104)384-151'],
            ['Antonio', 'Carpenter', 'acarpenter3@mozilla.com', '48-(975)699-950'],
            ['Martin', 'Mills', 'mmillsl@opera.com', '48-(225)490-277'],
            ['Carol', 'Pierce', 'cpiercec@moonfruit.com', '48-(690)371-415'],
            ['Timothy', 'Oliver', 'toliverd@sphinn.com', '48-(155)627-691'],
            ['Lawrence', 'Cruz', 'lcruzq@ibm.com', '48-(268)889-8980'],
        ];
        $qry = $this->pdo->prepare('INSERT INTO staff (name, surname, email, phone) VALUES (?, ?, ?, ?)');
        foreach ($staff as $s) {
            $qry->execute($s);
        }
    }


    public function getPDO()
    {
        return $this->pdo;
    }
}