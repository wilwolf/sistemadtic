@extends(backpack_view('layouts.plain'))

@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-3" style="text-align: center">
					<img alt="Ingenieria de Sistemas" src="{{ url('uploads\sistemaimg\sistemas.png') }}" />
                    <img alt="DTICs" src="{{ url('uploads\sistemaimg\dtic.png') }}" />
				</div>
                <div class="col-md-9">
					<blockquote class="blockquote">
                        <p class="mb-0">
                            {{ $certificado[0]->titulo }}
                        </p>
                        <footer class="blockquote-footer">
                            {{ $certificado[0]->tipoe }} |<cite>Versión  {{ $certificado[0]->version.' - '.$certificado[0]->modalidad }}</cite>
                        </footer>
                    </blockquote>
                    <h3 class="text-center">
                        {{ $certificado[0]->nombre.' '.$certificado[0]->apellidos.' | '.$certificado[0]->carnet }}.
                    </h3>
                    <div style="margin-left: 15px;" >
                        <dl>
                            <dt>
                                Estado
                            </dt>
                            <dd>
                                Nota de {{ $certificado[0]->nota }} sobre 100 puntos.
                                @if($certificado[0]->nota<71)
                                    <button type="button" class="btn btn-danger">No alcanzo la nota para aprobar</button>
                                @else
                                    <button type="button" class="btn btn-success">Aprobado</button>
                                @endif
                            </dd>
                            <dt>
                                Fecha de realización 
                            </dt>
                            <dd>
                                Del  {{ $certificado[0]->inicio.' al '.$certificado[0]->fin  }}
                            </dd>
                            <dt>
                                Horas académicas: <button type="button" class="btn btn-primary">{{ $certificado[0]->cargah }}</button>
                            </dt>
                        </dl>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
