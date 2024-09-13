<div class="modal fade" id="modalDocumento" tabindex="-1" aria-labelledby="modalDocumentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDocumentoLabel">{{__('documento.novo_documento')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="documentoForm">

                    <div class="mb-3">
                        <label for="numero" class="form-label">{{__('documento.add_numero')}}</label>
                        <input type="text" class="form-control" id="numero" name="numero" required>
                    </div>

                    <div class="mb-3" id="dataField">
                        <label for="data" class="form-label">{{__('documento.add_data')}}</label>
                        <input type="datetime-local" class="form-control" id="data" name="data" required>
                    </div>

                    <div class="mb-3" id="matriculaField">
                        <label for="matricula" class="form-label">{{__('documento.add_matricula')}}</label>
                        <input type="text" class="form-control" id="matricula" name="matricula" required>
                    </div>

                    <div class="mb-3" id="moradaField">
                        <label for="morada" class="form-label">{{__('documento.add_morada')}}</label>
                        <input type="text" class="form-control" id="morada" name="morada">
                    </div>

                    <div class="mb-3" id="horaCargaField">
                        <label for="hora_carga" class="form-label">{{__('documento.add_hora_carga')}}</label>
                        <input type="datetime-local" class="form-control" id="hora_carga" name="hora_carga" required>
                    </div>

                    <div class="mb-3" id="descargaField">
                        <label for="descarga" class="form-label">{{__('documento.add_descarga')}}</label>
                        <input type="datetime-local" class="form-control" id="descarga" name="descarga">
                    </div>

                    <div class="mb-3" id="totalField">
                        <label for="total" class="form-label">{{__('documento.add_total')}}</label>
                        <input type="text" class="form-control" id="total" name="total" required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('documento.cancelar')}}</button>
                <button type="button" id="continuarModalLinhaDocumentoBtn" class="btn btn-primary">{{__('documento.continuar')}}</button>
            </div>
        </div>
    </div>
</div>
