<?php 
class Product{
  //database connection and table name
  private $conn;
  private $table_name = 'products';

  //object properties
  public $id;
  public $name;
  public $description;
  public $price;
  public $category_id;
  public $category_name;
  public $created;

  //construct with $db as database connection
  public function __construct($db){
    $this->conn = $db;
  }
  // read products
function read(){
    // select all query
    $query = "SELECT
                categories.name as category_name, products.id, products.name, products.description, products.price, products.category_id, products.created
            FROM
                " . $this->table_name . "
                LEFT JOIN
                    categories 
                        ON products.category_id = categories.id
            ORDER BY
                products.created DESC";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}
// create product
function create(){
 
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, price=:price,description=:description category_id=:category_id, created=:created";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
    $this->created=htmlspecialchars(strip_tags($this->created));
 
    // bind values
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":price", $this->price);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":category_id", $this->category_id);
    $stmt->bindParam(":created", $this->created);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}


function readOne(){
    $query = "SELECT categories.name as category_name,products.id, products.name, products.description, products.price, products.category_id,products.created FROM ".$this->table_name." LEFT JOIN categories ON products.category_id = categories.id WHERE products.id = ? LIMIT 0,1";

    $stmt = $this->conn->prepare($query);
    //binding that ? parameter as integer type
    $stmt->bindParam(1,$this->id);
    //run that query
    $stmt->execute();
    //get that query data
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //for return or futher use that data
    //store them in public variable
    // set values to object properties
    $this->name = $row['name'];
    $this->price = $row['price'];
    $this->description = $row['description'];
    $this->category_id = $row['category_id'];
    $this->category_name = $row['category_name'];
}



function update(){
 
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                name = :name,
                price = :price,
                description = :description,
                category_id = :category_id
            WHERE
                id = :id";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->price=htmlspecialchars(strip_tags($this->price));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind new values
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':price', $this->price);
    $stmt->bindParam(':description', $this->description);
    $stmt->bindParam(':category_id', $this->category_id);
    $stmt->bindParam(':id', $this->id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}



// delete the product
function delete(){
 
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}

// search products
function search($keywords){
 
    // select all query
    $query = "SELECT
                categories.name as category_name, products.id, products.name, products.description, products.price, products.category_id, products.created
            FROM
                " . $this->table_name . "
                LEFT JOIN
                    categories 
                        ON products.category_id = categories.id
            WHERE
                products.name LIKE ? OR products.description LIKE ? OR categories.name LIKE ?
            ORDER BY
                products.created DESC";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";
 
    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

}
?>