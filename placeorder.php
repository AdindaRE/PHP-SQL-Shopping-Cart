<?php 
    $first_name = htmlspecialchars($_SESSION['first_name']);
    $last_name = htmlspecialchars($_SESSION['last_name']);
    $email = htmlspecialchars($_SESSION['email']);
    $phone = htmlspecialchars($_SESSION['phone']);
    $street = htmlspecialchars($_SESSION['street']);
    $street_number = htmlspecialchars($_SESSION['street_number']);
    $postal_code = htmlspecialchars($_SESSION['postal_code']);
    $city = htmlspecialchars($_SESSION['city']);

    // Save the client details to the 'client' table
    $query = "INSERT INTO client (first_name, last_name, email, phone, street, street_number, postal_code, city) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$first_name, $last_name, $email, $phone, $street, $street_number, $postal_code, $city]);

    // Get the last inserted client ID
    $client_id = $pdo->lastInsertId();

    // Fetch the client from the database
    $stmt = $pdo->prepare('SELECT * FROM client WHERE id = ?');
    $stmt->execute([$client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$client) {
        exit('Failed to fetch client details!');
    }

    // Fetching the products in the order
    $products_in_cart = $_SESSION['cart'];
    $product_ids = array_keys($products_in_cart);
    
    // Select the products from the database based on the cart items
    $placeholders = rtrim(str_repeat('?,', count($product_ids)), ',');
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($product_ids);
    
    // Fetch the products from the database
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Save the products in the order
    foreach ($products as $product) {
        $product_id = $product['id'];
        $query = "INSERT INTO orders_products (order_id, product_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$client_id, $product_id]);
    }

    // Save the order details to the 'orders' table
    $order_time = date('Y-m-d H:i:s');
    $query = "INSERT INTO orders (client, order_time) VALUES (?, ?)";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$client_id, $order_time]);

    // Get the last inserted order ID
    $order_id = $pdo->lastInsertId();

    // Fetch the order from the database
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        exit('Failed to fetch order details!');
    }

     // Clear the cart by emptying the session variable
     $_SESSION['cart'] = array();
?>

<?=template_header('Place Order')?>

<div class="placeorder content-wrapper">
    <h1>Your Order Has Been Placed</h1>
    <p>Thank you for ordering with us! We'll contact you by email with your order details.</p>
</div>

<?=template_footer()?>
