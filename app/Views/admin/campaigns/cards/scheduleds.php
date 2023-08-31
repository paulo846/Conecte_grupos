<?= $this->section('cssLink') ?>
<!-- multi.js css -->
<link rel="stylesheet" type="text/css" href="/assets/libs/multi.js/multi.min.css" />
<!-- autocomplete css -->
<link rel="stylesheet" href="/assets/libs/@tarekraafat/autocomplete.js/css/autoComplete.css">
<!-- datepiker -->
<?= $this->endSection() ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0 flex-grow-1">
                    Configure seu envio.
                    <br>
                    instância <b class="text-info"><?= $rowInstance['profile_name'] ?></b> selecionada
                </h4>
            </div><!-- end card header -->
            <div class="card-body">
                <?= form_open('api/v1/groups/scheduleds', 'id="sendMessage"') ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="title">Nome do agendamento</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Nome do agendamento" required>
                        </div>
                        <div class="mb-3">
                            <label for="data-hora">Agendamento</label>
                            <input type="text" class="form-control" placeholder="dd/mm/yy hh:mm" name="agendamento" id="data-hora" min="" required>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label for="multiselect-optiongroup">Escolha os grupos</label>
                            <select multiple="multiple" name="groups[]" id="multiselect-optiongroup" required style="display: none;">
                                <optgroup label="Proprietário">
                                    <?php foreach ($rowGroup as $list) : ?>
                                        <?php if ($list['owner'] == $rowInstance['owner']) : ?>
                                            <option value="<?= $list['id_group'] ?>"><?= $list['subject'] ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="Participante">
                                    <?php foreach ($rowGroup as $list) : ?>
                                        <?php if ($list['owner'] != $rowInstance['owner']) : ?>
                                            <option value="<?= $list['id_group'] ?>"><?= $list['subject'] ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                            <div class="mt-3 alert alert-info alert-dismissible bg-info text-white alert-label-icon fade show" role="alert">
                                <i class="ri-user-smile-line label-icon"></i><strong>Ei</strong> para selecionar os grupos, clique sobre eles. Os grupos para os quais as mensagens serão enviadas estão localizados à direita.
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="message">Escreva a mensagem</label>
                            <textarea class="form-control" name="message" id="message" cols="30" rows="5" required></textarea>
                        </div>
                        <div class="mb-1">
                            <label for="archive">Para enviar um arquivo (imagem, foto, video, audio), preencha com o link do arquivo</label>
                            <input type="url" class="form-control" name="archive" id="archive" placeholder="<?= site_url('arquivo/image.pdf') ?>">
                            <!-- Primary Alert -->
                            <div id="errorMessage" style="display: none;" class="mt-2 alert alert-danger alert-dismissible bg-danger text-white alert-label-icon fade show" role="alert">
                                <i class="ri-user-smile-line label-icon"></i> <span id="errorMessageText"></span>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mentions"></label>
                            <div class="form-check form-switch form-switch-lg" dir="ltr">
                                <input type="checkbox" class="form-check-input" id="mentions" name="mentions">
                                <label class="form-check-label" for="mentions">Mencionar participantes (Use com moderação)</label>
                            </div>
                        </div>
                        <input type="hidden" name="apiurl" value="<?= $rowInstance['server_url'] ?>">
                        <input type="hidden" name="instance" value="<?= $rowInstance['name'] ?>">
                        <input type="hidden" name="apikey" value="<?= $rowInstance['api_key'] ?>">
                        <input type="hidden" name="idInstance" value="<?= $rowInstance['id'] ?>">
                        <div class="text-end">
                            <button type="submit" class="btn btn-info" id="sendButton">Agendar</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>

<!-- multi.js -->
<script src="/assets/libs/multi.js/multi.min.js"></script>
<!-- autocomplete js -->

<!-- input spin init -->
<script src="/assets/js/pages/form-input-spin.init.js"></script>
<!-- input flag init -->
<script src="/assets/js/pages/flag-input.init.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>


<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/l10n/pt.js"></script>
<script src="https://malsup.github.io/jquery.form.js"></script>
<script>
    $(document).ready(function() {
        //
        var multiSelectOptGroup = document.getElementById("multiselect-optiongroup");
        if (multiSelectOptGroup) {
            multi(multiSelectOptGroup, {
                enable_search: true
            });
        }

        flatpickr('#data-hora', {
            enableTime: true,
            dateFormat: 'd/m/Y H:i',
            minDate: 'today',
            time_24hr: true,
            minuteIncrement: 5,
            locale: 'pt'
        });

        // Quando o formulário for enviado
        $('#sendMessage').ajaxForm({
            beforeSubmit: function(formData, jqForm, options) {
                // Mostrar notificação de envio
                Toastify({
                    text: "Registrando agendamento!",
                    duration: 3000,
                    style: {
                        background: "linear-gradient(to right, #0011ff, #1d5d8f)",
                    },
                }).showToast();
            },
            success: function(responseText, status, xhr, $form) {
                // Esconder a notificação de envio e mostrar notificação de sucesso
                Toastify({
                    text: "Agendamento registrado com sucesso!",
                    duration: 5000,
                    style: {
                        background: "linear-gradient(to right, #569701, #2e8f1d)",
                    },
                }).showToast();
                // Limpar o formulário
                $('#sendMessage')[0].reset();
            },
            error: function(xhr, status, error) {
                // Esconder a notificação de envio e mostrar notificação de erro
                Toastify({
                    text: `Houve um problema ao agendar, verifique a data!\n Error: ${error}`,
                    duration: 7000,
                    style: {
                        background: "linear-gradient(to right, #ff3838, #ff3e3e)",
                    },
                }).showToast();
            }
        });

        var allowedExtensions = ['jpg', 'png', 'mp4', 'pdf', 'xlsx', 'zip', 'mp3', 'jpeg'];
        
        $('#archive').on('input', function() {
            var url = $(this).val();
            var extension = url.split('.').pop().toLowerCase();
            if ($.inArray(extension, allowedExtensions) === -1) {
                $('#errorMessage').show();
                $('#errorMessageText').text('Extensão inválida para envio. As extensões permitidas são: <br><b>' + allowedExtensions.join(', ')) + '</b>';
                //$(this).val('');
                $('#sendButton').prop('disabled', true); // Desabilita o botão
            } else {
                $('#errorMessage').hide();
                $('errorMessageText').text('');
                $('#sendButton').prop('disabled', false); // Desabilita o botão
            }
        });

    });
</script>

<?= $this->endSection() ?>