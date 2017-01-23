<?php

/** @Entity */
class Artist
{
    /**
    * @Id
    * @GeneratedValue(strategy="AUTO")
    * @SequenceGenerator(sequenceName="id_artist")
    * @Column(type="integer")
    */  
    private $id_artist;
    /** @Column(type="string", length=255) */
    public $firstname;
    /** @Column(type="string", length=255) */
    public $lastname;   
    /** @Column(type="date") */
    public $birthday;
    /** @Column(type="text") */
    public $biography;       
    /**
     * @Column(type="integer")     *
     * @ManyToOne(targetEntity="Country")
     * @JoinColumns({
     *   @JoinColumn(name="country", referencedColumnName="id_country")
     * })
     */
     public $country;
}

