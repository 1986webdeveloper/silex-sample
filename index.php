<?php
// Autoloader for load all required modules
require_once __DIR__ . '/vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

$app = new Silex\Application();

//Database connection settings
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
    'driver'   => 'pdo_mysql',
    'user'     => 'YOUR_DB_USER_NAME',
    'password' => 'YOUR_DB_USER_PASSWORD',
    'dbname'   => 'YOUR_DB_NAME'
    ),
));

// Register validator provider
$app->register(new Silex\Provider\ValidatorServiceProvider());

//Get schema manger for excute queries
$schema = $app['db']->getSchemaManager();

//Check if User table is exist and blank then insert default value
if ($schema->tablesExist('User')) {
  $sql = "SELECT count(*) as totalrec FROM User";
  $data = $app['db']->fetchAssoc($sql);
    if($data['totalrec']<=0)
    {    
        $app['db']->insert('User', array(
          'username' => 'admin',
          'password' => 'nhDr7OyKlXQju+Ge/WKGrPQ9lPBSUFfpK+B1xqx/+8zLZqRNX0+5G1zBQklXUFy86lCpkAofsExlXiorUcKSNQ=='
        ));
    }
}

//Check if Category table is exist and blank then insert default value
if ($schema->tablesExist('Category')) {
  $sql = "SELECT count(*) as totalrec FROM Category";
  $data = $app['db']->fetchAssoc($sql);
    if($data['totalrec']<=0)
    {    
        $app['db']->insert('Category', array(
          'label' => 'Painting',
          'seo_url' => '/painting'
          
        ));
        
        $app['db']->insert('Category', array(
          'label' => 'Sculpture',
          'seo_url' => '/sculpture'
          
        ));
        
        $app['db']->insert('Category', array(
          'label' => 'Print',
          'seo_url' => '/print'
          
        ));
        
        $app['db']->insert('Category', array(
          'label' => 'Photography',
          'seo_url' => '/photography'
          
        ));
    }
}

//Check if Gallery table is exist and blank then insert default value
if ($schema->tablesExist('Gallery')) {
  $sql = "SELECT count(*) as totalrec FROM Gallery";
  $data = $app['db']->fetchAssoc($sql);
    if($data['totalrec']<=0)
    {    
        $app['db']->insert('Gallery', array(
          'name' => 'Artsper Gallery',
          'email' => 'artsper@artsper-nobody.com'
        ));
    }
}


//Check if country table is exist and blank then insert default value
if ($schema->tablesExist('Country')) {
  $sql = "SELECT count(*) as totalrec FROM Country";
  $data = $app['db']->fetchAssoc($sql);
    if($data['totalrec']<=0)
    {    
        $app['db']->insert('Country', array(
          'label_fr' => 'France',
          'label_en' => 'France',
          'seo_url_fr' =>'/france',
          'seo_url_en' =>'/france'
        ));
    }
}

//Check if Artist table is exist and blank then insert default value
if ($schema->tablesExist('Artist')) {
  $sql = "SELECT count(*) as totalrec FROM Artist";
  $data = $app['db']->fetchAssoc($sql);
    if($data['totalrec']<=0)
    {    
        $app['db']->insert('Artist', array(
          'firstname' => 'Betty',
          'lastname' => 'Pelmont',
          'birthday' =>'1978-01-20',
          'biography' =>'Lorem lpsum',
          'country'=>1
        ));
    }
}


//Check if Artist table is exist and blank then insert default value
if ($schema->tablesExist('Artwork')) {
  $sql = "SELECT count(*) as totalrec FROM Artwork";
  $data = $app['db']->fetchAssoc($sql);
    if($data['totalrec']<=0)
    {    
        $app['db']->insert('Artwork', array(
        'artwork_title' => 'One word',
        'biography' => 'Lorem lpsum',
        'artwork_year' => '2014-10-10',
        'artwork_price' => '1078',
        'artwork_dimensions' => '{"w":70,"h":19,"i":20}',
        'is_certificated' => 'true',
        'is_framed' => 'true',
        'is_numbered' =>'false',
        'artist' => 1,
        'category' => 1,
        'gallery' => 1
        ));
    }
}

// Validate input if its blank or not
function check_error($request,$app)
{
    $error_list="";
    // Create artwork array to insert
    $post = array(
        'artwork_title' => $request->request->get('artwork_title'),
        'biography' => $request->request->get('biography'),
        'artwork_year' => $request->request->get('artwork_year'),
        'artwork_price' => $request->request->get('artwork_price'),
        'artwork_dimensions' => $request->request->get('artwork_dimensions'),
        'is_certificated' => $request->request->get('is_certificated'),
        'is_framed' => $request->request->get('is_framed'),
        'is_numbered' => $request->request->get('is_numbered'),
        'artist' => $request->request->get('artist'),
        'category' => $request->request->get('category'),
        'gallery' => $request->request->get('gallery')
    );
     // Create array for validation
    $constraint = new Assert\Collection(array(
        'artwork_title' => new Assert\NotBlank(),
        'biography' => new Assert\NotBlank(),
        'artwork_year' => new Assert\NotBlank(),
        'artwork_price' => new Assert\NotBlank(),
        'artwork_dimensions' => new Assert\NotBlank(),
        'is_certificated' => new Assert\NotBlank(),
        'is_framed' => new Assert\NotBlank(),
        'is_numbered' => new Assert\NotBlank(),
        'artist' => new Assert\NotBlank(),
        'category' => new Assert\NotBlank(),
        'gallery' => new Assert\NotBlank(),
    ));
    // Check if given array is valuidate or not
    $errors = $app['validator']->validate($post, $constraint);
        
    
    // If error found then aad erros in return array
    if (count($errors) > 0) {
        $error_list = "";
        foreach ($errors as $error) {
            $error_list .= $error->getPropertyPath() . ' ' . $error->getMessage() . ", ";
        }
        $error_list = rtrim($error_list, ", ");
       
    } 
    return $error_list;
}

// Add authentication method before add/edit/delete/list
$app->before(function(Request $request) use ($app)
{  
    $request->headers->set('Content-Type', 'application/json');
     if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
    if (!isset($_SERVER['PHP_AUTH_USER']))
    {
        return $app->json(array('status' => 'failure'
            ,'message'=>'not authorized'), 401);
    }
    else
    {
        // Check username and password from database tabel 
        $query= $app['db']->createQueryBuilder();
        $query->select('*')->from('User', 'aw');
        $sth = $query->execute(); 
        $user = $sth->fetchAll(PDO::FETCH_ASSOC);
        $user_a="";
        $encoder = new MessageDigestPasswordEncoder();
     
        foreach($user as $key=>$val)
        {
            $user_a[$val['username']]=$val['password'];
        }
       
  

    if($user_a[$_SERVER['PHP_AUTH_USER']] !== $encoder->encodePassword($_SERVER['PHP_AUTH_PW'],''))
    {
        //If the password for this user is not correct then resond as such
        return $app->json(array(array('status' => 'failure'
        ,'message'=>'not authorized')), 403);
    }

    
    }
});

// Artwork add router
$app->post('/add', function (Request $request) use ($app) {

    $error_list= check_error($request,$app);
    
    // Default return array
    $return_array = array("status" => "failure", "message" => "error");
    
    if($error_list!='')
    {
         $return_array['message'] = $error_list;
    }
    // If no error found then we insert artwork and return success array
    else {
        
        // Create artwork array to insert
        $post = array(
            'artwork_title' => $request->request->get('artwork_title'),
            'biography' => $request->request->get('biography'),
            'artwork_year' => $request->request->get('artwork_year'),
            'artwork_price' => $request->request->get('artwork_price'),
            'artwork_dimensions' => $request->request->get('artwork_dimensions'),
            'is_certificated' => $request->request->get('is_certificated'),
            'is_framed' => $request->request->get('is_framed'),
            'is_numbered' => $request->request->get('is_numbered'),
            'artist' => $request->request->get('artist'),
            'category' => $request->request->get('category'),
            'gallery' => $request->request->get('gallery')
        );
        
    
        // Insert artwork into artwork table
        $chk_ins = $app['db']->insert('Artwork', $post);
        
        // If artwork inserted successfully then return success array
        if ($chk_ins)
            $return_array = array("status" => "success", "message" => "artwork added.");
    }
    
    // Return result
    return $app->json($return_array);
});

// Edit artwork
$app->post('/edit/{id}', function ($id,Request $request) use ($app) {
  
     // Default return array
   $return_array = array("status" => "failure", "message" => "error");

    //Check if request value is not blank then append it to update query
   $update_query="";
   if( $request->request->get('artwork_title')!='')
    $update_query .="artwork_title = :artwork_title,";
   if( $request->request->get('biography')!='')
    $update_query .="biography = :biography,";
   if( $request->request->get('artwork_year')!='')
    $update_query .="artwork_year = :artwork_year,";
   if( $request->request->get('artwork_price')!='')
    $update_query .="artwork_price = :artwork_price,"; 
   if( $request->request->get('artwork_dimensions')!='')
    $update_query .="artwork_dimensions = :artwork_dimensions,";
   if( $request->request->get('is_certificated')!='')
    $update_query .="is_certificated = :is_certificated,";
   if( $request->request->get('is_framed')!='')
    $update_query .="is_framed = :is_framed,";
   if( $request->request->get('is_numbered')!='')
    $update_query .="is_numbered = :is_numbered,";   
   if( $request->request->get('artist')!='')
    $update_query .="artist = :artist,";
   if( $request->request->get('category')!='')
    $update_query .="category = :category,";
   if( $request->request->get('gallery')!='')
    $update_query .="gallery = :gallery,";   
   
   $update_query= rtrim($update_query,",");
  
   $update_query_new="";
   $chk_ins=false;
 
   // Check if anything to update
   if($update_query!='') 
   {
        $update_query_new =" UPDATE Artwork SET {$update_query} WHERE id_artwork = :id_artwork";
        $sth = $app['db']->prepare($update_query_new);

        // Bind query value if its not blank
        if( $request->request->get('artwork_title')!='') $sth->bindValue(":artwork_title",$request->request->get('artwork_title'));
        if( $request->request->get('biography')!='') $sth->bindValue(":biography", $request->request->get('biography'));
        if( $request->request->get('artwork_year')!='') $sth->bindValue(":artwork_year", $request->request->get('artwork_year'));
        if( $request->request->get('artwork_price')!='') $sth->bindValue(":artwork_price",$request->request->get('artwork_price'));
        if( $request->request->get('artwork_dimensions')!='') $sth->bindValue(":artwork_dimensions", $request->request->get('artwork_dimensions'));
        if( $request->request->get('is_certificated')!='') $sth->bindValue(":is_certificated", $request->request->get('is_certificated'));
        if( $request->request->get('is_framed')!='') $sth->bindValue(":is_framed", $request->request->get('is_framed'));
        if( $request->request->get('is_numbered')!='') $sth->bindValue(":is_numbered", $request->request->get('is_numbered'));
        if( $request->request->get('artist')!='') $sth->bindValue(":artist", $request->request->get('artist'));
        if( $request->request->get('category')!='') $sth->bindValue(":category", $request->request->get('category'));
        if( $request->request->get('gallery')!='') $sth->bindValue(":gallery", $request->request->get('gallery'));
        $sth->bindValue(":id_artwork", $id, PDO::PARAM_INT);
        // Excecute query
        $chk_ins=$sth->execute();
   }

   

    // If artwork inserted successfully then return success array
    if ($chk_ins)
     $return_array = array("status" => "success", "message" => "artwork updated.");


    // Return result
    return $app->json($return_array);
  

});

// Delet artwork
$app->delete('/delete/{id}', function ($id,Request $request) use ($app) {
    $chk_del=$app['db']->delete('Artwork', array('id_artwork' => $id));
    $return_array = array("status" => "failure", "message" => "record is not deleted.");
    if($chk_del)  $return_array = array("status" => "success", "message" => "record is deleted.");

    return $app->json($return_array);
});

// Listing, search and sort artwork
$app->get('/list/{limit}/{sort_order}/{keyword}', function ($limit,$sort_order,$keyword) use ($app) {

    // Query for artwork with joins
    $query= $app['db']->createQueryBuilder();
    $query->select('*')
      ->from('Artwork', 'aw')
      ->innerJoin('aw', 'Artist', 'a', 'aw.artist = a.id_artist')
      ->innerJoin('a', 'Country', 'c', 'a.country = c.id_country')      
      ->innerJoin('c', 'Category', 'ca', 'aw.category = ca.id_category')      
      ->innerJoin('ca', 'Gallery', 'g', 'aw.gallery = g.id_gallery');
    
    if(is_numeric($limit)) $query->setMaxResults($limit);
    if(in_array(strtolower($sort_order), array("asc","desc"))) $query->orderBy('aw.artwork_price ' , $sort_order);
    if(!empty($keyword))         $query->andWhere("ca.label LIKE '%{$keyword}%' ");
  
$result = $query->execute(); 
$artworks = $result->fetchAll(PDO::FETCH_ASSOC);

// Create final array for artwork
$artwork_array="";
foreach ($artworks as $key=>$val)
{
    $artwork_array[$key]['id_artwork']=$val['id_artwork'];
    $artwork_array[$key]['artwork_title']=$val['artwork_title'];
    $artwork_array[$key]['biography']=$val['biography'];
    $artwork_array[$key]['artwork_year']=date("Y",strtotime($val['artwork_year']));
    $artwork_array[$key]['artwork_price']=$val['artwork_price'];
    $artwork_array[$key]['artwork_dimensions']=$val['artwork_dimensions'];
    $artwork_array[$key]['is_certificated']=$val['is_certificated'];
    $artwork_array[$key]['is_framed']=$val['is_framed'];
    $artwork_array[$key]['is_numbered']=$val['is_numbered'];
    $artwork_array[$key]['artist']['id_artist']=$val['artist'];
    $artwork_array[$key]['artist']['firstname']=$val['firstname'];
    $artwork_array[$key]['artist']['lastname']=$val['lastname'];
    $artwork_array[$key]['artist']['birthday']=date("Y",strtotime($val['birthday']));
    $artwork_array[$key]['artist']['biography']=$val['biography'];
    $artwork_array[$key]['artist']['country']['id_country']=$val['country'];
    $artwork_array[$key]['artist']['country']['label_fr']=$val['label_fr'];
    $artwork_array[$key]['artist']['country']['label_en']=$val['label_en'];
    $artwork_array[$key]['artist']['country']['seo_url_fr']=$val['seo_url_fr'];
    $artwork_array[$key]['artist']['country']['seo_url_en']=$val['seo_url_en'];
    $artwork_array[$key]['category']['id_category']=$val['id_category'];
    $artwork_array[$key]['category']['label']=$val['label'];
    $artwork_array[$key]['category']['seo_url']=$val['seo_url'];
    $artwork_array[$key]['gallery']['id_gallery']=$val['id_gallery'];
    $artwork_array[$key]['gallery']['name']=$val['name'];
    $artwork_array[$key]['gallery']['email']=$val['email'];
    
    
}

$final_result['status']="failure";
$final_result['message']="No record found.";
$final_result['X-Total-Count']=0;
if(!empty($artworks)) 
{
  $final_result['status']="success";
  $final_result['result']=$artwork_array;
  $final_result['X-Total-Count']=count($artwork_array);
  $final_result['message']="";
}
    
 return $app->json($final_result);
  
})->value('limit', FALSE)->value('sort_order', FALSE)->value('keyword', FALSE);


$app->run();
