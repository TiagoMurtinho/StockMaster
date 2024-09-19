<div class="modal fade" id="documentoModal" tabindex="-1" aria-labelledby="documentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentoModalLabel">{{ __('documento.detalhes_documento') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Informações do Documento -->
                <h6>{{ __('documento.numero') }} <span id="modal-documento-numero"></span></h6>
                <h6>{{ __('documento.data') }} <span id="modal-documento-data"></span></h6>
                <!-- Outros campos do documento -->

                <!-- Tabela com linhas do documento -->
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('documento.tipo_palete') }}</th>
                        <th>{{ __('documento.quantidade') }}</th>
                        <th>{{ __('documento.artigo') }}</th>
                    </tr>
                    </thead>
                    <tbody id="modal-linhas">
                    <!-- As linhas serão preenchidas via JavaScript -->
                    </tbody>
                </table>

                <!-- Adicionar campos editáveis se necessário -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('documento.fechar') }}</button>
                <button type="button" class="btn btn-primary">{{ __('documento.salvar_alteracoes') }}</button>
            </div>
        </div>
    </div>
</div>
