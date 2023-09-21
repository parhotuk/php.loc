<div class="title">
    <h1>IMPORT MOVIES</h1>
</div>
<?php if(isset($errorMessage) && !empty($errorMessage)): ?>
    <div class="error-messages">
        <?= $errorMessage ?>
    </div>
<?php endif; ?>
<?php if(isset($successMessage) && !empty($successMessage)): ?>
    <div class="success-messages">
        <?= $successMessage ?>
    </div>
<?php endif; ?>
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
    <a href="/movies"><< Show all movies</a>
</div>