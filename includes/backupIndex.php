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

// $database->query('SELECT * FROM posts WHERE id = 1');//PLAY WITH THIS NUMBER TO GET WHICH POST YOU WANT. OOOHH
$database->query('SELECT * FROM blog_posts');//select all from posts
$rows = $database->resultset();
 ?>
<h1>Add Post</h1>
 <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>"><!--SHORTHAND PHP-->

   <label>Post ID</label><br />
   <input type="text" name="id" placeholder="Specify ID"/><br /><br />

    <label>Post Title</label><br />
    <input type="text" name="title" placeholder="Add a Title..." /><br /><br />

    <label>Post Body</label><br />
    <textarea name="body"></textarea><br /><br />
    <input type="submit" name="submit" value="Submit" />
 </form>
<h1>Posts</h1>
<div>
  <?php foreach($rows as $row) : ?>
    <div>
      <h3><?php echo $row['title']; ?></h3>
      <p>
        <?php echo $row['body']; ?></p>
        <br />
        <form method="post" action="<?php $_SERVER['PHP_SELF'];?>">
          <input type="hidden" name="delete_id" value="<?php echo $row['id'];?>">
          <input type="submit" name="delete" value="Delete"/>
          <form/>
    </div>
  <?php endforeach; ?>
</div>
