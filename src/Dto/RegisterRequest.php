<?php

namespace App\Dto;

class RegisterRequest
{
    public string $email;
    public string $password;
    public string $nom;
    public string $prenom;
    public string $telephone;
    public string $codeSynchro;
    public string $couleur;
}