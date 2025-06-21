<?php
session_start();

if (!isset($_SESSION['basket'])) {
    $_SESSION['basket'] = array();
}

if(isset($_GET['cleanup'])) {
    $_SESSION['basket'] = array_filter($_SESSION['basket'], function($item) {
        return !empty($item['name']) && $item['price'] > 0;
    });
    $_SESSION['basket'] = array_values($_SESSION['basket']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['update_quantity'])) {
    $item_name = $_POST['item_name'];
    $action = $_POST['action'];

    foreach ($_SESSION['basket'] as $key => $item) {
        if ($item['name'] == $item_name) {
            if ($action == 'increase') {
                $_SESSION['basket'][$key]['quantity']++;
            } elseif ($action == 'decrease') {
                $_SESSION['basket'][$key]['quantity']--;
                if ($_SESSION['basket'][$key]['quantity'] <= 0) {
                    unset($_SESSION['basket'][$key]);
                    $_SESSION['basket'] = array_values($_SESSION['basket']);        
            }
        }
        break;
    }
}
 header("Location: " . $_SERVER['PHP_SELF']);
 exit;
}

if (isset($_POST['remove_item'])) {
    $item_name = $_POST['item_name'];
    foreach ($_SESSION['basket'] as $key => $item) {
        if ($item['name'] == $item_name) {
            unset($_SESSION['basket'][$key]);
            break;
        }
    }

    $_SESSION['basket'] = array_values($_SESSION['basket']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['clear_basket'])) {
    $_SESSION['basket'] = array();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$total = 0;
$total_items = 0;
if (isset($_SESSION['basket'])) {
    foreach ($_SESSION['basket'] as $item) {
        $total += ($item['price'] * $item['quantity']);
        $total_items += $item['quantity'];
    }
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
            <a href="basket.html">View Basket</a>            
        </ul>
    </nav>
</div>

                    <script>
                    if (document.referrer && document.referrer.includes(window.location.hostname)) {
                        const referrerPage = document.referrer;
                        const currentStoredPage = sessionStorage.getItem('shopping_page');

                        if (referrerPage.includes('equipment.php') || 
                            referrerPage.includes('games.php') || 
                            referrerPage.includes('index.php')) {
                            sessionStorage.setItem('shopping_page', referrerPage);
                        }
                    }
                    
                    window.goBackToShopping = function() {
                        const lastPage = sessionStorage.getItem('shopping_page');
                        if (lastPage && !lastPage.includes('basket.php')) {
                            window.location.href = lastPage;
                        } else {
                            window.location.href = 'index.php';
                        }
                    }
                    </script>

<div class="container mt-4">
        <div class="text-center mb-4">
            <h2 class="text-success">YOUR SHOPPING BASKET</h2>
        </div>

        <?php if (empty($_SESSION['basket'])): ?>
            <div class="text-center py-5">
                <h4>Your basket is empty</h4>
                <p class="text-muted">Add some items to your basket to see them here.</p>
                <button onclick="goBackToShopping()" class="btn btn-success">Continue Shopping</button>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <?php foreach ($_SESSION['basket'] as $item): ?>
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-2">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                         class="img-fluid rounded-start h-100" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                                         style="object-fit: cover; min-height: 120px;">
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-5">
                                    <div class="card-body">
                                        <h5 class="card-title text-success"><?php echo htmlspecialchars($item['name']); ?></h5>
                                        <p class="card-text h5 text-success">£<?php echo number_format($item['price'], 2); ?></p>
                                        <p class="card-text text-muted">Subtotal: £<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card-body">
                                        <label class="form-label">Quantity:</label>
                                        <div class="d-flex align-items-center">
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="item_name" value="<?php echo $item['name']; ?>">
                                                <input type="hidden" name="action" value="decrease">
                                                <button type="submit" name="update_quantity" class="btn btn-outline-secondary btn-sm">-</button>
                                            </form>
                                            <span class="mx-2 fw-bold"><?php echo $item['quantity']; ?></span>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="item_name" value="<?php echo $item['name']; ?>">
                                                <input type="hidden" name="action" value="increase">
                                                <button type="submit" name="update_quantity" class="btn btn-outline-secondary btn-sm">+</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card-body text-end">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="item_name" value="<?php echo $item['name']; ?>">
                                            <button type="submit" name="remove_item" class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span>Items (<?php echo $total_items; ?>):</span>
                                <span>£<?php echo number_format($total, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Delivery:</span>
                                <span>FREE</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between h5">
                                <strong>Total:</strong>
                                <strong class="text-success">£<?php echo number_format($total, 2); ?></strong>
                            </div>
                            <button class="btn btn-success w-100 mb-2">Proceed to Checkout</button>
                            <form method="POST" class="d-inline w-100">
                                <button type="submit" name="clear_basket" class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Are you sure you want to clear your basket?')">
                                    Clear Basket
                                </button>
                            </form>
                        </div>
                    </div>

                    <button onclick="goBackToShopping()" class="btn btn-outline-success w-100">
                        Continue Shopping
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>





<div class="footer">
    <p>&copy; 2025 Computer Equipment & Games. All rights reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
