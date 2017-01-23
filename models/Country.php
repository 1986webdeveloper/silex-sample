<?php

/** @Entity */
class Country
{
    /**
    * @Id
    * @GeneratedValue(strategy="AUTO")
    * @SequenceGenerator(sequenceName="id_country")
    * @Column(type="integer")
    */  
    private $id_country;
    /** @Column(type="string", length=255) */
    public $label_fr;
    /** @Column(type="string", length=255) */
    public $label_en;   
    /** @Column(type="string", length=255) */
    public $seo_url_fr;
    /** @Column(type="string", length=255) */
    public $seo_url_en;   
}

