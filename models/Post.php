<?php 
class Post
{
    //DB stuff
    private $conn ;
    private $table = 'posts';

    //post properties
    public $id ;
    public $category_id ;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at; 

    //constructor 
    public function __construct ($db)
    {
        $this->conn = $db;
    }

    //get Posts 
    public function read()
    {
        //create query 
        $query = 'SELECT c.name AS category_name , p.id , p.category_id , p.title, p.body , p.author , p.created_at
        FROM '.$this->table.' p
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC';

       

        //prepare statement 
        $stmt = $this->conn->prepare($query);

        // execute query 
        $stmt->execute();

        return $stmt;
    }

    //Get single post 
    public function read_single()
    {
        //create query 
        $query = 'SELECT c.name AS category_name , p.id , p.category_id , p.title, p.body , p.author , p.created_at
        FROM '.$this->table.' p
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.id = ? 
        LIMIT 0 , 1 ';

        //prepare statement 
        $stmt = $this->conn->prepare($query);

        //Bind ID 
        $stmt->bindParam(1 , $this->id);

        //execute query 
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //set properties 
        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];


    }

    //create post 
    public function create()
    {
        //create query 
        $query = 'INSERT INTO '.$this->table.'
                  SET 
                        title =:title,
                        body =:body,
                        author =:author,
                        category_id =:category_id';
    
        //prepare statement 
        $stmt = $this->conn->prepare($query);

        //clean data 
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
       
        //Bind data 

        $stmt->bindparam(':title' , $this->title );
        $stmt->bindparam(':body' , $this->body );
        $stmt->bindparam(':author' , $this->author );
        $stmt->bindparam(':category_id' , $this->category_id );
        
        //execute query \
        if ($stmt->execute())
        {
            return true ;
        }

        //print error if something went wrong
        printf("Error: %s.\n" , $stmt->error);

        return false;
    }

    // update post 
    public function update()
    {
        //update query 
        $query = 'UPDATE '. $this->table.'
                    SET 
                        title=:title,
                        body=:body,
                        author=:author,
                        category_id=:category_id
                        WHERE 
                        id = :id';
        //prepare statement
        $stmt = $this->conn->prepare($query);

        //clean data 
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        //Bind param 
        $stmt->bindparam(':title' , $this->title);
        $stmt->bindparam(':body' , $this->body);
        $stmt->bindparam(':author' , $this->author );
        $stmt->bindparam(':category_id' , $this->category_id );
        $stmt->bindparam(':id' , $this->id );

        //execute query 
        if ($stmt->execute())
        {
            return true;
        }
        //print error if smth went wrong 
        printf("Error: %s.\n" , $stmt->error);

        return false ;
     
    }

    //Delete post 
    public function delete()
    {
        //Query 
        $query = 'DELETE FROM '.$this->table.'
                    WHERE id= :id ';


        //Prepare statement 
        $stmt = $this->conn->prepare($query);

        //clean data 
        $this->id = htmlspecialchars(strip_tags($this->id));

        //Bind param
        $stmt->bindparam(':id' , $this->id);

        //execute query 
        if ($stmt->execute())
        {
            return true;
        }
        //print error if smth went wrong 
        printf("Error: %s.\n" , $stmt->error);

        return false ;

    }

}