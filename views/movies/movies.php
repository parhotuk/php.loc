<div class="title">
    <h1>MOVIES</h1>
</div>
<div class="content">
    <div class="content-links">
        <a href="/movies/create">Add movie</a>
        <a href="/movies/import">Import movies</a>
    </div>
    <div class="content-search-box">
        <form action="/movies" method="post" >
            <span>
                <input type="text" name="text" placeholder="Search" value="<?php echo $_POST['text'] ?? '' ?>">
            </span>
            <span>
                <input id="searchOption" type="checkbox" name="search_option" <?php echo isset($_POST['search_option']) ? 'checked' : '' ?>  >
                <label for="searchOption">By actors</label>
            </span>
            <span>
                <input type="submit" value="Search">
            </span>
            <a href="/movies">Reset</a>
        </form>
    </div>
    <div class="content-movies-list">
        <?php if(isset($movies) && !empty($movies)):  ?>
            <?php foreach($movies as $movie): ?>
                <div class="item">
                    <div class="item-title">
                        <a href="/movies/show/<?= $movie['id'] ?>"><?= $movie['title'] ?></a>
                    </div>
                    <div class="item-info">
                        <?php if(!empty($movie['year'])): ?>
                            Year: <?= $movie['year'] ?>.
                        <?php endif; ?>
                        <?php if(!empty($movie['format'])): ?>
                            Format: <?= $movie['format'] ?>.
                        <?php endif; ?>
                    </div>
                    <div class="item-delete">
                        <a href="/movies/delete/<?= $movie['id'] ?>">Delete</a>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div>Nothing found :(</div>
        <?php endif; ?>
    </div>
</div>

<?php if($this->authenticated): ?>
    <div class="content-link">
        Welcome, <?php echo $_SESSION['user_login'] ?? '' ?>.
        <a href="/logout">Exit</a>
    </div>
<?php endif; ?>