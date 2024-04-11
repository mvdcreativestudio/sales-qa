@extends('layouts/layoutMaster')

@section('title', 'Omnicanalidad')

@section('vendor-style')
  @vite('resources/assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.scss')
@endsection

@section('page-style')
  @vite('resources/assets/vendor/scss/pages/app-chat.scss')
@endsection

@section('vendor-script')
  @vite('resources/assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js')
@endsection

@section('page-script')
  @vite('resources/assets/js/app-chat.js')
@endsection

@section('content')
<div class="app-chat overflow-hidden card">
  <div class="row g-0">
    <!-- Sidebar Left -->
    <div class="col app-chat-sidebar-left app-sidebar overflow-hidden" id="app-chat-sidebar-left">
      <div class="chat-sidebar-left-user sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap p-4 mt-2">
        <div class="avatar avatar-xl avatar-online">
          <img src="https://ui-avatars.com/api/?background=random&name={{ urlencode(auth()->user->name ?? 'NA') }}" alt="User Avatar" class="rounded-circle">
        </div>
      </div>
    </div>
    <!-- /Sidebar Left-->

    <!-- Chat & Contacts -->
    <div class="col app-chat-contacts app-sidebar flex-grow-0 overflow-hidden border-end" id="app-chat-contacts">
      <div class="sidebar-header pt-3 px-3 mx-1">
        <div class="d-flex align-items-center me-3 me-lg-0">
          <div class="flex-shrink-0 avatar avatar-online me-2" data-bs-toggle="sidebar" data-overlay="app-overlay-ex" data-target="#app-chat-sidebar-left">
            <img src="https://ui-avatars.com/api/?background=random&name={{ urlencode(auth()->user()->name ?? 'NA') }}" alt="User Avatar" class="rounded-circle">
          </div>
          <div class="flex-grow-1 input-group input-group-merge rounded-pill ms-1">
            <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search fs-4"></i></span>
            <input type="text" class="form-control chat-search-input" placeholder="Buscar..." aria-label="Buscar..." aria-describedby="basic-addon-search31">
          </div>
        </div>
        <i class="bx bx-x cursor-pointer position-absolute top-0 end-0 mt-2 me-1 fs-4 d-lg-none d-block" data-overlay data-bs-toggle="sidebar" data-target="#app-chat-contacts"></i>
      </div>
      <hr class="container-m-nx mt-3 mb-0">
      <div class="sidebar-body">
        <!-- Chats -->
        <ul class="list-unstyled chat-contact-list pt-1" id="chat-list">
          <li class="chat-contact-list-item chat-contact-list-item-title">
            <h5 class="text-primary mb-0">Conversaciones</h5>
          </li>
          @forelse ($chats as $chat)
            <li class="chat-contact-list-item" data-phone-number-id="{{$chat->sender->phone_id}}" data-contact-name="{{$chat->sender->phone_number_owner}}">
              <a class="d-flex align-items-center">
                <div class="flex-shrink-0 avatar avatar-online">
                  <img src="https://ui-avatars.com/api/?background=random&name={{ urlencode($chat->sender->phone_number_owner ?? 'NA') }}" alt="Avatar" class="rounded-circle">
                </div>
                <div class="chat-contact-info flex-grow-1 ms-3">
                  @php
                    $messagePreview = '';
                    switch ($chat->message_type) {
                        case 'image':
                            $messagePreview = '📷 ' . ($chat->message_text ?: 'Imagen');
                            break;
                        case 'audio':
                            $messagePreview = '🔊 ' . ($chat->message_text ?: 'Audio');
                            break;
                        case 'document':
                            $messagePreview = '📄 ' . ($chat->message_text ?: 'Documento');
                            break;
                        case 'video':
                            $messagePreview = '🎥 ' . ($chat->message_text ?: 'Video');
                            break;
                        case 'sticker':
                            $messagePreview = '🌟 ' . ($chat->message_text ?: 'Sticker');
                            break;
                        default:
                            $messagePreview = $chat->message_text;
                    }
                  @endphp
                  <h6 class="chat-contact-name text-truncate m-0">{{ $chat->sender->phone_number_owner ?? 'Desconocido' }}</h6>
                  <p class="chat-contact-status text-truncate mb-0 text-muted">{{ $messagePreview }}</p>
                </div>
                <small class="text-muted mb-auto">{{ $chat->message_created->diffForHumans() }}</small>
              </a>
            </li>
          @empty
            <li class="chat-contact-list-item chat-list-item-0">
              <h6 class="text-muted mb-0">No se encontraron conversaciones</h6>
            </li>
          @endforelse
        </ul>


      </div>
    </div>
    <!-- /Chat contacts -->

    <!-- Chat History -->
    <div class="col app-chat-history">
      <div class="chat-history-wrapper">
        <div class="chat-history-header border-bottom">
          <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex overflow-hidden align-items-center">
              <i class="bx bx-menu bx-sm cursor-pointer d-lg-none d-block me-2" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-contacts"></i>
              <div class="flex-shrink-0 avatar avatar-header">
                <img src="https://ui-avatars.com/api/?background=random&name=!" alt="Avatar" class="rounded-circle" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right">
              </div>
              <!-- Chat Header -->
              <div class="chat-contact-info chat-header-info flex-grow-1 ms-3">
                <h6 class="m-0">Seleccioná un chat para comenzar</h6>
              </div>
              <!-- Chat Header -->
            </div>
          </div>
        </div>
        <div class="chat-history-body">
          <ul class="list-unstyled chat-history mb-0">
            <li class="chat-message chat-message-right">
              <div class="d-flex overflow-hidden">
                <div class="chat-message-wrapper flex-grow-1">
                  <div class="chat-message-text">
                    <p class="mb-0">Empezá a chatear seleccionando un chat en el listado de la izquierda !</p>
                  </div>
                  <div class="text-end text-muted mt-1">
                    <i class='bx bx-check-double text-success'></i>
                    <small>10:00 AM</small>
                  </div>
                </div>
                <div class="user-avatar flex-shrink-0 ms-3">
                  <div class="avatar avatar-sm">
                    <img src="https://ui-avatars.com/api/?background=random&name=¿?" alt="Avatar" class="rounded-circle">
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <!-- Chat message form -->
        <div class="chat-history-footer">
          <form class="form-send-message d-flex justify-content-between align-items-center ">
            <input class="form-control message-input border-0 me-3 shadow-none" placeholder="Escriba su mensaje aquí...">
            <div class="message-actions d-flex align-items-center">
              <button class="btn btn-primary d-flex send-msg-btn">
                <i class="bx bx-paper-plane me-md-1 me-0"></i>
                <span class="align-middle d-md-inline-block d-none">Enviar</span>
              </button>
            </div>
          </form>
        </div>
        <!-- Chat message form -->
      </div>
    </div>
    <!-- /Chat History -->
    <div class="app-overlay"></div>
  </div>
</div>

<script>
  function loadChatMessages(phoneNumberId, contactName, contactAvatarUrl, userAvatarUrl) {
      $.ajax({
          url: '{{ route('omnichannel.fetch.messages') }}',
          method: 'GET',
          data: { phone_number: phoneNumberId },
          success: function(response) {
              var chatHistoryBody = $('.chat-history-body ul');
              chatHistoryBody.empty();

              response.messages.forEach(function(message) {
                  var isSender = message.from_phone_id === '{{ auth()->user()->phone_number ?? 'user_phone_number' }}'; // Ajusta según sea necesario
                  var messageClass = isSender ? 'chat-message-right' : '';
                  var messageElement = $(`<li class="chat-message ${messageClass}"></li>`);

                  const avatarUrl = isSender ? userAvatarUrl : contactAvatarUrl;

                  var avatarElement = `<div class="user-avatar flex-shrink-0 ${isSender ? 'ms-3' : 'me-3'}">
                                          <div class="avatar avatar-sm">
                                              <img src="${avatarUrl}" alt="Avatar" class="rounded-circle">
                                          </div>
                                        </div>`;

                  var messageContent = getMessageContent(message);

                  var messageWrapper = `<div class="d-flex overflow-hidden">
                                            ${isSender ? '' : avatarElement}
                                            <div class="chat-message-wrapper flex-grow-1">
                                                <div class="chat-message-text">
                                                    ${messageContent}
                                                </div>
                                                <div class="text-${isSender ? 'end' : ''} text-muted mt-1">
                                                    <small>${new Date(message.message_created).toLocaleTimeString()}</small>
                                                </div>
                                            </div>
                                            ${isSender ? avatarElement : ''}
                                        </div>`;

                  messageElement.append(messageWrapper);
                  chatHistoryBody.append(messageElement);
              });

              $('.chat-history-body').scrollTop($('.chat-history-body')[0].scrollHeight);
          },
          error: function(error) {
              console.error("Error cargando los mensajes: ", error);
          }
      });
  }


  function getMessageContent(message) {
      switch (message.message_type) {
          case 'text':
              return `<p>${message.message_text}</p>`;
          case 'image':
              return `<img src="${message.image_url}" alt="Imagen" style="max-width: 100%; height: auto;">`;
          case 'audio':
              return `<audio controls src="${message.audio_url}"></audio>`;
          case 'document':
              return `<a href="${message.document_url}" target="_blank">Documento</a>`;
          case 'video':
              return `<video controls src="${message.video_url}" style="max-width: 100%;"></video>`;
          case 'sticker':
              return `<img src="${message.sticker_url}" alt="Sticker" style="max-width: 100%; height: auto;">`;
          default:
              return `<p>${message.message_text}</p>`;
      }
  }

  const chatListItems = document.querySelectorAll('.chat-contact-list-item');

  chatListItems.forEach(function(item) {
    item.addEventListener('click', function() {
        const phoneNumberId = this.getAttribute('data-phone-number-id');
        const contactName = this.getAttribute('data-contact-name');
        const contactAvatarUrl = `https://ui-avatars.com/api/?background=random&name=${encodeURIComponent(contactName)}`;
        const userAvatarUrl = `https://ui-avatars.com/api/?background=random&name={{ urlencode(auth()->user()->name ?? 'NA') }}`;

        const avatarElement = document.querySelector('.avatar-header img');
        avatarElement.src = contactAvatarUrl;

        const chatHeaderInfo = document.querySelector('.chat-header-info h6');
        chatHeaderInfo.textContent = contactName;

        loadChatMessages(phoneNumberId, contactName, contactAvatarUrl, userAvatarUrl);
    });
  });
</script>
@endsection
