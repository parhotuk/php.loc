<div class="title">
    <h1>MOVIE INFO</h1>
</div>
<div class="content">
    <div class="content-movie-title">
        <?= $movie['title'] ?>
    </div>
    <div class="content-movie-info">
        <strong>Year: </strong><?= $movie['year'] ?>
    </div>
    <div class="content-movie-info">
        <strong>Format: </strong><?= $movie['format'] ?>
    </div>
    <div class="content-movie-info">
        <strong>Stars: </strong><?= $movie['stars'] ?>
    </div>
</div>
<div class="content-link">
    <a href="/movies"><< Show all movies</a>
</div>