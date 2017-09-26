<script src="https://hgverwaltung.ch/polyfill/v2/polyfill.min.js?features=fetch"></script>
<script src="https://hgverwaltung.ch/list-1.5.min.js"></script>
<script src="https://hgverwaltung.ch/hgutil-1.0.js"></script>
<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">

<style>
	#hg_jahrSelect,
	#hg_teamSelect,
	#hg_data,
	#hg_alle {
		font-family: 'Lato', sans-serif;
	}

	#hg_jahrSelect,
	#hg_alle,
	#hg_alle input {
		vertical-align: top;
	}

	#hg_data tbody tr:nth-child(odd) {
		background-color: #ebeff4;
	}

	#hg_data tr {
		text-align: left;
	}

	#hg_data th {
		cursor: default;
	}

	#hg_data .number {
		text-align: right;
		padding-right: 5px;
	}

	#hg_data td {
		width: 40px;
	}

	#hg_data td.nachname {
		width: 200px;
	}

	#hg_data td.vorname {
		width: 200px;
	}

	#hg_data td.diff.positive {
		background-color: lightgreen;
	}

	#hg_data td.diff.negative {
		background-color: lightcoral;
	}

	#hg_data .sort.asc::after {
		content: "\25b2";
	}

	#hg_data .sort.desc::after {
		content: "\25bc";
	}

	#hg_total_tr td {
		font-weight: bold;
	}
</style>

<select id="hg_teamSelect" size="3" multiple></select>
<select id="hg_jahrSelect"></select>
<span id="hg_alle">
<input type="radio" name="alle" value="1" checked>Alle Spiele
<input type="radio" name="alle" value="0">Nur Meisterschaft
</span>
<?php echo "Übergeben wurde der Name " . $_GET["club"]; ?>
<table id="hg_data" style="display: none;">
	<thead>
		<tr>
			<th class="sort" data-sort="nachname">Nachname</th>
			<th class="sort" data-sort="vorname">Vorname</th>
			<th class="sort number" data-sort="punkte">Punkte</th>
			<th class="sort number" data-sort="streiche">Streiche</th>
			<th class="sort number" data-sort="schnitt">Durchschnitt</th>
			<th class="sort number" data-sort="diff">Ver&auml;nderung Vorjahr</th>
			<th id="hg_rangpunkte_header" class="sort number" data-sort="rangpunkte">Rangpunkte</th>
		</tr>
	</thead>
	<tbody class="hg_list">
	</tbody>
	<tfoot>
	</tfoot>
</table>

<table style="display: none;">
	<tr id="hg_tr_template">
		<td class="nachname"></td>
		<td class="vorname"></td>
		<td class="punkte number"></td>
		<td class="streiche number"></td>
		<td class="schnitt number"></td>
		<td class="diff number"></td>
		<td class="rangpunkte number"></td>
	</tr>
	<tr id="hg_total_tr">
		<td colspan="2" class="total_label"></td>
		<td class="total_punkte number"></td>
		<td class="total_streiche number"></td>
		<td class="total_schnitt number"></td>
		<td></td>
		<td id="hg_rangpunkte_footer"></td>
	</tr>
</table>

<script>
	(function () {
		//var club = window.club;
		
        hgutil.loadSelectFromArray('https://www.hgverwaltung.ch/api/1/' + club + '/spiele/jahre', 'hg_jahrSelect', true, getData);
		hgutil.loadSelectFromArray('https://www.hgverwaltung.ch/api/1/' + club + '/mannschaften?spiele=true', 'hg_teamSelect', true, getData);

		var hgDataTable = document.getElementById("hg_data");
		// var tbody = hgDataTable.createTBody();
		// tbody.classList.add('hg_list');
		hgDataTable.createTFoot();

		var valueNames = [];
		var tdElements = document.getElementById('hg_tr_template').getElementsByTagName('td');
		for (var v = 0; v < tdElements.length; v++) {
			valueNames.push(tdElements[v].classList[0]);
		}

		var options = {
			valueNames: valueNames,
			listClass: 'hg_list',
			item: 'hg_tr_template'
		};

		var dataList = new List('hg_data', options);

		document.getElementById('hg_jahrSelect').addEventListener("change", getData);
		document.getElementById('hg_teamSelect').addEventListener("change", getData);
		var allRadios = document.getElementById('hg_alle').querySelectorAll("input");

		allRadios[0].addEventListener("change", getData);
		allRadios[1].addEventListener("change", getData);

		function getData() {
			var jahr = document.getElementById('hg_jahrSelect').value;
			var teams = Array.prototype.slice.call(document.querySelectorAll('#hg_teamSelect option:checked'), 0).map(function (v) {
				return v.value;
			});
			var alle = document.querySelector('#hg_alle input[name="alle"]:checked').value;

			if (jahr && teams && teams.length > 0) {
				var url = 'https://www.hgverwaltung.ch/api/1/' + club + '/durchschnitt/' + teams.join(',') + '?alle=' + alle + '&jahr=' + jahr;
				fetch(url).then(function (response) {
					return response.json();
				}).then(function (results) {
					showData(results);
				});
			}
			else {
				showData([]);
			}
		}

		function showData(results) {
			dataList.clear();

			//remove total rows
			var tfoot = document.getElementById('hg_data').getElementsByTagName('tfoot')[0];
			while (tfoot.firstChild) {
				tfoot.removeChild(tfoot.firstChild);
			}

			if (results.length === 0) {
				document.getElementById('hg_data').style.display = 'none';
				return;
			}
			document.getElementById('hg_data').style.display = '';

			var totalPunkte = 0;
			var totalStreiche = 0;

			results.forEach(function (row) {
				if (row.schnitt && row.schnittVorjahr) {
					row.diff = (parseFloat(row.schnitt) - parseFloat(row.schnittVorjahr)).toFixed(2);
					row.schnitt = row.schnitt.toFixed(2);
				}

				if (row.streiche) {
					totalStreiche += row.streiche;
				}

				if (row.punkte) {
					totalPunkte += row.punkte;
				}
			});
			dataList.add(results);

			// css class 'negative' und 'positive' in der diff spalte hinzufügen
			var i = 0;
			var diffTds = document.getElementById('hg_data').querySelectorAll('td.diff');
			for (; i < diffTds.length; i++) {
				var diffTd = diffTds[i];
				var value = parseFloat(diffTd.textContent);
				if (value >= 0) {
					diffTd.classList.add('positive');
				}
				else if (value < 0) {
					diffTd.classList.add('negative');
				}
			}

			//sortierung nach schnitt
			dataList.sort('schnitt', { order: "desc" });

			// mannschaftstotal
			if (totalStreiche > 0) {
				var totalRow = document.getElementById('hg_total_tr').cloneNode(true);
				totalRow.querySelector(".total_label").textContent = 'Mannschaftstotal';
				totalRow.querySelector(".total_punkte").textContent = totalPunkte;
				totalRow.querySelector(".total_streiche").textContent = totalStreiche;
				totalRow.querySelector(".total_schnitt").textContent = (totalPunkte / totalStreiche).toFixed(2);
				tfoot.appendChild(totalRow);
			}

			// rangpunkte verstecken wenn nachwuchs
			var teams = Array.prototype.slice.call(document.querySelectorAll('#hg_teamSelect option:checked'), 0).map(function (v) {
				return v.value;
			});
			if (teams.length === 1 && teams[0] === 'NW') {
				document.getElementById('hg_rangpunkte_header').style.display = 'none';
				document.getElementById('hg_rangpunkte_footer').style.display = 'none';

				var rangpunkteTds = document.getElementById('hg_data').querySelectorAll("td.rangpunkte");
				for (i = 0; i < rangpunkteTds.length; i++) {
					rangpunkteTds[i].style.display = 'none';
				}
			}
			else {
				document.getElementById('hg_rangpunkte_header').style.display = '';
				document.getElementById('hg_rangpunkte_footer').style.display = '';
			}
		}

	})();

</script>