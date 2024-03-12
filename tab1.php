<!-- Querying DB -->
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

<!-- Liking movies -->
<form action="tab1.php" id="likeForm" method="post" class="my-4 rounded-badge border-2 border-base-300 p-6 shadow">
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
        <button id="likeButton" name="like" value="like_movie">
            <div class="btn btn-primary">Like!</div>
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('likeButton').addEventListener('click', function(e) {
            e.preventDefault()
            var form = document.getElementById('likeForm');
            var formData = new FormData(form);
            form.reset()

            fetch('index.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    // Handle response if needed
                    console.log('Form submitted successfully');
                })
                .catch(error => {
                    // Handle error if needed
                    console.error('Error submitting form:', error);
                });
        });
    });
</script>
