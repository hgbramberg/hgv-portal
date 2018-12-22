<select id="hg_jahrSelect"></select>

<table id="hg_data" style="display: none;">
	<thead>
		<tr>
			<th class="sort" data-sort="datum">Tag</th>
			<th class="sort" data-sort="datum">Datum</th>
			<th class="sort" data-sort="zeit">Zeit</th>
            <th class="sort" data-sort="anlass">Anlass</th>
            <th class="sort" data-sort="ort">Ort</th>
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
        <td class="anlass"></td>
		<td class="ort"></td>
	</tr>
</table>

<script>
	(function () {
		//var club = 'test';
		hgutil.loadSelectFromArray('https://www.hgverwaltung.ch/api/1/' + club + '/spiele/jahre?alle=1', 'hg_jahrSelect', (new Date()).getFullYear(), getData);	
	//	hgutil.loadSelectFromArray('https://www.hgverwaltung.ch/api/1/' + club + '/mannschaften?spiele=true', 'hg_teamSelect', true, getData);

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
	/*	document.getElementById('hg_teamSelect').addEventListener("change", getData);
		var allRadios = document.getElementById('hg_alle').querySelectorAll("input");
			allRadios[0].addEventListener("change", getData);
			allRadios[1].addEventListener("change", getData);
	*/		
		function getData() {
			var jahr = document.getElementById('hg_jahrSelect').value;
		//	var teams = Array.prototype.slice.call(document.querySelectorAll('#hg_teamSelect option:checked'), 0).map(function (v) {
		//		return v.value;
		//	});

			if (jahr > 0) {
				var url = 'https://www.hgverwaltung.ch/api/1/' + club + '/anlaesse' + '?jahr=' + jahr + '&inklSpiele=false';
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
			console.log(results);
            dataList.clear();

		//	var alle = document.querySelector('#hg_alle input[name="alle"]:checked').value;

			if (results.length === 0) {
				document.getElementById('hg_data').style.display = 'none';
				return;
			}
			document.getElementById('hg_data').style.display = '';

	
			results.forEach(function (row) {
				row.datumDisplay = row.datum.substring(8, 10) + '.' + row.datum.substring(5, 7) + '.' + row.datum.substring(0, 4);

				// Zeit ausblenden wenn 00:00
				if (row.datum.substring(11) != '00:00') {
					row.zeit = row.datum.substring(11);
				}
				else {
					row.zeit = '';
				};
                
                //Wochentag auswerten (wird in der Anlässe API nicht ausgegeben)
                var weekday = new Array(7);
                    weekday[0] = "Sonntag";
                    weekday[1] = "Montag";
                    weekday[2] = "Dienstag";
                    weekday[3] = "Mittwoch";
                    weekday[4] = "Donnerstag";
                    weekday[5] = "Freitag";
                    weekday[6] = "Samstag";
                
                // Achtung Monat ist 0 indexiert
                var d = new Date(row.datum.substring(0, 4), row.datum.substring(5, 7) -1 , row.datum.substring(8, 10) );
                row.wochentag = weekday[d.getDay()];
		
			});
			dataList.add(results);

			//dataList.sort('datum', { order: "asc" });
		}

	})();

</script>

