<!-- Querying DB -->
<form action="index.php" method="post" class="my-4">
    <div class="space-y-4">
        <div class="flex items-center gap-4">
            <p>Show series directors who have directed a movie in a certain zip code:</p>
            <input type="number" placeholder="Zip code" name="zip" class="input input-bordered w-full max-w-[10rem]" />
            <button type="submit" name="query" value="show_directors_by_zip">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>Show people who have won more than</p>
            <input type="number" placeholder="Num of awards" name="k" class="input input-bordered w-full max-w-[10rem]" />
            <p>awards for the same motion picture in the same year</p>
            <button type="submit" name="query" value="multiple_awards_same_mp_year">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>Show the youngest and oldest actors to receive at least one award:</p>
            <button type="submit" name="query" value="youngest_and_oldest_winner">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>Show American producers who got a box office collection of at least</p>
            <input type="number" name="x" class="input input-bordered w-full max-w-[10rem]" />
            <p>with a budget no greater than</p>
            <input type="number" name="y" class="input input-bordered w-full max-w-[10rem]" />
            <button type="submit" name="query" value="american_budget_box_office">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>List people who have played multiple roles in a motion picture where the rating is greater than</p>
            <input type="number" name="rating" class="input input-bordered w-full max-w-[10rem]" />
            <button type="submit" name="query" value="multiple_roles_with_rating">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>Show actors who have played a role in both a Marvel and a Warner Bros. motion picture:</p>
            <button type="submit" name="query" value="marvel_and_wb">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
        <div class="flex items-center gap-4">
            <p>Show all actors who have the same birthday:</p>
            <button type="submit" name="query" value="same_birthday">
                <div class="btn btn-primary">Search</div>
            </button>
        </div>
    </div>
</form>
