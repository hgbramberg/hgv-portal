<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.16/b-1.4.2/b-html5-1.4.2/b-print-1.4.2/datatables.min.css"/>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.16/b-1.4.2/b-html5-1.4.2/b-print-1.4.2/datatables.min.js"></script>
<script type="text/javascript" language="javascript" class="init"></script>


<script src="https://hgverwaltung.ch/polyfill/v2/polyfill.min.js?features=fetch"></script>
<script src="https://hgverwaltung.ch/list-1.5.min.js"></script>
<script src="https://hgverwaltung.ch/hgutil-1.1.js"></script>
<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">

<body>

<select id="hg_teamSelect" size="3" multiple></select>
<select id="hg_jahrSelect"></select>

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


$('#hg_data').DataTable( {
                buttons: [
                    'copy', 'excel', 'pdfHtml5'
                ]
            } );


	(function () {
		var club = hgutil.getParameterByName('club');
		if (!club) {
		  club = 'test';
		}

      

		hgutil.loadSelectFromArray('https://www.hgverwaltung.ch/api/1/' + club + '/spiele/jahre?alle=1', 'hg_jahrSelect', (new Date()).getFullYear(), getData);
		hgutil.loadSelectFromArray('https://www.hgverwaltung.ch/api/1/' + club + '/mannschaften?spiele=true', 'hg_teamSelect', true, getData);

		var hgDataTable = document.getElementById("hg_data");
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

		function getData() {
            var jahr = document.getElementById('hg_jahrSelect').value;
			var teams = Array.prototype.slice.call(document.querySelectorAll('#hg_teamSelect option:checked'), 0).map(function (v) {
				return v.value;
			});

			if (jahr && teams && teams.length > 0) {
				var url = 'https://www.hgverwaltung.ch/api/1/' + club + '/spiele/' + teams.join(',') + '?jahr=' + jahr;
				var url_anlaesse = 'https://www.hgverwaltung.ch/api/1/' + club + '/anlaesse' + '?jahr=' + jahr + '&inklSpiele=false';

            dataList.clear(); 
               
                
            fetch(url)
                .then(function (response) { return response.json(); })
                .then(function (results) { showDataSpiele(results) });

            fetch(url_anlaesse)
                .then(function (response) { return response.json(); })
                .then(function (results) { showDataAnlaesse(results); });
               
          	}
			else {
				showDataSpiele([]);
                showDataAnlaesse([]);
			}
		}

        function showDataSpiele(results) {
           // dataList.clear();
            if (results.length === 0) {
                document.getElementById('hg_data').style.display = 'none';
                return;
            }
            document.getElementById('hg_data').style.display = '';

            results.forEach(function (row) {
                row.datumDisplay = row.datum.substring(8, 10) + '.' + row.datum.substring(5, 7) + '.' + row.datum.substring(0, 4);
                row.zeit = row.datum.substring(11);

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

        function showDataAnlaesse(results) {
            
            var weekday = new Array(7);
             weekday[0] = "Sonntag";
             weekday[1] = "Montag";
             weekday[2] = "Dienstag";
             weekday[3] = "Mittwoch";
             weekday[4] = "Donnerstag";
             weekday[5] = "Freitag";
             weekday[6] = "Samstag";
            
            
            //dataList.clear();

            if (results.length === 0) {
                document.getElementById('hg_data').style.display = 'none';
                return;
            }
            document.getElementById('hg_data').style.display = '';

            results.forEach(function (row) {
                row.datumDisplay = row.datum.substring(8, 10) + '.' + row.datum.substring(5, 7) + '.' + row.datum.substring(0, 4);
                row.zeit = row.datum.substring(11);

                row.spielort = row.ort;
                row.ort = "";

                row.art = row.anlass;
                var d = new Date(row.datum.substring(0, 4),row.datum.substring(5, 7) -1 ,row.datum.substring(8, 10));
                row.wochentag = weekday[d.getDay()];

                // Zeit ausblenden wenn 00:00
				if (row.datum.substring(11) != '00:00') {
					row.zeit = row.datum.substring(11);
				}
				else {
					row.zeit = '';
				};
            });
            dataList.add(results);
            
           
            //dataList.sort('datum', { order: "asc" });
        }

       
	})();

</script>