<div class="title">
    <h1>IMPORT MOVIES</h1>
</div>
<div class="content">
    <form action="/movies/import" method="post" enctype="multipart/form-data">
        <div>
            <input type="file" name="fileToUpload" >
        </div>
        <div class="form-submit">
            <input type="submit" value="Import">
        </div>
    </form>
</div>
<div class="content-link">
    Have txt file? <a href="/movies/import">Import movies</a>
</div>
<div class="content-link">
    <a href="/movies"><< Show all movies</a>
</div>