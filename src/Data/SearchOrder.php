<?php

namespace App\Data;

class SearchOrder
{
    /**
     * Numéro de page pour knp_paginator
     *
     * @var integer
     */
    public $page = 1;

    /**
     * Le numéro de la commande
     *
     * @var int
     */
    public $numero;

    /**
     * Numéro de compte
     *
     * @var Account[]
     */
    public $account = [];

    /**
     * Fournisseur
     *
     * @var Provider
     */
    public $provider;

    /**
     * Status
     *
     * @var Status
     */
    public $status;

    /**
     * Désignation
     *
     * @var Designation
     */
    public $designation;

    /**
     * Utilisateur
     *
     * @var user
     */
    public $user;

    
    
}