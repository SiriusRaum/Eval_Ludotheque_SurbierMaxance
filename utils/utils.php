<?php
function formatJoueurs(int $min, int $max){
    if ($min === 1 && $max === 1)
    return "Solo";
        else if ($min === $max)
            return  $min . " joueurs";
            else if ($min !== $max)
                return  $min . " à " . $max . " joueurs";
        }

function categorieDuree($duree){
    if ($duree < "30" )
    return "Partie rapide";
        else if ( "30" <= $duree && $duree<= "90")
            return  "Partie moyenne";
            else if ($duree > "90")
                return  "Partie longue";
        }
?>