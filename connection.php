<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$database_name = 'restaurant_db';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menuItems = [
        'A' => 'Paneer Tikka Masala',
        'B' => 'Veggie Korma',
        'C' => 'Dal Makhani',
        'D' => 'Palak Paneer',
        'E' => 'Chicken Biryani',
        'F' => 'Butter Chicken',
        'G' => 'Fish Curry',
        'H' => 'Mutton Rogan Josh',
        'I' => 'Tandoori Roti',
        'J' => 'Butter Naan',
        'K' => 'Garlic Naan',
        'L' => 'Plain Paratha',
        'M' => 'Aloo Paratha',
        'N' => 'Paneer Paratha',
        'O' => 'Missi Roti',
        'P' => 'Roomali Roti'
    ];

    $orders = [];
    $totalPrice = 0;

    foreach ($menuItems as $id => $name) {
        if (isset($_POST[$id]) && $_POST[$id] > 0) {
            $quantity = $_POST[$id];
            $price = $_POST[strtolower($id)];
            $orders[] = "$name [$quantity]";
            $totalPrice += $price;
        }
    }

    // Convert orders to string
    $ordersString = implode(", ", $orders);

    // Define CSV file path
    $csvFilePath = 'orders.csv';

    // Open the CSV file in append mode
    $csvFile = fopen($csvFilePath, 'a');

    // Check if file is empty, if yes, add headings
    if (filesize($csvFilePath) == 0) {
        fputcsv($csvFile, ['Orders', 'Total Price']);
    }

    // Write data to CSV file
    fputcsv($csvFile, [$ordersString, $totalPrice]);

    // Close CSV file
    fclose($csvFile);

    // Insert data into the database
    $sql_query = "INSERT INTO orders (`orders`, `total_price`) VALUES ('$ordersString', '$totalPrice')";

    if (mysqli_query($conn, $sql_query)) {
        // Success message displayed using JavaScript alert
        echo "<script>alert('Order Placed Successfully');</script>";
        // Redirect to the menu page after successful submission
        echo "<script>window.location.href = 'menu.html';</script>";
        exit();
    } else {
        echo "Error: " . $sql_query . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>
