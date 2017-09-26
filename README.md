# hgv-portal

Seite als Iframe einbinden und clubname übergeben.

Beispiel für ein responsive iframe

```
.videoWrapper {
	position: relative;
	padding-bottom: 56.25%; /* 16:9 */
	padding-top: 25px;
	height: 0;
}
.videoWrapper iframe {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}


<h1>Responsive iframe</h1>

<div class="videoWrapper">
    <iframe src="//www.youtube.com/embed/9fSde2DD8YQ" allowfullscreen></iframe>
</div>

<p>Enjoy =)</p>

```

Source: http://jsfiddle.net/omarjuvera/8zkunqxy/2/
# TODO: more text
