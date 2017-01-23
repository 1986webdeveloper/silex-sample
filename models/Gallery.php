<?php

/** @Entity */
class Gallery
{
    /**
    * @Id
    * @GeneratedValue(strategy="AUTO")
    * @SequenceGenerator(sequenceName="id_gallery")
    * @Column(type="integer")
    */  
    private $id_gallery;
    /** @Column(type="string", length=255) */
    public $name;
    /** @Column(type="string", length=255) */
    public $email;   
}

