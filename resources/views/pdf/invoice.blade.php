<!DOCTYPE html>
<html>

<head>

</head>

<body>
<style>
    @font-face {
        font-family: 'Calibri';
        src: url({{ storage_path('fonts/Calibri.ttf') }}) format("truetype");
        font-weight: normal;
        font-style: normal;
        font-size: 12px !important;
    }

    body {
        font-family: "Calibri", sans-serif;
        font-size: 12pt !important;
    }

    @page {
        margin: 50px 35px;
    }

    footer {
        position: fixed;
        bottom: -60px;
        left: 0px;
        right: 0px;
        height: 70px;
        text-align: center;
    }

    p {
        page-break-after: always;
    }

    p:last-child {
        page-break-after: never;
    }


    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    thead {
    }

    .right-text {
        text-align: left;
        display: block;
        overflow: hidden;
    }

    .block {
        display: block;
        overflow: hidden;
        text-align: right;
    }
</style>

<main>
    <div class="block">
        <br>
        <img src="logo_sml.jpg">
        <br><br>
        Wien, {{ $date }}
        <br>
        @php
            $invoice_number = $invoice->id;
        @endphp
        @if ($invoice_number < 10)
            AWA-{{ $year }}-00{{ $invoice_number }}
        @elseif($invoice_number < 100)
            AWA-{{ $year }}-0{{ $invoice_number }}
        @else
            AWA-{{ $year }}-{{ $invoice_number }}
        @endif


        <br>
    </div>

    <div class="right-text">

        @if ($user->rechnungsadresse == '1')
            {{ $user->firma }} - {{ $user->atu }}
            <br>
            z.H. {{ $user->anr }} {{ $user->titel }} {{ $user->vorname }} {{ $user->name }}
            <br><br>
            {{ $user->email }}
            <br><br>
            {{ $user->adresse }}<br>
            {{ $user->plz }} {{ $user->ort }}<br>
        @else
            {{ $user->firma_re }} - {{ $user->atu }}
            <br>
            z.H. {{ $user->anr }} {{ $user->titel }} {{ $user->vorname }} {{ $user->name }}
            <br><br>
            {{ $user->email }}
            <br><br>
            {{ $user->adresse_re }}<br>
            {{ $user->plz_re }} {{ $user->ort_re }}<br>
        @endif
    </div>

    <br>
    <br>

    @php
        $YEAR = Carbon\Carbon::now()->addYear()->year;
    @endphp
    <div>
        Sehr geehrte/r {{ $user->anr }} {{ $user->name }},<br><br>
        vielen Dank für Ihre Teilnahme am Austrian Wedding Award. Wir freuen uns sehr, dass Sie Ihre kreativen
        Beiträge eingereicht haben und wünschen Ihnen schon jetzt viel Erfolg beim
        Austrian Wedding Award {{ $YEAR }}.
    </div>

    <br>
    <br>

    <table class="table table-borderless">
        <thead>
        <tr>
            <th>Nr</th>
            <th>Projektname + Kategorie</th>
            <th>Gruppe</th>
            <th></th>
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
        @foreach ($projects as $project)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $project->projektname }} - {{ $project->cat_name }}</td>
                <td>{{ $project->group }}</td>
                <td></td>
                <td>
                    {{-- // Old Logic
                    @php
                        if (in_array($project->group, $group_array)) {
                            if ($project->free == 1) {
                                $price = 0;
                            } else {
                                $price = 30;

                                if ($project->cat_id == '35' || $project->cat_id == '36') {
                                    $price = 24;
                                }
                            }
                        } elseif ($first_project) {
                            if ($project->free == 1) {
                                $price = 0;

                                array_push($group_array, $project->group);
                                $first_project = false;
                            } else {
                                $price = 80;
                                if ($project->cat_id == '35' || $project->cat_id == '36') {
                                    $price = 64;
                                }
                                array_push($group_array, $project->group);
                                $first_project = false;
                            }
                        } else {
                            $price = 50;
                            if ($project->cat_id == '35' || $project->cat_id == '36') {
                                $price = 40;
                            }
                            array_push($group_array, $project->group);
                        }
                    @endphp
                    --}}
                    @php
                        if ($first_project) {
                            $price = 100;
                            $first_project = false;
                        } else {
                            $price = 40;
                        }
                    @endphp
                    &euro; {{ $price }}
                </td>
            </tr>
            @if ($project->service == 1)
                <tr>
                    <td></td>
                    <td>Upload Service</td>
                    <td></td>
                    <td></td>
                    <td>
                        &euro; 15
                    </td>
                </tr>
                @php
                    $netto = $netto + $price + 15;
                @endphp
            @else
                @php
                    $netto = $netto + $price;
                @endphp
            @endif
        @endforeach

        <!-- Neu: Gutschein 20%-->
{{--        @if ($user->voucher != '' && $user->voucher == 'HOCHZEITCLICK15' . $YEAR)--}}
        @if ($user->voucher != '' && $user->voucher == '15HOCHZEITCLICK' . $YEAR)
            @php
                $rabatt = round($netto * 0.15, 2);
            @endphp
            <tr>
                <td></td>
                <td>Gutschein - {{ $user->voucher }}</td>
                <td></td>
                <td></td>
                <td>
                    - &euro; {{ $rabatt }}
                </td>
            </tr>
            @php
                $netto = $netto - $rabatt;
            @endphp
        @else
            @php
                $netto = $netto;
            @endphp
        @endif

        <!-- Ende Neu Gutschein -->
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Netto</td>
            <td>
                &euro; {{ $final_netto = round($netto, 2) }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Ust 20%</td>
            <td>
                &euro; {{ $final_vat = round($netto * 0.2, 2) }}
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Gesamt</td>
            <td>
                &euro; {{ $final_netto + $final_vat }}
            </td>
        </tr>
        </tbody>
    </table>
    <br>
    <div>
        {{-- Wir ersuchen Sie um Überweisung des Gesamtbetrags bis zum 05. Jänner 2023 auf unser Konto bei der Ersten Bank: <b>AT60 2011 1292 4654 2804</b> --}}
        Wir ersuchen um Überweisung des Gesamtbetrags bis zum 15. Jänner {{ $YEAR }} auf unser Konto: <br>
        <b>BIC EASYATW1</b><br>
        <b>IBAN AT591420020010437920</b>
    </div>

    <div>
        Bitte verwenden Sie die obengenannte Rechnungsnummer plus Namen als Verwendungszweck.
        <br>
        <b>Beispiel: AWA-2025-123 Max Mustermann</b><br>

{{--        <b>Herzliche Grüße</b><br>--}}
{{--        <b>Susanne Hummel & das AWA Team</b><br>--}}
    </div>
    <br>
    <br>
    Herzliche Grüße
    <br>
    {{-- Susanne Hummel & Bianca Lehrner --}}
    Susanne Hummel & das AWA Team
</main>

<footer>
    Susanne Hummel
    <br>
    Tenschertstrasse 24/8/21 – 1230 Wien | Tel. 0699 1000 86 55 | ATU63858800
    <br>
    office@austrianweddingaward.at - www.austrianweddingaward.at
</footer>


</body>

</html>
