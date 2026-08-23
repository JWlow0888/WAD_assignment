<?php include('../includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>GameGear Exchange - Item Details</title>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <div id="contentWrapper">

        <?php
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<p>No item selected. <a href='../listings/index.php'>Browse listings</a></p>";
        } else {
            $id = $_GET['id'];

            // ---- View counter: +1 every time this page loads ----
            $stmt = mysqli_prepare($conn, "UPDATE listings SET views = views + 1 WHERE listing_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // ---- READ: fetch item + category name + seller info ----
            $stmt = mysqli_prepare($conn, "SELECT listings.*, categories.category_name, users.full_name, users.email
                                            FROM listings
                                            JOIN categories ON listings.category_id = categories.category_id
                                            JOIN users ON listings.seller_id = users.user_id
                                            WHERE listings.listing_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $item = mysqli_fetch_assoc($result);
                ?>
                <article id="itemDetails">
                    <h1><?php echo htmlspecialchars($item['title']); ?></h1>

                    <?php if ($item['image_path'] != "") { ?>
                        <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" width="400">
                    <?php } ?>

                    <p class="price">RM <?php echo number_format($item['price'], 2); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category_name']); ?></p>
                    <p><strong>Condition:</strong> <?php echo htmlspecialchars($item['item_condition']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($item['status']); ?></p>
                    <p><strong>Views:</strong> <?php echo $item['views']; ?></p>
                    <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                    <p><strong>Seller:</strong> <?php echo htmlspecialchars($item['full_name']); ?> (<?php echo htmlspecialchars($item['email']); ?>)</p>
                    <p><strong>Posted on:</strong> <?php echo date("d M Y", strtotime($item['created_at'])); ?></p>

                    <a href="../listings/edit.php?id=<?php echo $item['listing_id']; ?>" class="button">Edit Listing</a>
                    <a href="../listings/index.php?delete=<?php echo $item['listing_id']; ?>" class="button" onclick="return confirm('Delete this listing?')">Delete Listing</a>
                </article>


                <section id="relatedListings">
                    <h2>Related Listings</h2>
                    <?php
                    $stmt2 = mysqli_prepare($conn, "SELECT * FROM listings WHERE category_id = ? AND listing_id != ? LIMIT 3");
                    mysqli_stmt_bind_param($stmt2, "ii", $item['category_id'], $id);
                    mysqli_stmt_execute($stmt2);
                    $related_result = mysqli_stmt_get_result($stmt2);

                    if (mysqli_num_rows($related_result) > 0) {
                        while ($rel = mysqli_fetch_assoc($related_result)) {
                            echo "<div class='listingCard'>";
                            if ($rel['image_path'] != "") {
                                echo "<img src='../" . htmlspecialchars($rel['image_path']) . "' alt='" . htmlspecialchars($rel['title']) . "'>";
                            }
                            echo "<h3>" . htmlspecialchars($rel['title']) . "</h3>";
                            echo "<p class='price'>RM " . number_format($rel['price'], 2) . "</p>";
                            echo "<a href='index.php?id=" . $rel['listing_id'] . "'>View Details</a>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No other items in this category yet.</p>";
                    }
                    mysqli_stmt_close($stmt2);
                    ?>
                </section>

                <section id="commentsSection">
                    <h2>Comments</h2>

                    <?php
                    $comment_errors = array();
                    $comment_success = "";

                    // CREATE: new comment
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_comment'])) {
                        if (trim($_POST['commenter_name']) == "") {
                            $comment_errors[] = "Please enter your name.";
                        }
                        if (trim($_POST['comment_text']) == "") {
                            $comment_errors[] = "Comment cannot be empty.";
                        }

                        if (empty($comment_errors)) {
                            $commenter_name = trim($_POST['commenter_name']);
                            $comment_text   = trim($_POST['comment_text']);

                            $stmt3 = mysqli_prepare($conn, "INSERT INTO comments (listing_id, commenter_name, comment_text) VALUES (?, ?, ?)");
                            mysqli_stmt_bind_param($stmt3, "iss", $id, $commenter_name, $comment_text);

                            if (mysqli_stmt_execute($stmt3)) {
                                $comment_success = "Comment posted!";
                            } else {
                                $comment_errors[] = "Error posting comment: " . mysqli_error($conn);
                            }
                            mysqli_stmt_close($stmt3);
                        }
                    }

                    // DELETE: remove a comment
                    if (isset($_GET['delete_comment']) && is_numeric($_GET['delete_comment'])) {
                        $comment_id = $_GET['delete_comment'];
                        $stmt4 = mysqli_prepare($conn, "DELETE FROM comments WHERE comment_id = ?");
                        mysqli_stmt_bind_param($stmt4, "i", $comment_id);
                        mysqli_stmt_execute($stmt4);
                        mysqli_stmt_close($stmt4);
                        $comment_success = "Comment deleted.";
                    }

                    if (!empty($comment_errors)) {
                        foreach ($comment_errors as $err) {
                            echo "<p class='error-msg'>" . htmlspecialchars($err) . "</p>";
                        }
                    }
                    if ($comment_success != "") {
                        echo "<p class='success-msg'>" . htmlspecialchars($comment_success) . "</p>";
                    }
                    ?>

                    <!-- New comment form -->
                    <form action="index.php?id=<?php echo $id; ?>" method="post" id="commentForm">
                        <label for="commenter_name">Your Name:</label>
                        <input type="text" name="commenter_name" id="commenter_name" required>

                        <label for="comment_text">Comment:</label>
                        <textarea name="comment_text" id="comment_text" rows="2" required></textarea>

                        <button type="submit" name="add_comment" class="button">Post Comment</button>
                    </form>

                    <!-- READ: list existing comments -->
                    <?php
                    $stmt5 = mysqli_prepare($conn, "SELECT * FROM comments WHERE listing_id = ? ORDER BY created_at DESC");
                    mysqli_stmt_bind_param($stmt5, "i", $id);
                    mysqli_stmt_execute($stmt5);
                    $comments_result = mysqli_stmt_get_result($stmt5);

                    if (mysqli_num_rows($comments_result) > 0) {
                        while ($comment = mysqli_fetch_assoc($comments_result)) {
                            echo "<div class='commentCard'>";
                            echo "<p class='commenterName'>" . htmlspecialchars($comment['commenter_name']) . "</p>";
                            echo "<p>" . nl2br(htmlspecialchars($comment['comment_text'])) . "</p>";
                            echo "<p class='commentDate'>" . date("d M Y, g:i A", strtotime($comment['created_at'])) . "</p>";
                            echo "<a href='index.php?id=" . $id . "&delete_comment=" . $comment['comment_id'] . "' onclick=\"return confirm('Delete this comment?')\">Delete</a>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No comments yet. Be the first to ask a question!</p>";
                    }
                    mysqli_stmt_close($stmt5);
                    ?>
                </section>
                <?php
            } else {
                echo "<p>Item not found.</p>";
            }
        }
        ?>

    </div>

    <?php include('../content/footer.php'); ?>
</body>
</html>
