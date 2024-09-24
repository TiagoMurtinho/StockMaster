<div class="modal fade" id="modalGuiaTransporte" tabindex="-1" aria-labelledby="modalGuiaTransporteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGuiaTransporteModalLabel">{{__('documento.novo_documento')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="documentoForm" {{--action="{{ route('documento.store') }}--}}" method="POST">
                @csrf
                <div class="modal-body modal-documento">

                        <div class="mb-3">
                            <label for="matricula" class="form-label">{{__('documento.add_matricula')}}</label>
                            <input type="text" class="form-control" id="matricula" name="matricula" required>
                        </div>

                        <div class="mb-3">
                            <label for="morada" class="form-label">{{__('documento.add_morada')}}</label>
                            <input type="text" class="form-control" id="morada" name="morada">
                        </div>

                        <div class="mb-3">
                            <label for="hora_carga" class="form-label">{{__('documento.add_hora_carga')}}</label>
                            <input type="datetime-local" class="form-control" id="hora_carga" name="hora_carga" required>
                        </div>

                        <div class="mb-3">
                            <label for="descarga" class="form-label">{{__('documento.add_descarga')}}</label>
                            <input type="datetime-local" class="form-control" id="descarga" name="descarga">
                        </div>
                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('documento.cancelar')}}</button>
                    <button type="button" id="continuarModalLinhaDocumentoBtn" class="btn btn-primary">{{__('documento.continuar')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
