<?php

if ($userAuth->checkToken()) {
?>
    <li><a href="/genre"><i class="fa-solid fa-layer-group"></i>Genres</a></li>
    <li><a href="/collection"><i class="fa-solid fa-layer-group"></i>Collections</a></li>
    <?php
    if ($data->user_roles($userAuth->user()->id())->hasRole("manager")) {
    ?>
        <li><a href="/movie-map"><i class="fa-solid fa-paperclip"></i>Map Movies</a></li>
        <li><a href="/movie-file"><i class="fa-solid fa-paperclip"></i>Movie Files</a></li>
<?php
    }
}
