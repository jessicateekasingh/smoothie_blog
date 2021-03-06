<?php
$dbconn = pg_connect("host=localhost dbname=grapevine user=bloggres");

class Blog {
    public $id;
    public $author;
    public $image;
    public $content;
    public $snippet;
    public $created_at;
    public $is_featured;

    public function __construct($id, $author, $image, $content, $snippet, $created_at, $is_featured){
        $this->id = $id;
        $this->author = $author;
        $this->image = $image;
        $this->content = $content;
        $this->snippet = $snippet;
        $this->created_at = $created_at;
        $this->is_featured = $is_featured;
    }
}

class Blogs {
    static function all(){
        $blogs = array();

        $results = pg_query("SELECT * FROM blogs");

        $row_object = pg_fetch_object($results);
        while($row_object){
            $new_blog = new Blog(
                intval($row_object->id),
                $row_object->author,
                $row_object->image,
                $row_object->content,
                $row_object->snippet,
                $row_object->created_at,
                $row_object->is_featured
            );
            $blogs[] = $new_blog;
            $row_object = pg_fetch_object($results);
        }
        return $blogs;
    }

    static function create($blog){
        $query = "INSERT INTO blogs (author, image, content, snippet, created_at, is_featured) VALUES ($1, $2, $3, $3, $4, $5, $6)";
        $query_params = array($blog->author, $blog->image, $blog->content, $blog->snippet, $blog->created_at, $blog->is_featured);
        pg_query_params($query, $query_params);
        return self::all();
    }

    static function update($updated_blog){
        $query = "UPDATE blogs SET author = $1, image = $2, content = $3, snippet = $4, created_at = $5, is_featured = $6 WHERE id = $7";
        $query_params = array($updated_blog->author, $updated_blog->image, $updated_blog->content, $updated_blog->snippet, $updated_blog->created_at, $updated_blog->is_featured, $updated_blog->id);
        $result = pg_query_params($query, $query_params);

        return self::all();
    }
    static function delete($id){
        $query = "DELETE FROM blogs WHERE id = $1";
        $query_params = array($id);
        $result = pg_query_params($query, $query_params);

        return self::all();
    }
}

?>
