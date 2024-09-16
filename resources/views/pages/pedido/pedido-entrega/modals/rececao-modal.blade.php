@foreach($documentos as $documento)
    @foreach($documento->linha_documento as $linha)
        <div class="modal fade" id="rececaoModal{{ $linha->id }}" tabindex="-1" aria-labelledby="rececaoModalLabel{{ $linha->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rececaoModalLabel{{ $linha->id }}">{{ __('Verificação de Paletes para o Pedido') }} {{ $documento->numero }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="rececao-form" action="{{ route('palete.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="linha_documento_id" value="{{ $linha->id }}">
                            <input type="hidden" name="cliente_id" value="{{ $documento->cliente_id }}">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('Tipo de Palete') }}</th>
                                    <th scope="col">{{ __('Palete #') }}</th>
                                    <th scope="col">{{ __('Localização') }}</th>
                                    <th scope="col">{{ __('Artigo') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($linha->tipo_palete as $tipoPalete)
                                    @for($i = 1; $i <= $tipoPalete->pivot->quantidade; $i++)
                                        <tr>
                                            <td>{{ $tipoPalete->tipo }}</td>
                                            <td>{{ $i }}</td>
                                            <td>
                                                <input type="text" name="localizacao[{{ $tipoPalete->id }}][]" class="form-control" placeholder="Localização">
                                            </td>
                                            <td>
                                                <select name="artigo_id[{{ $tipoPalete->id }}][]" class="form-control">
                                                    @foreach($artigosPorCliente[$documento->cliente_id] ?? [] as $artigo)
                                                        <option value="{{ $artigo->id }}">{{ $artigo->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <input type="hidden" name="tipo_palete_id[{{ $tipoPalete->id }}]" value="{{ $tipoPalete->id }}">
                                        </tr>
                                    @endfor
                                @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#tipoDocumentoModal" >{{ __('Confirmar Verificação') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach
