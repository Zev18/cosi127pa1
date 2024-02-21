<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css" type="text/css">
    <title>Document</title>
</head>

<body class="p-4 sm:p-10 sm:py-8">
    <header class="mb-5">
        <div class="navbar bg-base-100 border-2 border-base-300 rounded-xl shadow">
            <p class="text-xl px-2 font-bold">Movie database</p>
        </div>
    </header>
    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <?php
            // SQL CONNECTIONS
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "COSI127b";

            try {
                // We will use PDO to connect to MySQL DB. This part need not be
                // replicated if we are having multiple queries.
                // initialize connection and set attributes for errors/exceptions
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // prepare statement for executions. This part needs to change for every query
                $stmt = $conn->prepare("DESCRIBE motion_picture");
                // execute statement
                $stmt->execute();
                $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
                echo "<thead><tr>";
                // Loop through each row and print its contents
                foreach ($columns as $column) {
                    echo "<th>" . $column . "</th>";
                }
                echo "</tr></thead>";

                echo "<tbody>";
                $stmt = $conn->prepare("SELECT * FROM motion_picture");
                $stmt->execute();
                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                foreach ($stmt->fetchAll() as $k => $v) {
                    echo "<tr>";
                    foreach ($v as $value) {
                        echo "<td>" . $value . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody>";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>

        </table>
    </div>
</body>

</html>
