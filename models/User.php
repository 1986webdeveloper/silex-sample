<?php

/** @Entity */
class User
{
    /**
    * @Id
    * @GeneratedValue(strategy="AUTO")
    * @SequenceGenerator(sequenceName="id_user")
    * @Column(type="integer")
    */  
    private $id_user;
    /** @Column(type="string", length=255) */
    public $username;
    /** @Column(type="string", length=255) */
    public $password;   
   
}

