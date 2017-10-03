<html>
<style>
    table {
        margin-bottom: 5px;
        font-size: 10px;
    }

    table, tr, td, th {
        /*border-spacing: 0;*/
        border-collapse: collapse;
        border: 1px solid #000;
        position: relative;
        padding: 6px;
    }

    td span {
        transform-origin: 0 50%;
        transform: rotate(-90deg);
        white-space: nowrap;
        display: block;
        position: absolute;
        bottom: 0;
        left: 50%;
    }

    .center {
        text-align: center;
    }

    .pagebreak {
        /*height: 2px;*/
        page-break-before: always;
    }
</style>
<body>
<table>
    <thead>
    @php
        $meses = [
            '01' => 'janeiro',
            '02' => 'fevereiro',
            '03' => 'março',
            '04' => 'abril',
            '05' => 'maio',
            '06' => 'junho',
            '07' => 'julho',
            '08' => 'agosto',
            '09' => 'setembro',
            '10' => 'outubro',
            '11' => 'novembro',
            '12' => 'dezembro'
        ];
    @endphp
    </thead>
</table>
</body>
</html>