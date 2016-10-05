<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Blog</title>
    <link rel="stylesheet" type="text/css" href="includes/styles.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  </head>
  <body>
<!-- made html just so I could connect css -->
  </body>
</html>
<?php
require 'includes/includes.php';
$database = new Database;

$database->query('SELECT * FROM blog_posts');

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
// $database->query('SELECT * FROM posts');
// $rows = $database->resultset();
// print_r($rows);

if(@$_POST['delete']){//superglobal
  $delete_id = $_POST['delete_id'];
  $database->query('DELETE FROM blog_posts WHERE id = :id');
  $database->bind(':id', $delete_id);//points to delete
  $database->execute();
}

if(@$post['update']){//declared variable
  $id = $post['id'];
  $title = $post['title'];
  $body = $post['body'];

$database->query('UPDATE blog_posts SET title = :title, body = :body WHERE id = :id');
$database->bind(':title', $title);
$database->bind(':body', $body);
$database->bind(':id', $id);
$database->execute();
}

if(@$post['submit']){//@suppresses warning that there is no array key to update in the post
  $title = $post['title'];
  $body = $post['body'];

$database->query('INSERT INTO blog_posts (title, body) VALUES(:title, :body)');
$database->bind(':title', $title);
$database->bind(':body', $body);
$database->execute();
if($database->lastInsertId()){
  echo '<p>Post Added!</p>';
}
}

//JOIN HAPPENS HERE
// $database->query('SELECT blog_posts.tags, blog_posts_tags.join_tags FROM blog_posts LEFT JOIN blog_posts_tags ON blog_posts.id = blog_posts_tags.blog_post_id');
// $database->query('SELECT blog_posts.tags, blog_posts_tags.join_tags FROM blog_posts LEFT JOIN blog_posts_tags ON blog_posts.id = blog_posts_tags.blog_post_id');


//* means all
///////////

// $database->query('SELECT * FROM posts WHERE id = 1');//PLAY WITH THIS NUMBER TO GET WHICH POST YOU WANT. OOOHH
$database->query('SELECT * FROM blog_posts');//select all from posts
$rows = $database->resultset();
 ?>
 <div class="container">
   <div class="row-header">
<h1>Add Post</h1>
  </div>
 <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>"><!--SHORTHAND PHP-->

   <label>Post ID</label><br />
   <input type="text" name="id" placeholder="Specify ID"/><br /><br />

    <label>Post Title</label><br />
    <input type="text" name="title" placeholder="Add a Title..." /><br /><br />

    <label>Post Body</label><br />
    <textarea name="body"></textarea><br /><br />
    <input type="submit" name="submit" value="Submit" />
 </form>
<h1 style = "color:white">Posts</h1>

<div id="postfeed">
  
  <?php foreach($rows as $row) : ?>
  <?php echo '<div class="row col-lg-7">';?>
      <h3><?php echo $row['title']; ?></h3>
      <p>
        <?php
        $database->query('SELECT name FROM tags LEFT JOIN blog_post_tags ON tags.id = blog_post_tags.tag_id WHERE blog_post_tags.post_id = :inId');
                                                                                        //look at this row
        $database->bind(':inId', $row['id']);

        $tagName = $database->resultset();
        echo "Tags: " ;
        foreach($tagName as $name) {
          echo $name['name'] . ", ";
        }
         ?></p>
        <br />
      <p>
        <?php echo $row['body']; ?></p>
        <br />
        <form method="post" action="<?php $_SERVER['PHP_SELF'];?>">
          <input type="hidden" name="delete_id" value="<?php echo $row['id'];?>">
          <input class="btn btn-info"type="submit" name="delete" value="Delete"/>
          <form/>
    <?php '</div>' ?>
  </div>
  <?php endforeach; ?>
  <div class="row col-lg-4 col-lg-push-2" id="twitterfeed">
    <a class="twitter-timeline" href="https://twitter.com/thenetninjauk">Tweets by thenetninjauk</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
  </div>

</div>
<!-- container -->
