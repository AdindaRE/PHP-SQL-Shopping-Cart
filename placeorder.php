<?php 
    $first_name = htmlspecialchars($_SESSION['first_name']);
    $last_name = htmlspecialchars($_SESSION['last_name']);
    $email = htmlspecialchars($_SESSION['email']);
    $phone = htmlspecialchars($_SESSION['phone']);
    $street = htmlspecialchars($_SESSION['street']);
    $street_number = htmlspecialchars($_SESSION['street_number']);
    $postal_code = htmlspecialchars($_SESSION['postal_code']);
    $city = htmlspecialchars($_SESSION['city']);

    // Save the order details to the database

    $query = "INSERT INTO client (first_name, last_name, email, phone, street, street_number, postal_code, city) 
    VALUES ('$first_name', '$last_name', '$email', '$phone', '$street', '$street_number', '$postal_code', '$city')";

    echo $query;
    
    $stmt = $pdo->prepare($query);
    $success = $stmt->execute();

    if(success) echo "YAY";
    else echo "FAIL";

    // Fetching the client in the order

    if (isset($_GET['id']) && !empty($_GET['id']) ) {
        echo "WORKS?";
        $stmt = $pdo->prepare('SELECT * FROM client WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$client) {
            echo "nope";
            exit('Product does not exist!');
        }
    } else {
        echo "Fail";
    }

      // Fetching the product in the order

      if (isset($_GET['id']) && !empty($_GET['id']) ) {
        echo "WORKS?";
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$client) {
            echo "nope";
            exit('Product does not exist!');
        }
    } else {
        echo "Fail";
    }
    
    // Saving the product in the order

    $id = $_POST['id'];
    $query = "INSERT INTO orders_products (order_id, product_id) 
    SELECT orders.id, products.id FROM orders, products";
    $pdo->execute($query);

?>

<?=template_header('Place Order')?>

<div class="placeorder content-wrapper">
    <h1>Your Order Has Been Placed</h1>
    <p>Thank you for ordering with us! We'll contact you by email with your order details.</p>
</div>

<?=template_footer()?>


