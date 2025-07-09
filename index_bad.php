<?php
session_start();

if (!isset($_SESSION['basket'])) {
    $_SESSION['basket'] = array();
}

if (isset($_POST['add_to_basket'])) {
    $item = array(
       'name' => $_POST['item_name'],
       'price' => $_POST['item_price'],
       'image' => $_POST['item_image'],
       'quantity' => 1
    );

    $found = false;
    foreach ($_SESSION['basket'] as $key => $basket_item) {
        if ($basket_item['name'] == $item['name']) {
            $_SESSION['basket'][$key]['quantity']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['basket'][] = $item;
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport">
    <title>Home - Computer Equipment & Games</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" 
    rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
   <link href="https://fonts.googleapis.com/css2?family=Silkscreen:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="header">
    <div class="logo">
        <img src="images/logo.png" width="150px">
    </div>
    <nav>
        <ul>
            <a href="index_bad.php">Home</a>
            <a href="equipment_bad.php">Equipment</a>
            <a href="games_bad.php">Games</a>
             <a href="basket.php">View Basket</a>              
        </ul>
    </nav>
</div>

<section id="home">
        <h1>Find Any Equipment or Games You May Be Looking For!</h1>
        <p>This is the No.1 place for anything computers. Any piece of equipment can be found here. Monitors, Speakers, CPUs. We've got it all. We also have a plethora of games here as we understand the importance of preserving games, old & new.</p>
        <a href="equipment_bad.php" class="cta-button">Shop For Equipment</a>
        <a href="games_bad.php" class="cta-button">Shop For Games</a>
    </section>

    <div>
        <h2>Why Choose Us?</h2>
        <p>We're the UK's leading supplier of computer equipment and games!</p>
    </div>

    <section id="features">
        <div class="feature-grid">
            <div class="feature-card">
                <h3 class="feature-title1">Free Delivery</h3>
                <p class="feature-description1">All deliveries over £30 will be free!</p>
            </div>
                <div class="feature-card">
                    <h3 class="feature-title2">Guaranteed Warranty</h3>
                    <p class="feature-description2">Anything sold from us will have a warranty so you won't have to worry if a product doesn't work as intended.</p>
                </div>
                <div class="feature-card">
                    <h3 class="feature-title3">24/7 Support</h3>
                    <p class="feature-description3">Support will always be available if you have any questions or need. If a person isn't available, an AI chatbot will be there instead.</p>
                </div>
                <div class="feature-card">
                    <h3 class="feature-title4">An Array of Games Across All Areas Available</h3>
                    <p class="feature-description4">All kinds of games over the years are available for purchase here. Whether you're a fan of the classics or are looking for some new you missed out on, there's bound to be something that'll catch your attention.</p>
                </div>
            </div>
    </section>

   <div class="container">
        <h2>Customer Favourites</h2>

       <?php
    $conn = new mysqli("localhost", "root", "", "images_bad");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT name, image_url, price FROM image";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<section class="py-5 bg-light">';
        echo '<div class="container">';
        echo '<div class="row g-4">';
        while ($row = $result->fetch_assoc()) {
            echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">';
            echo '<div class="card h-100 shadow-sm">';
            echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" class="img-fluid" style="max-width: 200px; max-height: 200px;">';
            echo '<div class="card-body d-flex flex-column">';
            echo '<h5 class= "card-title">' . htmlspecialchars($row['name']) . '</h5>';
            echo '<p class="h5 text-success mb-2">£' . number_format($row['price'], 2) . '</p>';
            echo '<form method="POST" class="mt-auto">';
            echo '<input type="hidden" name="item_name" value="' . htmlspecialchars($row['name']) . '">';
            echo '<input type="hidden" name="item_price" value="' . $row['price'] . '">';
            echo '<input type="hidden" name="item_image" value="' . htmlspecialchars($row['image_url']) . '">';
            echo '<input type="hidden" name="add_to_basket" value="1">';
            echo '<button class="btn btn-success">Add to Basket</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</section>';

    } else {
        echo "<p>No images found.</p>";
    }
    $conn->close();
    ?>
</div>

<div class="container">
        <h2>Demonstration Video</h2>

         <?php
    $conn = new mysqli("localhost", "root", "", "images_bad");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql2 = "SELECT title, video_path FROM video";
    $result = $conn->query($sql2);

    $result->data_seek(0);
    $video = $result->fetch_assoc();
    
?>


  <video controls width="640" height="480">
  <source src="<?php echo htmlspecialchars($video['video_path']); ?>" type="video/mp4">
    Your browser does not support the video tag.
</video>
</div>


<div class="footer">
    <p>&copy; 2025 Computer Equipment & Games. All rights reserved.</p>
    </div>

</body>
</html>
