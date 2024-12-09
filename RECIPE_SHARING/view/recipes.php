<?php
session_start();
require_once '../db/db.php';

// Response array
$response = [
    'success' => false,
    'message' => '',
    'recipes' => []
];

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'get_recipe') {
        // Fetch a single recipe by ID
        $recipeId = $_GET['recipe_id'] ?? 0;

        try {
            // Fetch the recipe details
            $query = "SELECT r.recipe_id, r.food_id, ri.ingredient_id, ri.quantity, ri.unit, ri.optional, r.created_at 
                      FROM recipes AS r 
                      LEFT JOIN recipe_ingredients AS ri ON r.recipe_id = ri.recipe_id
                      WHERE r.recipe_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$recipeId]);
            $recipeDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($recipeDetails) {
                $response['success'] = true;
                $response['recipe'] = $recipeDetails;
            } else {
                $response['message'] = 'Recipe not found.';
            }
        } catch (Exception $e) {
            $response['message'] = "Failed to fetch recipe: " . $e->getMessage();
        }
    } else {
        // Fetch all recipes
        try {
            $query = "SELECT DISTINCT r.recipe_id, r.food_id, r.created_at FROM recipes AS r ORDER BY r.created_at DESC";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $response['success'] = true;
            $response['recipes'] = $recipes;
        } catch (Exception $e) {
            $response['message'] = "Failed to fetch recipes: " . $e->getMessage();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add_recipe') {
            // Add a new recipe
            $foodId = $_POST['food_id'] ?? 0;
            $ingredientId = $_POST['ingredient_id'] ?? [];
            $quantity = $_POST['quantity'] ?? [];
            $unit = $_POST['unit'] ?? [];
            $optional = $_POST['optional'] ?? [];

            // Insert recipe
            $query = "INSERT INTO recipes (food_id, created_at) VALUES (?, NOW())";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$foodId]);
            $recipeId = $pdo->lastInsertId();

            // Insert recipe ingredients
            foreach ($ingredientId as $index => $ingId) {
                $query = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit, optional)
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    $recipeId,
                    $ingId,
                    $quantity[$index] ?? 0,
                    $unit[$index] ?? '',
                    $optional[$index] ?? 0
                ]);
            }

            $response['success'] = true;
            $response['message'] = "Recipe added successfully.";
        } elseif ($action === 'edit_recipe') {
            // Edit an existing recipe
            $recipeId = $_POST['recipe_id'] ?? 0;
            $foodId = $_POST['food_id'] ?? 0;
            $ingredientId = $_POST['ingredient_id'] ?? [];
            $quantity = $_POST['quantity'] ?? [];
            $unit = $_POST['unit'] ?? [];
            $optional = $_POST['optional'] ?? [];

            // Update recipe
            $query = "UPDATE recipes SET food_id = ? WHERE recipe_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$foodId, $recipeId]);

            // Delete existing ingredients
            $query = "DELETE FROM recipe_ingredients WHERE recipe_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$recipeId]);

            // Re-insert ingredients
            foreach ($ingredientId as $index => $ingId) {
                $query = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit, optional)
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    $recipeId,
                    $ingId,
                    $quantity[$index] ?? 0,
                    $unit[$index] ?? '',
                    $optional[$index] ?? 0
                ]);
            }

            $response['success'] = true;
            $response['message'] = "Recipe updated successfully.";
        } elseif ($action === 'delete_recipe') {
            // Delete a recipe
            $recipeId = $_POST['recipe_id'] ?? 0;

            // Delete ingredients first
            $query = "DELETE FROM recipe_ingredients WHERE recipe_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$recipeId]);

            // Delete recipe
            $query = "DELETE FROM recipes WHERE recipe_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$recipeId]);

            $response['success'] = true;
            $response['message'] = "Recipe deleted successfully.";
        } else {
            $response['message'] = "Invalid action.";
        }
    } catch (Exception $e) {
        $response['message'] = "Operation failed: " . $e->getMessage();
    }
}

// Output response as JSON
header('Content-Type: application/json');
echo json_encode($response);

$connection->close();
?>
