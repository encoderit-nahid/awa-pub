<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <style>

    @page { margin: 100px 25px; }
    footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 70px; text-align: center;}
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }


    table {
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
    }
    thead{

    }
    .right-text{
        text-align: left;
        display: block;
        overflow: hidden;
    }
    .block{
        display: block;
        overflow: hidden;
        text-align: right;
    }
    </style>

    <footer>
        Mag. Bianca Lehrner
        <br>
        Himmelpfortgasse 20 – 1010 Wien | Tel. 0650 58 00 550 | ATU 75114939
        <br>
        office@austrianweddingaward.at - www.austrianweddingaward.at
    </footer>
    <main>
        <div class="block">
            <br>
            <img src="logo_sml.jpg">
            <br><br>
            Wien, {{$date}}
            <br>
            @php
                $invoice_number = $invoice->id+235;
            @endphp
            @if($invoice_number < 10)
                AWA-{{$year}}-00{{$invoice_number}}
            @elseif($invoice_number < 100)
                AWA-{{$year}}-0{{$invoice_number}}
            @else
                AWA-{{$year}}-{{$invoice_number}}
            @endif


            <br>
            <br>
        </div>

        <div class="right-text">
            {{$cat->name}}
            <br>
            {{$cat->code}}
            <br>
        </div>

        <br>
        <br>
        <br>

{{--         <div>
			Sehr geehrte/r ,<br><br>
            vielen Dank für Ihre Teilnahme am Austrian Wedding Award. Wir freuen uns sehr, dass Sie Ihre kreativen Beiträge eingereicht haben und wünschen Ihnen schon jetzt viel Erfolg beim
            Austria Wedding Award 2020.
        </div> --}}

        <br>
        <br>
        <br>

        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>Nr</th>
					<th>Projektname + Kategorie</th>
                    <th>Gruppe</th>
                    <th>Ust</th>
                    <th>Preis</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $group_array = [];
                    $netto = 0;
                    $final_netto = 0;
                    $final_vat = 0;
                    $first_project = true;
                @endphp
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$project->projektname}} - {{$project->cat_name}}</td>
                        <td>{{$project->group}}</td>
                        <td>20%</td>
                        <td>
                            @php
                                if(in_array($project->group, $group_array)){
                                    $price = 30;
                                }elseif($first_project){
                                  if ($project->free==1) {

                                    $price = 0;
                                    array_push($group_array, $project->group);
                                    $first_project = false;

                                  } else {

                                    $price = 80;
                                    array_push($group_array, $project->group);
                                    $first_project = false;

                                  }
                                  //  $price = 80;

                                }else{
                                    $price = 50;
                                    array_push($group_array, $project->group);
                                }
                            @endphp
                            € {{$price}}
                        </td>
                    </tr>
                    @if($project->service==1)
                        <tr>
                            <td> </td>
                            <td>Upload Service</td>
                            <td></td>
                            <td></td>
                            <td>
                                € 15
                            </td>
                        </tr>
                        @php
                            $netto = $netto+$price+15;
                        @endphp
                    @else
                        @php
                            $netto = $netto+$price;
                        @endphp
                    @endif
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Netto</td>
                    <td>
                        € {{$final_netto = round($netto)}}
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Ust 20%</td>
                    <td>
                        € {{$final_vat = round($netto*.20)}}
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Gesamt</td>
                    <td>
                        € {{$final_netto + $final_vat}}
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <div>
            Wir ersuchen Sie um Überweisung des Gesamtbetrags innerhalb von 7 Tagen auf unser Konto bei der Ersten Bank: <b>AT60 2011 1292 4654 2804</b>
        </div>
        <br>
        <br>
        Herzliche Grüße
        <br>
        Susanne Hummel & Bianca Lehrner
    </main>


</body>
</html>
