<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Movie database</title>
    <link rel="stylesheet" href="styles.css" type="text/css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
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
            <button type="submit" name="query" value="view_all_movies">
                <div class="btn">View all movies</div>
            </button>
            <button type="submit" name="query" value="view_all_actors">
                <div class="btn">View all actors</div>
            </button>
        </div>
    </form>
    <form action="index.php" method="post" class="my-4 rounded-badge border-2 border-base-300 p-6 shadow">
        <div class="space-x-2 flex gap-4 items-center flex-wrap">
            <h2 class="text-xl font-bold">Like a movie!</h2>
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Who are you?</span>
                </div>
                <select class="select select-bordered" name="user">
                    <option disabled selected>Select user</option>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "COSI127b";

                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    foreach ($conn->query("SELECT email FROM user") as $row) {
                        echo "<option value=\"" . $row['email'] . "\">" . $row['email'] . "</option>";
                    }
                    ?>
                </select>
            </label>
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">What movie do you like?</span>
                </div>
                <select class="select select-bordered" name="movie">
                    <option disabled selected>Select movie</option>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "COSI127b";

                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    foreach ($conn->query("SELECT mp.name, mp.id FROM movie m INNER JOIN motion_picture mp ON m.mpid = mp.id") as $row) {
                        echo "<option value=\"" . $row['id'] . "\">" . $row['id'] . " - " . $row['name'] . "</option>";
                    }
                    ?>
                </select>
            </label>
            <button type="submit" name="like" value="like_movie">
                <div class="btn btn-primary">Like!</div>
            </button>
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
                'view_all_movies' => "SELECT mp.name, mp.rating, mp.production, mp.budget, m.boxoffice_collection FROM movie m INNER JOIN motion_picture mp ON m.mpid = mp.id",
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
            }

            if (isset($_POST['like'])) {
                $email = $_POST['user'];
                $mpid = $_POST['movie'];
                $stmt = $conn->prepare("INSERT INTO likes (user_email, mp_id) VALUES (?, ?)");
                $stmt->bindParam("si", $email, $mpid); // "si" indicates string and integer types
                $stmt->execute([$email, $mpid]);
            }
            ?>
        </table>
    </div>
</body>

</html>
