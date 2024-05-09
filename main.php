<?php

//import objects from products.json
$fileName = 'products.json';
$data = file_get_contents($fileName);
$products = json_decode($data);

$person = new stdClass();
$person->name = "John Doe";
$person->wallet = 1500;


function displayProducts($products)
{
    foreach ($products as $index => $product) {
        if ($index > 0 && $index % 4 == 0) {
            echo PHP_EOL;
        }
        echo "[$index] $product->Name for ";
        echo "$" . number_format($product->price / 100, 2) . "  ";
    }

}

$shoppingCart = [];
$sum = array_sum(array_column($shoppingCart, "price"));
$keepShopping = true;

echo "Welcome to Narvesen!\n";

while ($keepShopping) {
    echo PHP_EOL;
    displayProducts($products);
    echo PHP_EOL;
    $selectedProduct = readline("Select a product: ");
    if (!isset($products[$selectedProduct])) {
        echo 'Enter valid product number!' . PHP_EOL;
        continue;
    }

    $itemAmount = (int)readline('Enter amount: ');
    echo PHP_EOL;
    if ($itemAmount == 0) {
        echo "Enter valid amount!" . PHP_EOL;
        continue;
    }

    if ($itemAmount > $products[$selectedProduct]->stock) {
        echo "Can't buy that amount of {$products[$selectedProduct]->Name}" . PHP_EOL;
        echo "There are only {$products[$selectedProduct]->stock}" . PHP_EOL;
        continue;
    }
    for ($i = 0; $i < $itemAmount; $i++) {
        $shoppingCart[] = $products[$selectedProduct];
    }

    echo "{$products[$selectedProduct]->Name} added to cart" . PHP_EOL;

    $yesOrNoLoop = null;
    while ($yesOrNoLoop == null) {
        $continueShopping = (string)readline("Continue shopping: y/n ?");
        if ($continueShopping == 'y') {
            $keepShopping = true;
            break;
        } elseif ($continueShopping == 'n') {
            $keepShopping = false;
            break;
        } else {
            echo 'Wrong input' . PHP_EOL;
            $yesOrNoLoop = null;
        }
    }
}

$cleanCart = [];
if (!isset($cleanCart)) {
    $cleanCart = new stdClass();
}
foreach ($shoppingCart as $product) {
    $productName = $product->Name;

    if (array_key_exists($productName, $cleanCart)) {
        $cleanCart[$productName]->Price += $product->price;
        $cleanCart[$productName]->count += 1;
    } else {
        @$cleanCart[$productName]->Price = $product->price;
        $cleanCart[$productName]->name = $productName;
        $cleanCart[$productName]->count = 1;
    }
}

$cleanCart = array_values($cleanCart);

echo PHP_EOL;
for ($i = 0; $i < count($cleanCart); $i++) {
    echo $cleanCart[$i]->count . ' x ' . $cleanCart[$i]->name;
    echo ' for $' . number_format($cleanCart[$i]->Price / 100, 2) . PHP_EOL;
}

$sum = array_sum(array_column($cleanCart, "Price"));

echo 'Total sum of shopping cart is $' . number_format($sum / 100, 2) . PHP_EOL;

echo PHP_EOL;

$buyOrnNot = readline("Would you like to purchase: y/n ?");
echo PHP_EOL;

if ($buyOrnNot == 'y' && $sum <= $person->wallet) {
    $person->wallet -= $sum;
    echo "$person->name is left with $" . number_format($person->wallet / 100, 2) . PHP_EOL;
    echo 'Thank you, come again!';
    exit;
}
if ($buyOrnNot == 'y' && $sum > $person->wallet) {
    echo "Cart total sum is more than $person->name has :$";
    echo number_format($person->wallet / 100, 2) . PHP_EOL;
    echo 'Thank you, come again!';
    exit;
}


if ($buyOrnNot == 'n') {
    echo "Thank you for browsing" . PHP_EOL;
    exit;
}
