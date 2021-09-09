<div class="m-t-10 m-b-10 p-l-10 p-r-10 p-t-10 p-b-10">
	<div class="row">
		<div class="col-md-12">
			<strong>{{ Hashids::encode($entry->id, $entry->id_evento, $entry->id_estudiante); }}</strong>
			<br/>

			{!! QrCode::size(100)->generate('ItSolutionStuff.com/'.Hashids::encode($entry->id, $entry->id_evento, $entry->id_estudiante)); !!}
				<br/>
				<br/>
				<br/>
			 {!! '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG(Hashids::encode($entry->id, $entry->id_evento, $entry->id_estudiante), 'C39+') . '" alt="barcode"   />'; !!} 

			
			</span>	
		</div>
	</div>
</div>
<div class="clearfix"></div>