<!-- Querying DB -->
<form action="index.php" method="post" class="my-4">
    <div class="space-y-4">
        <div class="flex items-center gap-4">
            <p>Search motion picture by name:</p>
            <input type="text" placeholder="Motion picture name" name="movie_name" class="input input-bordered w-full max-w-xs" />
            <button type="submit" name="query" value="search_movie_by_name">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>Find movies that a certain user has liked:</p>
            <input type="text" placeholder="Enter an email" name="uemail" class="input input-bordered w-full max-w-xs" />
            <button type="submit" name="query" value="show_movies_liked_by_email">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>Find motion pictures that were filmed in a certain country:</p>
            <select class="select select-bordered" name="country">
                <option disabled selected>Select country</option>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "COSI127b";

                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                foreach ($conn->query("SELECT DISTINCT country FROM `location` ORDER BY country ASC;") as $row) {
                    echo "<option value=\"" . $row['country'] . "\">" . $row['country'] . "</option>";
                }
                ?>
            </select>
            <button type="submit" name="query" value="show_by_shooting_country">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>Show top 2 rated thriller movies that were shot exclusively in Boston:</p>
            <button type="submit" name="query" value="top_2_thrillers_boston">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>Show all movies with more than</p>
            <input type="number" name="y" class="input input-bordered w-full max-w-[10rem]" />
            <p>likes by users of ages less than</p>
            <input type="number" name="x" class="input input-bordered w-full max-w-[10rem]" />
            <button type="submit" name="query" value="likes_ages">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>Show all motion pictures with a rating higher than the average of all comedies:</p>
            <button type="submit" name="query" value="higher_than_avg_comedies">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>Show the top 5 movies with the highest number of people playing roles:</p>
            <button type="submit" name="query" value="top_5_most_people">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
    </div>
</form>
