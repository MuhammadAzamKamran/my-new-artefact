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
    <title>Computer Equipment & Games</title>
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
            <a href="index.php">Home</a>
            <a href="equipment.php">Equipment</a>
            <a href="games.php">Games</a>
            <a href="basket.php">View Basket</a>               
        </ul>
    </nav>
</div>

<div class="container">
        <h2 style="margin-top: 100px;">Computer/Video Games</h2>

       <?php
    $conn = new mysqli("localhost", "root", "", "images_blob");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT name, image_url, price FROM games";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<section class="py-5 bg-light">';
        echo '<div class="container">';
        echo '<div class="row g-4">';
        while ($row = $result->fetch_assoc()) {
            echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">';
            echo '<div class="card h-100 shadow-sm">';
            echo '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\'%3E%3Crect width=\'100%25\' height=\'100%25\' fill=\'%23f0f0f0\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3ELoading...%3C/text%3E%3C/svg%3E" data-src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" class="lazy-image" loading="lazy">';
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

<script>
const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            const src = img.getAttribute('data-src');
            
            if (src) {
                const newImg = new Image();
                newImg.onload = () => {
                    img.src = src;
                    img.classList.add('loaded');
                };
                newImg.onerror = () => {
                    img.src = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\'%3E%3Crect width=\'100%25\' height=\'100%25\' fill=\'%23f5f5f5\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3EImage not found%3C/text%3E%3C/svg%3E';
                    img.classList.add('error');
                };
                newImg.src = src;
                
                img.removeAttribute('data-src');
            }

            observer.unobserve(img);
         }
       });
    },{

       rootMargin: '50px'
});

function observeImages() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    lazyImages.forEach(img => imageObserver.observe(img));
}

document.addEventListener('DOMContentLoaded', observeImages);

</script>

<div class="footer">
        <p>&copy; 2025 Computer Equipment & Games. All rights reserved.</p>
        </div>

</body>
</html>
