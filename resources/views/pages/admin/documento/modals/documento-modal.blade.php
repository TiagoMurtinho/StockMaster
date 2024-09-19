<div class="modal fade" id="documentoModal" tabindex="-1" aria-labelledby="documentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentoModalLabel">{{ __('documento.detalhes_documento') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-documento-id" />

                <div class="form-group">
                    <label for="modal-documento-numero">Número</label>
                    <input type="text" id="modal-documento-numero" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="modal-documento-data">Data</label>
                    <input type="date" id="modal-documento-data" class="form-control" />
                </div>

                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('documento.tipo_palete') }}</th>
                        <th>{{ __('documento.quantidade') }}</th>
                        <th>{{ __('documento.artigo') }}</th>
                    </tr>
                    </thead>
                    <tbody id="modal-linhas">

                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('documento.fechar') }}</button>
                <button type="button" class="btn btn-primary" onclick="saveChanges()">{{ __('documento.salvar_alteracoes') }}</button>
            </div>
        </div>
    </div>
</div>
