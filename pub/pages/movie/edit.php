<div class="header">
    <h1>Edit Movie</h1>
</div>

<nav class="breadcrumbs">
    <ul>
        <li><a href="/">Movies</a></li>
        <li><a href="/movie/<?php echo htmlentities($recMovie->id()); ?>/summary"><?php echo htmlentities($recMovie->title()); ?></a></li>
        <li>Edit</li>
    </ul>
</nav>

<div class="content">
    <form method="post" action="" id="frm" class="form-group main-form">
        <input type="hidden" id="movie.id" name="movie.id" value="<?php echo htmlentities($recMovie->id()); ?>" />
        <div class="input-group">
            <label for="movie.order_title" class="form-control">Order Title</label>
            <input type="text" id="movie.order_title" name="movie.order_title" class="form-control" required="required" value="<?php echo htmlentities($recMovie->order_title()); ?>" />
        </div>
        <div class="button-group">
            <button type="submit" class="button primary"><i class="fa-solid fa-save"></i>Save</button>
            <a href="/movie/<?php echo htmlentities($recMovie->id()); ?>/summary" class="button secondary"><i class="fa-solid fa-ban"></i>Cancel</a>
            <a href="/movie/<?php echo htmlentities($recMovie->id()); ?>/delete" class="button remove"><i class="fa-solid fa-trash"></i>Delete?</a>
        </div>
    </form>
</div>