<div class="header">
	<h1>Movies</h1>
</div>

<nav class="breadcrumbs">
	<ul>
		<li>Movies</li>
	</ul>
</nav>

<div class="content">
	<div class="row">
		<h2>Recent Movies</h2>
		<div class="recent movies" id="recent-movies"></div>
	</div>
	<div class="row">
		<p>Record Count: <span id="data-table-count">?</span></p>
		<h2>All Movies</h2>
		<div class="movies" id="movies"></div>
	</div>
</div>


<template id="template">
	<a class="movie-info" href="/movie/MOVIE_ID/summary" target="_blank">
		<div class="poster">
			<img src="/api/movie/MOVIE_ID/poster" data-id="poster" loading="lazy" />
		</div>
		<div class="info">
			<div class="title">
				<h2 data-id="title">TITLE</h2>
			</div>
			<div class="release_date">
				<span>Release Date: </span><span data-dateonlyformatter data-id="release_date">RELEASE_DATE</span>
			</div>
			<!-- <div class="file_name">
				<span>File Name: </span><span data-id="file_name">FILE_NAME</span>
			</div> -->
		</div>
	</a>
</template>