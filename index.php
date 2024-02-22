<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Movie database</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css" type="text/css">
    <title>Document</title>
</head>

<!-- Styling using DaisyUI and TailwindCSS -->
<!-- DaisyUI - https://daisyui.com/ -->
<!-- TailwindCSS - https://tailwindcss.com/ -->

<body class="p-4 sm:p-10 sm:py-8">
    <header class="mb-5">
        <div class="navbar bg-base-100 border-2 border-base-300 rounded-xl shadow">
            <p class="text-xl px-2 font-bold">Movie database</p>
        </div>
    </header>
    <form action="index.php" method="post" class="my-4">
        <div class="space-x-2">
            <button type="submit" name="query" value="view_all_movies" class="btn">View all movies</button>
            <button type="submit" name="query" value="view_all_actors" class="btn">View all actors</button>
        </div>
    </form>
    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <?php
            // SQL CONNECTIONS
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "COSI127b";

            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            function snakeCaseToNormal($str)
            {
                // Split the string by underscore
                $words = explode('_', $str);
                // Capitalize the first letter of each word and join them with spaces
                return implode(' ', array_map('ucfirst', $words));
            }

            $queries = [
                'view_all_movies' => "SELECT mp.name, mp.rating, mp.production, mp.budget, boxoffice_collection FROM movie JOIN motion_picture AS mp ON id = mp.id",
                'view_all_actors' => "SELECT p.name, p.nationality, p.dob, p.gender FROM `role` join people as p on p.id = pid where role_name = 'actor'",
            ];

            if (isset($_POST['query']) && array_key_exists($_POST['query'], $queries)) {
                $query = $queries[$_POST['query']];

                try {
                    // We will use PDO to connect to MySQL DB. This part need not be
                    // replicated if we are having multiple queries.
                    // initialize connection and set attributes for errors/exceptions
                    // Prepare and execute query to get column names
                    $stmtColumns = $conn->prepare($query);
                    $stmtColumns->execute();
                    $columns = array_keys($stmtColumns->fetch(PDO::FETCH_ASSOC));

                    // Prepare and execute main query
                    $data = $conn->prepare($query);
                    $data->execute();
                    echo "<thead><tr>";
                    // Loop through each row and print its contents
                    foreach ($columns as $column) {
                        echo "<th>" . snakeCaseToNormal($column) . "</th>";
                    }
                    echo "</tr></thead>";

                    echo "<tbody>";
                    while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>$value</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody>";
                } catch (PDOException $e) {
                    echo "<p>Error: " . $e->getMessage() . "</p>";
                }
            } ?>
        </table>
    </div>
</body>

</html>
