<div class="title">
    <h1>ADD MOVIE</h1>
</div>
<div class="content">
    <form action="/movies/create" method="post">
        <div class="form-line">
            <input type="text" name="title" placeholder="Movie name" value="<?php echo $_POST['title'] ?? '' ?>">
        </div>
        <div class="form-line">
            <input type="number" name="year" placeholder="Year (E.g. 1980)" min="1901" max="2155" step="1" value="<?php echo $_POST['year'] ?? '' ?>">
        </div>
        <div class="form-line">
            <input type="text" name="format" placeholder="Movie format" value="<?php echo $_POST['format'] ?? '' ?>">
        </div>
        <div class="form-line">
            <textarea name="stars" placeholder="Movie actors"><?php echo $_POST['stars'] ?? '' ?></textarea>
        </div>
        <div  class="form-submit">
            <input type="submit" value="Save">
        </div>
    </form>
</div>
<div class="content-link">
        Have txt file? <a href="/movies/import">Import movies</a>
</div>
<div class="content-link">
    <a href="/movies"><< Show all movies</a>
</div>