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
'CREATE TABLE IF NOT EXISTS users (
        user_id INTEGER PRIMARY KEY, 
        username VARCHAR(255) NOT NULL, 
        password VARCHAR(255) NOT NULL, 
        name VARCHAR(255) NOT NULL, 
        surname VARCHAR(255) NOT NULL, 
        faculty VARCHAR(255) NOT NULL, 
        field VARCHAR(255) NOT NULL, 
        country VARCHAR(255) NOT NULL, 
        is_admin INTEGER NOT NULL
)',
'CREATE TABLE staff (  
		staff_id  INTEGER PRIMARY KEY,  
		name     varchar(30) NOT NULL, 
		surname      varchar(30) NOT NULL, 
		email		varchar(70) NOT NULL, 
		phone		  varchar(20) NOT NULL
);',
'CREATE TABLE lessons (  
		lesson_id  INTEGER PRIMARY KEY,  
		name     varchar(30) NOT NULL, 
		code      varchar(30) NOT NULL, 
		ects		varchar(70) NOT NULL
);',
'CREATE TABLE lectures (  
		lesson_id  INTEGER NOT NULL,  
		user_id     INTEGER NOT NULL, 
		grade      varchar(2) NOT NULL
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
        $this->userSeeder();
        $this->staffSeeder();
        $this->lessonSeeder();
    }

    public function userSeeder()
    {
        $users = [
            ["student", "student123", "Zekeriya", "Durmus", "Engineering", "Computer Science", "Turkey", 0],
            ["burak", "burak123","Burak", "Degirmenci", "Engineering", "Computer Science", "Turkey", 0],
            ["bill", "bill321", "Bill", "Gates", "Engineering", "Computer Science", "USA", 0],
            ["steve", "stv314", "Steve", "Jobs", "Engineering", "Computer Science", "USA", 0],
            ["linus", "312linus","Linus", "Torvalds", "Engineering", "Computer Science", "Finland", 0],
            ["seymour", "supercomputer","Seymour", "Cray", "Engineering", "Electrical Engineer", "USA", 0],
            ["mzachara", "noteasytofind", "Marek", "Zachara", "Engineering", "Computer Science", "Poland", 1],
        ];
        $qry = $this->pdo->prepare('INSERT INTO users (username, password, name, surname, faculty, field, country, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        foreach ($users as $user) {
            $qry->execute($user);
        }
    }

    public function lessonSeeder()
    {
        $lessons = [
            ["Compilation Theory", "UBPJO-236", 5],
            ["Cybersecurity: systems' and data protection", "UBPJO-088", 5],
            ["Software design patterns in object oriented programming", "UBPJO-160", 4],
            ["POLISH language course", "UBPJO-237", 5],
            ["Scripting languages", "UBPJO-235", 4],
            ["Modern computer architectures", "UBPJO-121", 4],
        ];
        $qry = $this->pdo->prepare('INSERT INTO lessons (name, code, ects) VALUES (?, ?, ?)');
        foreach ($lessons as $lesson) {
            $qry->execute($lesson);
        }
    }

    public function staffSeeder()
    {
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

    public function lecturesSeeder()
    {
        $staff = [
            [1, 1, 'A'],
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