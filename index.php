<?php
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");

if (isset($_POST['post'])) {
    $post = new Post($con, $userLoggedIn);
    $post->submitPost($_POST['post_text']);
    header("Location: index.php");
}
?>

<div class=row>
    <div class="column" id="user_details">
        <!-- Displays Username -->
        Hello, <b>
        <a href=<?php echo "$userLoggedIn";?>>
            <?php echo $user['username'];?>
        </a>
        </b>!
        <br><br>

        <!-- Displays number of posts and likes -->
        <?php
        echo "Posts: " . $user['num_posts'];
        echo "<br>";
        echo "Likes: " . $user['num_likes'];
        ?>
        <br><br>
    </div>

    <div class="column" id="main_column">
        <form class="post_form" action="index.php" method="POST">
            <textarea name="post_text" id="post_text" placeholder="Post something here!"></textarea>
            <input type="submit" name="post" id="post_button" value="Post">
            <hr>
        </form>

        <?php
        $post = new Post($con, $userLoggedIn);
        $post->loadPostsFriends();
        ?>
    </div>

</div>

</body>
</html>