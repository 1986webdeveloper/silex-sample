<?php

/** @Entity */
class Category
{
    /**
    * @Id
    * @GeneratedValue(strategy="AUTO")
    * @SequenceGenerator(sequenceName="id_category")
    * @Column(type="integer")
    */  
    private $id_category;
    /** @Column(type="string", length=255) */
    public $label;
    /** @Column(type="string", length=255) */
    public $seo_url;   
}

