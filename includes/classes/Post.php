<?php

class Post {
    private $user_obj;
    private $con;

    public function __construct($con, $user) {
        $this->con = $con;
        $this->user_obj = new User($con, $user);
    }

    public function submitPost($body) {
        $body = strip_tags($body); // Removes HTML tags.
        $body = mysqli_real_escape_string($this->con, $body);

        // Allow line breaks in posts.
        $body = str_replace('\r\n', '\n', $body);
        $body = nl2br($body);

        $check_empty = preg_replace('/\s+/', '', $body); // Deletes all spaces.

        if($check_empty != "") {
            // Current date and time
            $date_added = date("Y-m-d H:i:s");
            
            // Get username
            $added_by = $this->user_obj->getUsername();
            
            // Insert post
            $query = mysqli_query($this->con, "INSERT INTO posts VALUES('', '$body', '$added_by', '$date_added', 'no', '0')");
            $returned_id = mysqli_insert_id($this->con);

            // Insert notification

            //  Update post count for user
            $num_posts = $this->user_obj->getNumPosts();
            $num_posts++;
            $update_query = mysqli_query($this->con, "UPDATE users SET num_posts = '$num_posts' WHERE username='$added_by'");

        }
    }

    public function loadPostsFriends() {
        $str = ""; // String to return.
        $data = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

        while($row = mysqli_fetch_array($data)) {
            $id = $row['id'];
            $body = $row['body'];
            $added_by = $row['added_by'];
            $date_time = $row['date_added'];

            if($row['added_by'] == $added_by) {
                $delete_button = "<button class='delete_button' id='post$id'>X</button>";
            } else {
                $delete_button = "";
            }

            // User details
            $user_details_query = mysqli_query($this->con, "SELECT username FROM users WHERE username='$added_by'");
            $user_row = mysqli_fetch_array($user_details_query);
            $username = $user_row['username'];

            ?>

            <script>
                function toggle<?php echo $id;?>() {
                    var element = document.getElementById("toggleComment<?php echo $id;?>");

                    if(element.style.display == "block") {
                        element.style.display = "none";
                    } else {
                        element.style.display = "block";
                    }
                }
            </script>

            <?php

            $comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
            $comments_check_num = mysqli_num_rows($comments_check);

            // Timeframe
            $date_time_now = date("Y-m-d H:i:s");
            $start_date = new DateTime($date_time); // Time of post created
            $end_date = new DateTime($date_time_now); // Current Time
            $interval = $start_date -> diff($end_date); // Difference between dates
            if($interval->y >= 1) {
                if($interval == 1) {
                    $time_message = $interval->y . " year ago";
                } else {
                    $time_message = $interval->y . " years ago";
                }
            }
            else if($interval->m >= 1) {
                if($interval->d == 0) {
                    $days = " ago";
                } else if($interval->d == 1) {
                    $days = $interval->d . " day ago";
                } else {
                    $days = $interval->d . " days ago";
                }

                if($interval->m == 1) {
                    $time_message = $interval->m . " month" . $days;
                } else {
                    $time_message = $interval->m . " months" . $days;
                }
            }
            else if($interval-> d >= 1) {
                if($interval->d == 1) {
                    $time_message = "Yesterday";
                } else {
                    $time_message = $interval->d . " days ago";
                }
            } else if($interval->h >= 1) {
                if($interval->h == 1) {
                    $time_message = $interval->d . " hour ago";
                } else {
                    $time_message = $interval->d . " hours ago";
                }
            } else if($interval->i >= 1) {
                if($interval->i == 1) {
                    $time_message = $interval->i . " minute ago";
                } else {
                    $time_message = $interval->i . " minutes ago";
                }
            } else {
                if($interval->s < 30) {
                    $time_message = "Just now";
                } else {
                    $time_message = $interval->s . " seconds ago";
                }
            }

            $str .= "
                <div class='status_post' onClick='javascript:toggle$id()'>
                    <div class='posted_by' style='color=#3333CC;'>
                        <a href='$added_by'>$username</a> &nbsp;&nbsp;&nbsp;&nbsp;$time_message
                        $delete_button
                    </div>

                    <div id='post_body'>
                        $body<br>
                    </div>

                    <div class='newsfeedPostOptions'>
                    <br>
                        Comments($comments_check_num)&nbsp;&nbsp;&nbsp;
                        <iframe src='like.php?post_id=$id' scrolling='no'></iframe>
                    </div>
                </div>

                <div class='post_comment' id='toggleComment$id' style='display:none;'>
                    <iframe src='comment_frame.php?post_id=$id' id='comment_frame' frameborder='0'></iframe>
                </div>
                <hr>
            ";

            echo "<script type='text/JavaScript'>$(document).ready(function() {
                $('#post<?php echo $id; ?>').on('click', function() {
                    bootbox.confirm('Delete this post?', function(result) {
                        $.post('includes/handlers/delete_post.php?post_id=<?php echo $id; ?>', {result:result});
                        if(result)
                            location.reload();
                        });
                    });
                });
            </script>";

        } // end of While Loop

        echo $str;
    }
}

?>
