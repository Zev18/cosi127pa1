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
        <div class="navbar bg-base-100 border-2 border-base-300 rounded-xl shadow flex justify-between py-2">
            <p class="text-xl px-2 font-bold">Movie database</p>
            <form action="index.php" method="post">
                <button name="query" value="show_all_tables">
                    <div class="btn btn-primary">Show all tables</div>
                </button>
            </form>
        </div>
    </header>
    <!-- TABS LOGIC -->
    <div role="tablist" class="tabs tabs-lifted w-full tabs-lg">
        <input type="radio" name="my_tabs_2" role="tab" class="tab min-w-max" aria-label="PA 1.2" />
        <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
            <?php include 'tab1.php' ?>
        </div>
        <input type="radio" name="my_tabs_2" role="tab" class="tab min-w-max" aria-label="Movies" checked />
        <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
            <?php include 'tab2.php' ?>
        </div>
        <input type="radio" name="my_tabs_2" role="tab" class="tab min-w-max" aria-label="People" />
        <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
            <?php include 'tab3.php' ?>
        </div>
    </div>

    <!-- Results table and queries logic -->
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
                'view_all_movies' => "SELECT mp.name, mp.rating, mp.production, mp.budget, m.boxoffice_collection FROM movie m INNER JOIN MotionPicture mp ON m.mpid = mp.id
                ",
                'view_all_actors' => "SELECT p.name, p.nationality, p.dob, p.gender FROM `role` join people as p on p.id = pid where role_name = 'actor'
                ",
                'search_movie_by_name' => "SELECT mp.name, mp.rating, mp.production, mp.budget
                FROM MotionPicture mp, movie m
                WHERE mp.id = m.mpid AND mp.name = :movie_name
                ",
                'show_all_tables' => "show tables
                ",
                'show_movies_liked_by_email' => "SELECT mp.name, mp.rating, mp.production, mp.budget FROM user u, likes l, MotionPicture mp, movie m WHERE u.email = l.uemail AND l.mpid = mp.id AND mp.id = m.mpid AND u.email = :uemail
                ",
                'show_by_shooting_country' => "SELECT DISTINCT mp.name FROM MotionPicture mp, location l WHERE mp.id = l.mpid AND l.country = :country
                ",
                'show_directors_by_zip' => "SELECT p.name AS 'Director name', mp.name AS 'Motion picture Name' FROM people p, role r, series s, location l, MotionPicture mp WHERE p.id = r.pid AND r.mpid = s.mpid AND s.mpid = mp.id AND s.mpid = l.mpid AND r.role_name = 'Director' AND l.zip = :zip
                ",
                'multiple_awards_same_mp_year' => "SELECT p.name AS 'Person name', mp.name AS 'Motion picture name', q.award_year, q.count FROM (SELECT a.pid, a.mpid, a.award_year, COUNT(*) AS count FROM award a GROUP BY a.mpid, a.pid, a.award_year HAVING COUNT(*) > :k) AS q, people p, MotionPicture mp WHERE q.pid = p.id AND q.mpid = mp.id
                ",
                'youngest_and_oldest_winner' => "SELECT DISTINCT p.name, DATEDIFF(DATE(CONCAT(a.award_year, '-01-01')), p.dob)/365 AS 'Age won'
                FROM award a, people p, role r
                WHERE a.pid = p.id AND a.mpid = r.mpid AND a.pid = r.pid AND r.role_name = 'Actor'
                    AND DATEDIFF(DATE(CONCAT(a.award_year, '-01-01')), p.dob) =
                        (SELECT MAX(DATEDIFF(DATE(CONCAT(a.award_year, '-01-01')), p.dob))
                        FROM award a, people p, role r
                        WHERE a.pid = p.id AND a.mpid = r.mpid AND a.pid = r.pid AND r.role_name = 'Actor')
                    OR DATEDIFF(DATE(CONCAT(a.award_year, '-01-01')), p.dob) =
                        (SELECT MIN(DATEDIFF(DATE(CONCAT(a.award_year, '-01-01')), p.dob))
                        FROM award a, people p, role r
                        WHERE a.pid = p.id AND a.mpid = r.mpid AND a.pid = r.pid AND r.role_name = 'Actor')
                ",
                'american_budget_box_office' => "SELECT p.name AS 'Producer name', mp.name AS 'Motion picture name', m.boxoffice_collection, mp.budget
                FROM people p, role r, MotionPicture mp, movie m
                WHERE p.id = r.pid AND r.mpid = mp.id AND mp.id = m.mpid AND r.role_name = 'Producer' AND p.nationality = 'USA' AND m.boxoffice_collection >= :x AND mp.budget <= :y
                ",
                'multiple_roles_with_rating' => "SELECT p.name AS 'Person name', mp.name AS 'Motion picture name', q.count
                FROM
                (SELECT p.id AS pid, mp.id AS mpid, COUNT(*) AS count
                FROM people p, role r, MotionPicture mp
                WHERE p.id = r.pid AND r.mpid = mp.id AND mp.rating > :rating
                GROUP BY p.id, mp.id
                HAVING COUNT(*) > 1) AS q, people p, MotionPicture mp
                WHERE q.pid = p.id AND q.mpid = mp.id
                ",
                'top_2_thrillers_boston' => "SELECT DISTINCT mp.name, mp.rating
                FROM MotionPicture mp, movie m, genre g, location l
                WHERE mp.id = m.mpid AND mp.id = g.mpid AND mp.id = l.mpid AND g.genre_name = 'Thriller' AND l.city = 'Boston'
                AND NOT EXISTS
                (SELECT * FROM location l2 WHERE l2.mpid = mp.id AND l2.city != 'Boston')
                ORDER BY mp.rating DESC
                LIMIT 2
                ",
                'likes_ages' => "SELECT mp.name, COUNT(*) AS count
                FROM movie m, MotionPicture mp, likes l, user u
                WHERE m.mpid = mp.id AND mp.id = l.mpid AND l.uemail = u.email AND u.age < :x
                GROUP BY mp.name
                HAVING count > :y
                ",
                'marvel_and_wb' => "SELECT actor_name, mp2.name AS WB_name, q.name AS Marvel_name
                FROM MotionPicture mp2, role r2,
                (SELECT p.id, p.name AS actor_name, mp.name
                FROM people p, role r, MotionPicture mp
                WHERE p.id = r.pid AND r.mpid = mp.id AND r.role_name = 'Actor' AND mp.production = 'Marvel' AND EXISTS
                    (SELECT *
                    FROM people p2, role r2, MotionPicture mp2
                    WHERE p.id = p2.id AND p2.id = r2.pid AND r2.mpid = mp2.id AND r2.role_name = 'Actor' AND mp2.production = 'Warner Bros')) AS q
                WHERE r2.pid = q.id AND r2.role_name = 'Actor' AND r2.mpid = mp2.id AND q.name != mp2.name

                ",
                'higher_than_avg_comedies' => "SELECT DISTINCT mp.name, mp.rating
                FROM MotionPicture mp, genre g
                WHERE mp.id = g.mpid AND mp.rating >
                    (SELECT AVG(mp2.rating)
                    FROM MotionPicture mp2, genre g2
                    WHERE mp2.id = g2.mpid AND g2.genre_name = 'Comedy')
                ORDER BY mp.rating DESC
                ",
                'top_5_most_people' => "SELECT mp2.name, q2.p_count, q1.r_count
                FROM
                (SELECT mp.id, COUNT(*) AS r_count
                FROM MotionPicture mp, movie m, role r
                WHERE mp.id = m.mpid AND r.mpid = mp.id
                GROUP BY mp.id) AS q1,
                (SELECT DISTINCT mp.id, COUNT(DISTINCT r.pid) AS p_count
                FROM MotionPicture mp, movie m, role r
                WHERE mp.id = m.mpid AND r.mpid = mp.id
                GROUP BY mp.id) AS q2, MotionPicture mp2
                WHERE q1.id = q2.id AND q1.id = mp2.id
                ORDER BY q2.p_count DESC
                LIMIT 5
                ",
                'same_birthday' => "SELECT DISTINCT q1.name AS 'actor 1', q2.name AS 'actor 2', q1.dob AS 'Birthday'
                FROM
                (SELECT * FROM people p1, role r WHERE p1.id = r.pid AND r.role_name = 'Actor') AS q1,
                (SELECT * FROM people p1, role r WHERE p1.id = r.pid AND r.role_name = 'Actor') AS q2
                WHERE q1.dob = q2.dob AND q1.pid < q2.pid
                "
            ];

            try {
                if (isset($_POST['query']) && array_key_exists($_POST['query'], $queries)) {
                    $query = $queries[$_POST['query']];

                    $stmtColumns = $conn->prepare($query);
                    switch ($_POST['query']) {
                        case 'search_movie_by_name':
                            $stmtColumns->bindParam(":movie_name", $_POST['movie_name']);
                            break;
                        case 'show_movies_liked_by_email':
                            $stmtColumns->bindParam(":uemail", $_POST['uemail']);
                            break;
                        case 'show_by_shooting_country':
                            $stmtColumns->bindParam(":country", $_POST['country']);
                            break;
                        case 'show_directors_by_zip':
                            $stmtColumns->bindParam(':zip', $_POST['zip']);
                            break;
                        case 'multiple_awards_same_mp_year':
                            $stmtColumns->bindParam(':k', $_POST['k']);
                            break;
                        case 'american_budget_box_office':
                            $stmtColumns->bindParam(':x', $_POST['x']);
                            $stmtColumns->bindParam(':y', $_POST['y']);
                            break;
                        case 'multiple_roles_with_rating':
                            $stmtColumns->bindParam(':rating', $_POST['rating']);
                            break;
                        case 'likes_ages':
                            $stmtColumns->bindParam(':x', $_POST['x']);
                            $stmtColumns->bindParam(':y', $_POST['y']);
                            break;
                    }

                    $stmtColumns->execute();
                    $columns = array();
                    for ($i = 0; $i < $stmtColumns->columnCount(); $i++) {
                        $col = $stmtColumns->getColumnMeta($i);
                        $columns[] = $col['name'];
                    }

                    // Prepare and execute main query
                    $data = $conn->prepare($query);
                    switch ($_POST['query']) {
                        case 'search_movie_by_name':
                            $data->bindParam(":movie_name", $_POST['movie_name']);
                            break;
                        case 'show_movies_liked_by_email':
                            $data->bindParam(":uemail", $_POST['uemail']);
                            break;
                        case 'show_by_shooting_country':
                            $data->bindParam(":country", $_POST['country']);
                            break;
                        case 'show_directors_by_zip':
                            $data->bindParam(':zip', $_POST['zip']);
                            break;
                        case 'multiple_awards_same_mp_year':
                            $data->bindParam(':k', $_POST['k']);
                            break;
                        case 'american_budget_box_office':
                            $data->bindParam(':x', $_POST['x']);
                            $data->bindParam(':y', $_POST['y']);
                            break;
                        case 'multiple_roles_with_rating':
                            $data->bindParam(':rating', $_POST['rating']);
                            break;
                        case 'likes_ages':
                            $data->bindParam(':x', $_POST['x']);
                            $data->bindParam(':y', $_POST['y']);
                            break;
                    }
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
                }
            } catch (PDOException $e) {
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }

            if (isset($_POST['like'])) {
                $email = $_POST['user'];
                $mpid = $_POST['movie'];
                $stmt = $conn->prepare("INSERT INTO likes (uemail, mpid) VALUES (?, ?)");
                $stmt->bindParam("si", $email, $mpid); // "si" indicates string and integer types
                $stmt->execute([$email, $mpid]);
            }
            ?>
        </table>
    </div>
</body>


</html>
