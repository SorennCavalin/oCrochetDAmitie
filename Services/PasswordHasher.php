<?php

namespace Services;


class PasswordHasher {
    static function hashPassword($mdp) {
        // optimisation du parametre cost de password hasher
        $timeTarget = 0.05; // 50 millisecondes

        $cost = 1;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);

        // traitement de mdp avant de le hasher et de le retourner
        $mdp = addslashes(trim($mdp));
        return password_hash($mdp, PASSWORD_BCRYPT, ["cost" => $cost]);
    }
}