<?php

/** @Entity */
class Artwork
{
    /**
    * @Id
    * @GeneratedValue(strategy="AUTO")
    * @SequenceGenerator(sequenceName="id_artwork")
    * @Column(type="integer")
    */  
    private $id_artwork;
    /** @Column(type="string", length=255) */
    public $artwork_title;
    /** @Column(type="text") */
    public $biography;   
    /** @Column(type="date") */
    public $artwork_year;        
    /** @Column(type="decimal", precision=10, scale=2) */
    public $artwork_price;
    /** @Column(type="string", length=255) */
    public $artwork_dimensions;       
    /** @Column(type="string", columnDefinition="ENUM('True', 'False')") */
    private $is_certificated;
    /** @Column(type="string", columnDefinition="ENUM('True', 'False')") */
    private $is_framed;
    /** @Column(type="string", columnDefinition="ENUM('True', 'False')") */
    private $is_numbered;    
    /**
     * @Column(type="integer")     *
     * @ManyToOne(targetEntity="Artist")
     * @JoinColumns({
     *   @JoinColumn(name="artist", referencedColumnName="id_artist")
     * })
     */    
     public $artist;
     /**
     * @Column(type="integer")     *
     * @ManyToOne(targetEntity="Category")
     * @JoinColumns({
     *   @JoinColumn(name="category", referencedColumnName="id_category")
     * })
     */
     public $category;
     /**
     * @Column(type="integer")     *
     * @ManyToOne(targetEntity="Gallery")
     * @JoinColumns({
     *   @JoinColumn(name="gallery", referencedColumnName="id_gallery")
     * })
     */
     public $gallery;
    
}

