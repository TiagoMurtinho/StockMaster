<div class="modal fade" id="rececaoModal" tabindex="-1" aria-labelledby="rececaoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg rececao-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rececaoModalLabel">{{ __('Verificação de Paletes para o Pedido') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none error-messages" role="alert"></div>
                <form id="modalRececaoForm" action="{{ route('palete.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="documento_id" id="documento_id">
                    <input type="hidden" name="cliente_id" id="cliente_id">

                    <div class="mb-3">
                        <label for="observacao" class="form-label">{{ __('Observação (opcional)') }}</label>
                        <input type="text" name="observacao" id="observacao" class="form-control" placeholder="Escreva aqui as suas observações">
                    </div>
                    <div class="scrollable-palete-area" id="palete-area">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('Tipo de Palete') }}</th>
                                <th scope="col">{{ __('Palete #') }}</th>
                                <th scope="col">{{ __('Localização') }}</th>
                                <th scope="col">{{ __('Armazém') }}</th>
                            </tr>
                            </thead>
                            <tbody id="rececao-body">
                            <!-- Paletes são carregadas aqui via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary submit-btn">
                            <span class="submit-btn-text">{{ __('Confirmar Verificação') }}</span>
                            <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script id="armazem-options" type="application/json">
    @json($armazens)
</script>
