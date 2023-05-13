<?php
trait ViewArticleIp
{
    // Cette fonction nous donne l'adresse ip de l'utilisateur
    public function getAdresseIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    // Retourne un formulaire
    public function inputIp($hostname)
    {
        return "<form method=" . "\"post\"" . "><input id='adresse_ip' type='hidden' value=" . "\"$hostname\"" . "/></form>";
    }
}