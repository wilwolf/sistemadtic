
<div style="text-align: justify">
    <span style="position: absolute;   ;
    top: 180; left: 185; right:30; height: 40px; font-size: 17px;font-family: sans-serif !important;">
        El Departamento de Tecnologias de la Información y Comunicación dependiente de la Carrera de Ingenieria de Sistemas de la Universidad Pública de El Alto, otroga el presente Certificado a:
    </span>
</div>
<div style="text-align: center">
<span style="position: absolute;   ;
top: 228; left: 185; width: 680px; height: 40px; font-size: 35px; font-weight:bold; font-family: helvetica !important;
}}">{{ucwords(mb_strtolower($certificado[0]->nombre.' '.$certificado[0]->apellidos)) }}</span>
</div>
<div style="text-align: center">
    <span style="position: absolute;   ;
    top: 260; left: 185; width: 680px; height: 40px; font-size: 28px;">
        {!! '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG(Hashids::encode($certificado[0]->id,$certificado[0]->evento,$certificado[0]->estudiante), 'C39+') . '" alt="barcode"   />'; !!}
    </span>
</div>
@if((int)$certificado[0]->nota >= 71)
<span style="position: absolute;   ;
top: 285; left: 185; width: 680px; height: 20px; font-size: 17px;font-family: sans-serif !important;"> Por haber <b>aprobado</b> satisfactoriamente los requisitos establecidos en el Programa Académico, obteniendo un puntaje de: <b>{{ $certificado[0]->nota }}/100 </b> puntos.</span>
@else
<span style="position: absolute;   ;
top: 295; left: 185; width: 680px; height: 20px; font-size: 17px;font-family: sans-serif !important;"> Por haber <b>participado</b>, cumpliendo con el avance de los contenidos del  Programa Académico:</span>
@endif
<div style="text-align: center">
    <span style="position: absolute;   ;
    top: 313; left: 185; width: 680px; height: 40px; font-size: 26px;">{{ $certificado[0]->tipoe }}</span>
</div>

<div style="text-align: center">
    <span style="position: absolute;   ;
        top: 335; left: 185; width: 680px; height: 40px; font-size: 28px;">{{ $certificado[0]->titulo }}</span>
</div>
<div style="text-align: center">
    <span style="position: absolute;   ;
        top: 360; left: 185; width: 680px; height: 40px; font-size: 18px;">{{ 'Version '.$certificado[0]->version.' - '.$certificado[0]->modalidad }}  </span>
</div>
<span style="position: absolute;   ;
top: 380; left: 185; width: 680px; height: 40px; font-size: 17px;font-family: sans-serif !important;"> Con una carga horaria de {{ $certificado[0]->cargah }} horas académicas, llevado a cabo del {{ \Carbon\Carbon::parse($certificado[0]->inicio)->isoFormat('DD [de] MMMM  ') }} hasta el {{ \Carbon\Carbon::parse($certificado[0]->fin)->isoFormat('DD [de] MMMM  YYYY')  }}.</span>
<div style="text-align:right">
    <span style="position: absolute;   ;
        top: 410; left: 185; width: 680px; height: 40px; font-size: 17px; font-family: sans-serif !important">El Alto, {{ \Carbon\Carbon::now()->isoFormat('DD [de] MMMM  YYYY') }}, </span>
</div>
<div style="text-align:center">
    <span style="position: absolute;   ;
        top: 480; left: 175; width: 180px; height: 40px; font-size: 12px; font-family: sans-serif !important"> {{ $certificado[0]->docente }}<br/>Facilitador(a) </span>
</div>
<span style="position: absolute;   ;
top: 430; left: 600; right:20; height: 40px; font-size: 17px;">
    <img src="data:image/png;base64,{{base64_encode(QrCode::format('png')->size(120)->generate(url('/verificar').'/'.Hashids::encode($certificado[0]->id,$certificado[0]->evento,$certificado[0]->estudiante))) }} "
</span>

<!--
Developer: Grover Wilson Quisbert Ibañez
-->
     