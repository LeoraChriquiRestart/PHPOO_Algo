<?php

class Game { // Création de la classe Game

    private $heroes; // Tableau qui contient les héros
    private $enemies; // Tableau qui contient les ennemis
    private $difficulties = ["Facile" => 5, "Difficile" => 10, "Impossible" => 20]; // Tableau qui contient les difficultés

    public function __construct() { // Constructeur de la classe Game
        $this->createHeroes(); // J'appelle la fonction createHeroes() pour créer les héros
    }

    public function createHeroes() { // Fonction qui crée les héros
        $SeongGihun =  new Hero("Seong Gi-hun", 15, 1, 2, "WAAAA"); // J'instancie la classe Hero pour créer les héros
        $KangSaebyeok = new Hero("Kang Sae-byeok", 25, 2, 1, "YEAHHH"); 
        $ChoSangwoo =  new Hero("Cho Sang-woo", 35, 3, 0, "HRRRRR");

        $this->heroes = [$SeongGihun, $KangSaebyeok, $ChoSangwoo]; // J'ajoute les héros dans le tableau $heroes
    }

    public function createEnemies($roundNumber) { // Fonction qui crée les ennemis
        for($i = 0; $i <= $roundNumber; $i++) { // Je crée autant d'ennemis que le nombre de manches
            $enemy = new Enemy("Ennemi " . $i + 1, rand(1, 20), rand(18, 95)); // J'instancie la classe Enemy pour créer les ennemis
            $this->enemies[] = $enemy; // J'ajoute les ennemis dans le tableau $enemies
        }
    }
    
    public function chooseHero() { // Fonction qui choisit un héros aléatoirement
        return $this->selectedHero = $this->heroes[array_rand($this->heroes)]; // Je choisis un héros aléatoirement dans le tableau $heroes 
    }

    public function chooseDifficulty() { // Fonction qui choisit une difficulté aléatoirement
        return $this->selectedDifficulty = array_rand($this->difficulties); // Je choisis une difficulté aléatoirement dans le tableau $difficulties
    }

    public function startGame() { // Fonction qui lance le jeu
        $selectedHero = $this->chooseHero(); // Je récupère mon héro choisi aléatoirement et je le stocke dans une variable
        $selectedDifficulty = $this->chooseDifficulty(); // Je récupère la difficulté choisie aléatoirement et je la stocke dans une variable
        $roundNumber = $this->difficulties[$selectedDifficulty]; // Je récupère le nombre de manches en fonction de la difficulté choisie

        echo "Le jeu commence, bonne chance à tous !" . "<br> <br>";
        echo "Bienvenue dans le jeu, " . $selectedHero->getName() . ".<br>"; // J'affiche le nom du héros
        echo "Tu possèdes " . $selectedHero->getMarbles() . " billes, si tu gagnes une manche, tu obtiens " . $selectedHero->getBonus() . " billes en bonus, sinon tu en perds " . $selectedHero->getMalus() . " en plus.<br>"; // J'affiche le nombre de billes du héros, son bonus et son malus
        echo "La difficulté sélectionnée est : " . $selectedDifficulty . "<br>"; // J'affiche la difficulté choisie
        echo "Tu dois affronter " . $roundNumber . " ennemis pour gagner la partie." . "<br> <br>"; // J'affiche le nombre de manches max
        $this->createEnemies($roundNumber); // J'appelle la fonction createEnemies() pour créer les ennemis autant de fois que le nombre de manches max

        $i = 1; // Je crée une variable qui va me permettre de compter le nombre de manches
        while ($i <= $roundNumber && $selectedHero->getMarbles() > 0 && $this->enemies != []) { // Je fais une boucle qui tourne tant que le nombre de manches n'est pas atteint, que le héros a encore des billes et qu'il reste des ennemis
            echo "Tu joues contre " . $this->enemies[0]->getName() . ", qui a " . $this->enemies[0]->getMarbles() . " billes." . "<br>";
            $enemy = reset($this->enemies); // Je récupère le premier ennemi du tableau $enemies
            if ($enemy->checkAge($enemy) == true) { // Si l'ennemi est vieux, le héros peut tricher
                if ($enemy->cheatOrNot($enemy) == true) { // Si le héros triche, il gagne
                    echo "Tu as gagné contre " . $enemy->getName() . " en trichant. Tu remportes ". $enemy->getMarbles() . " billes.<br>"; 
                    $selectedHero->setMarbles($selectedHero->getMarbles() + $enemy->getMarbles()); // Je modifie le nombre de billes du héros en ajoutant le nombre de billes de l'ennemi
                    echo "Il te reste " . $selectedHero->getMarbles() . " billes." . "<br>";
                    array_shift($this->enemies); // Je retire l'ennemi de la liste après la confrontation
                    $i++; // J'ajoute 1 à la variable qui compte le nombre de manches
                    echo "<br>";
                } else {
                    $selectedHero->checkEvenOrOdd($enemy); // Si le héros ne triche pas, il joue normalement
        
                    echo "Il te reste " . $selectedHero->getMarbles() . " billes." . "<br>";
                    array_shift($this->enemies); 
                    $i++; 
                    echo "<br>";
                }
            } else {
                $selectedHero->checkEvenOrOdd($enemy); // Si l'ennemi n'est pas vieux, le héros joue normalement
    
                echo "Il te reste " . $selectedHero->getMarbles() . " billes." . "<br>"; 
                array_shift($this->enemies); 
                $i++;
                echo "<br>";
            }

        }
        if ($selectedHero->getMarbles() <= 0) { // Si le héros n'a plus de billes, il perd
            echo "Tu as perdu, tu n'as plus de billes. Prépare toi à mourir." . "<br>";
        } else { // Si le héros a encore des billes, il gagne
            echo "Bravo, tu as gagné, tu remportes 45,6 milliards de Won sud-coréen !" . "<br>";
        }

    }



}

class Character {
    
    private $name; // Nom du personnage
    private $marbles; // Nombre de billes du personnage

    public function __construct($name, $marbles) { // Constructeur de la classe Character
        $this->name = $name;
        $this->marbles = $marbles;
    }

    public function getName() { // Je recupère le nom du personnage
        return $this->name;
    }

    public function getMarbles() { // Je recupère le nombre de billes du personnage
        return $this->marbles;
    }
    
    public function setMarbles($marbles) { // Je modifie le nombre de billes du personnage
        $this->marbles = $marbles;
    }
}

class Hero extends Character {

    private $bonus; // Bonus de billes si le héros gagne
    private $malus; // Malus de billes si le héros perd
    private $screamWar; // Cri de guerre du héros

    public function __construct($name, $marbles, $bonus, $malus, $screamWar) { // Constructeur de la classe Hero
        parent::__construct($name, $marbles); // J'appelle le constructeur de la classe Character
        $this->bonus = $bonus;
        $this->malus = $malus;
        $this->screamWar = $screamWar;
    }

    public function getBonus() { // Je recupère le bonus de billes du héros
        return $this->bonus;
    }
    
    public function getMalus() { // Je recupère le malus de billes du héros
        return $this->malus;
    }
    
    public function getScreamWar() { // Je recupère le cri de guerre du héros
        return $this->screamWar;
    }
    

    private function evenOrOdd() { // Fonction qui détermine si le héros a trouvé un nombre pair ou impair
        $result = rand(1, 2);
        if($result == 1) {
            return "pair";
        } else {
            return "impair";
        }
    }

    public function checkEvenOrOdd($enemy) { // Fonction qui détermine si le héros a gagné ou perdu et combien de billes il gagne ou perd
        if ($this->evenOrOdd() == "pair") { 
            echo "Tu as gagné, donc tu as un bonus de " . $this->getBonus() . " billes !" . "<br>"; 
            echo $this->getScreamWar() . "<br>";
            $this->setMarbles($this->getMarbles() + $this->getBonus() + $enemy->getMarbles()); // Je modifie le nombre de billes du héros en ajoutant le bonus et le nombre de billes de l'ennemi
        } else {
            echo "Tu as perdu, donc tu as un malus de " . $this->getMalus() . " billes." . "<br>";
            $this->setMarbles($this->getMarbles() - ($this->getMalus() + $enemy->getMarbles())); // Je modifie le nombre de billes du héros en retirant le malus et le nombre de billes de l'ennemi
        }

    }

}

class Enemy extends Character {

    private $age; // Age de l'ennemi

    public function __construct($name, $marbles, $age) { // Constructeur de la classe Enemy
        parent::__construct($name, $marbles); // J'appelle le constructeur de la classe Character
        $this->age = $age;
    }

    public function getAge() { // Je recupère l'age de l'ennemi
        return $this->age;
    }

    public function checkAge($enemy) { // Fonction qui détermine si l'ennemi est vieux ou pas
        if($enemy->getAge() > 70) { 
            echo "Ton ennemi est vieux, il a " . $enemy->getAge() . " ans." . "<br>";
            return true;
        } else {
            return false;
        }
    }

    public function cheatOrNot() { // Fonction qui détermine si le héros triche ou pas
        $cheat = rand(1, 2);
        if($cheat == 1) {
            return true;
        } else {
            return false;
        }
    }
}
$game = new Game(); // J'instancie la classe Game
$game->startGame(); // J'appelle la fonction startGame() de la classe Game pour commencer la partie
?>