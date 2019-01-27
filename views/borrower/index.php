<div id="homepageslider" class="carousel slide" data-ride="carousel" data-interval="5000">
	<ol class="carousel-indicators">
		<li class="active" data-target="#homepageslider" data-slide-to="0"></li>
		<li data-target="#homepageslider" data-slide-to="1"></li>
		<li data-target="#homepageslider" data-slide-to="2"></li>
		<li data-target="#homepageslider" data-slide-to="3"></li>
		<li data-target="#homepageslider" data-slide-to="4"></li>
		<li data-target="#homepageslider" data-slide-to="5"></li>
		<li data-target="#homepageslider" data-slide-to="6"></li>
	</ol>

	<div class="carousel-inner">
		<div class="item active"><img src="images/carousel1.jpg"></div>
		<div class="item"><img src="images/carousel2.jpg"></div>
		<div class="item"><img src="images/carousel3.jpg"></div>
		<div class="item"><img src="images/carousel4.jpg"></div>
		<div class="item"><img src="images/carousel5.jpg"></div>
		<div class="item"><img src="images/carousel6.jpg"></div>
		<div class="item"><img src="images/carousel7.jpg"></div>
	</div>

	<a href="#homepageslider" role="button" class="left carousel-control" data-slide="prev">
	<span class="glyphicon glyphicon-chevron-left"></span></a>

	<a href="#homepageslider" role="button" class="right carousel-control" data-slide="next">
	<span class="glyphicon glyphicon-chevron-right"></span></a>
</div>

<div class="basicsearch">
    <h3>Greenville College Library</h3>
    <h4>Search for library's materials and collections</h4>
    <form method="GET" id="basicsearchform">
        <div class="form-group">
            <input id="basicsearchbox" type="text" name="basicsearch" class="form-control" size="50">
        </div>
        <div class="form-group">
            Limit to: <select name="searchtype" class="searchtype form-control">
                <option value="any">Any Field</option>
                <option value="booktitle">Title</option>
                <option value="author">Author</option>
                <option value="publisher">Publisher</option>
                <option value="publishingyear">Year</option>
                <option value="accession_no">Accession Number</option>
            </select>
        </div>
        <button type="submit" id="button" class="btn btn-success btn-sm">Search</button>
    </form>
</div>