<?php
// Get the 4 most recently added products
$stmt = $pdo->prepare('SELECT * FROM products ORDER BY date_added DESC LIMIT 4');
$stmt->execute();
$recently_added_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the list of categories
$stmt = $pdo->prepare('SELECT * FROM category');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve selected category value
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare the SQL statement based on the selected category
$sql = 'SELECT * FROM products';
$params = [];

if ($selectedCategory) {
  $sql .= ' WHERE category_id = (SELECT id FROM category WHERE name = ?)';
  $params[] = $selectedCategory;
}

$sql .= ' ORDER BY date_added DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Fetch the products from the database
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header('Home')?>

<div class="featured">
    <h2>Tropical Summer</h2>
    <p>Stylish swimwear for summer</p>
</div>
<div class="category-filter content-wrapper">
    <!-- Category filter form -->
    <form action="" method="get">
      <label for="category">Filter by Category:</label>
      <select name="category" id="category">
        <option value="">Recently Added</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= $category['name'] ?>" <?= ($selectedCategory == $category['name']) ? 'selected' : '' ?>>
            <?= $category['name'] ?>
          </option>
        <?php endforeach; ?>
      </select>
      <input type="submit" value="Filter">
    </form>
</div>
<div class="recentlyadded content-wrapper">
    <h2>Recently Added Products</h2>
    <div class="products">
        <?php foreach ($products as $product): ?>
        <a href="index.php?page=product&id=<?=$product['id']?>" class="product">
            <img src="imgs/<?=$product['image']?>" width="200" height="250" alt="<?=$product['name']?>">
            <span class="name"><?=$product['name']?></span>
            <span class="price">
                &dollar;<?=$product['price']?>
            </span>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<style>
  .category-filter.content-wrapper {
  padding: 20px;
  background-color: #FFFFFF;
  border: 1px solid #EEEEEE;
  margin-bottom: 20px;
}

.category-filter label {
  font-weight: bold;
  color: #013220;
  margin-right: 10px;
}

.category-filter select {
  padding: 5px;
  font-size: 16px;
  color: #013220;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.category-filter input[type="submit"] {
  background-color: #63748e;
  border: none;
  color: #FFFFFF;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
}

.category-filter input[type="submit"]:hover {
  background-color: #4e5c70;
}

</style>

<?=template_footer()?>

