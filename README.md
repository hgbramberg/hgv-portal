# hgv-portal

Seite als Iframe einbinden und clubname übergeben.

Beispiel unter https://hg.bramberg.ch/hgverwaltung



Beispiel für ein responsive iframe

```
.statsWrapper {
	position: relative;
	padding-bottom: 56.25%; /* 16:9 */
	padding-top: 25px;
	height: 0;
}
.statsWrapper iframe {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}


<h1>Responsive iframe</h1>

<div class="statsWrapper">
    <iframe src="https://stats.hg.bramberg.ch/index.php?club=test" ></iframe>
</div>

<p>Enjoy =)</p>

```

Source: http://jsfiddle.net/omarjuvera/8zkunqxy/2/
# TODO: more text
