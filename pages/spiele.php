<script src="https://hgverwaltung.ch/polyfill/v2/polyfill.min.js?features=fetch"></script>
<script src="https://hgverwaltung.ch/list-1.5.min.js"></script>
<script src="https://hgverwaltung.ch/hgutil-1.1.js"></script>
<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">

<select id="hg_teamSelect" size="3" multiple></select>
<select id="hg_jahrSelect"></select>
<span id="hg_alle">
<input type="radio" name="alle" value="1" checked>Alle Spiele
<input type="radio" name="alle" value="0">Nur Heimspiele
</span>

<table id="hg_data" style="display: none;">
	<thead>
		<tr>
			<th class="sort" data-sort="datum">Tag</th>
			<th class="sort" data-sort="datum">Datum</th>
			<th class="sort" data-sort="zeit">Zeit</th>
			<th class="sort" data-sort="team">Team</th>
			<th class="sort" data-sort="ort">Ort</th>
			<th class="sort" data-sort="art">Anlass</th>
			<th class="sort" data-sort="gegner">Gegner</th>
			<th class="sort" data-sort="spielort">Ort</th>
		</tr>
	</thead>
	<tbody class="hg_list">
	</tbody>
	<tfoot>
	</tfoot>
</table>

<table style="display: none;">
	<tr id="hg_tr_template">
		<td class="wochentag"></td>
		<td class="datumDisplay"></td>
		<td class="zeit"></td>
		<td class="team"></td>
		<td class="ort"></td>
		<td class="art"></td>
		<td class="gegner"></td>
		<td class="spielort"></td>
	</tr>
</table>

<script>
	(function () {
		//var club = 'test';
		hgutil.loadSelectFromArray('https://www.hgverwaltung.ch/api/1/' + club + '/spiele/jahre?alle=1', 'hg_jahrSelect', (new Date()).getFullYear(), getData);	
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

			if (jahr && teams && teams.length > 0) {
				var url = 'https://www.hgverwaltung.ch/api/1/' + club + '/spiele/' + teams.join(',') + '?jahr=' + jahr;
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

			var alle = document.querySelector('#hg_alle input[name="alle"]:checked').value;

			if (results.length === 0) {
				document.getElementById('hg_data').style.display = 'none';
				return;
			}
			document.getElementById('hg_data').style.display = '';

			//Heimspiele filtern
			if (alle == 0) {
				results = results.filter( (result) => result.ort == 'Heim' );
			}

			results.forEach(function (row) {
				row.datumDisplay = row.datum.substring(8, 10) + '.' + row.datum.substring(5, 7) + '.' + row.datum.substring(0, 4);

				// Zeit ausblenden wenn 00:00
				if (row.datum.substring(11) != '00:00') {
					row.zeit = row.datum.substring(11);
				}
				else {
					row.zeit = '';
				};

				//Team Name kürzen
				var re = new RegExp(club,"i");
				row.team = row.team.replace(re, "");				
			});
			dataList.add(results);

			dataList.sort('datum', { order: "asc" });
		}

	})();

</script>