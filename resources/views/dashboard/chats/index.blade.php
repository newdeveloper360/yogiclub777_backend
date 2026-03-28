@extends('layouts.app')
@section('title', 'Admin | Chats ')
@section('content')
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layouts.navbar')
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="card">
                                    <div class="body">
                                        <div id="plist" class="people-list">
                                            <div class="chat-search">
                                                <h4>Chats</h4>
                                            </div>
                                            <hr>
                                            <div class="m-b-20">
                                                <div id="chat-scroll">
                                                    <ul class="chat-list list-unstyled m-b-0" id="chatList">
                                                        @foreach ($chats as $chat)
                                                            <li class="chats clearfix" data-chat="{{ $chat->id }}">
                                                                <div class="row">
                                                                    <div class="col-8">
                                                                        <div class="about">
                                                                            <div class="name font-weight-bold">
                                                                                {{ $chat->user->name . ' (' . $chat->user->phone . ')' }}
                                                                            </div>
                                                                            <div class="status">
                                                                                {{ ucfirst(str_replace('_', ' ', $chat->type)) }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-4 text-right unreadMessage">
                                                                        <span
                                                                            class="badge badge-danger">{{ $chat->unread_messages > 0 ? $chat->unread_messages : '' }}</span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                        <div id="divOfChatIdsData"
                                                            data-chat-ids="{{ $chats->pluck('id')->implode(',') }}"
                                                            data-chat-exists="{{ $chats->isNotEmpty() ? 'true' : 'false' }}">
                                                        </div>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="divOfChatWall" class="col-xs-12 col-sm-12 col-md-9 col-lg-9 d-none">
                                <div class="card">
                                    <div class="chat">
                                        <div class="chat-header clearfix">
                                            <div class="chat-about">
                                                <div class="chat-with" id="headerUserName"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat-box" id="mychatbox">
                                        <div class="card-body chat-content">
                                        </div>
                                        @can('chats-send-message')
                                        <div class="card-footer chat-composer-footer">
                                            <style>
                                                .chat-composer-footer {
                                                    position: static !important;
                                                    padding: 0.75rem 1.25rem 1rem;
                                                }

                                                #chat-form {
                                                    display: flex;
                                                    flex-direction: column;
                                                    gap: 0.75rem;
                                                    margin: 0;
                                                    width: 100%;
                                                }

                                                #chat-form .composer-main {
                                                    display: flex;
                                                    flex-wrap: nowrap;
                                                    align-items: center;
                                                    justify-content: space-between;
                                                    gap: 0.5rem;
                                                    width: 100%;
                                                }

                                                #chat-form #chat-message-input {
                                                    flex: 1 1 auto;
                                                    min-width: 0;
                                                    position: static !important;
                                                    margin: 0 !important;
                                                }

                                                #chat-form .composer-actions {
                                                    display: flex;
                                                    flex-wrap: nowrap;
                                                    align-items: center;
                                                    justify-content: flex-start;
                                                    align-self: center;
                                                    gap: 0.5rem;
                                                    flex: 0 0 auto;
                                                    margin-top: 0 !important;
                                                }

                                                #chat-form .composer-actions,
                                                #chat-form .composer-actions * {
                                                    top: auto !important;
                                                    right: auto !important;
                                                    bottom: auto !important;
                                                    left: auto !important;
                                                    transform: none !important;
                                                    float: none !important;
                                                }

                                                #chat-form .composer-actions .btn,
                                                #chat-form .composer-actions .btn.btn-primary,
                                                #chat-form .composer-actions label.btn {
                                                    position: static !important;
                                                    margin: 0 !important;
                                                    display: inline-flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    min-width: 42px;
                                                    height: 42px;
                                                    padding: 0 0.85rem;
                                                }

                                                #chat-form #send-message-btn {
                                                    min-width: 48px;
                                                }

                                                @media (max-width: 767.98px) {
                                                    #chat-form .composer-main {
                                                        flex-wrap: wrap;
                                                    }

                                                    #chat-form .composer-actions {
                                                        width: 100%;
                                                    }
                                                }
                                            </style>
                                            <form id="chat-form">
                                                <div class="composer-main">
                                                    <input type="text" class="form-control flex-fill"
                                                        id="chat-message-input" placeholder="Type a message">
                                                    <div class="composer-actions">
                                                        <label for="chat-file-input"
                                                            class="btn btn-light border"
                                                            title="Attach file">
                                                            <i class="fas fa-paperclip"></i>
                                                        </label>
                                                        <input type="file" id="chat-file-input" class="d-none"
                                                            accept="image/*,audio/*,video/*,application/pdf">
                                                        <button type="button" class="btn btn-light border"
                                                            id="start-recording" title="Record audio">
                                                            <i class="fas fa-microphone"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-warning d-none"
                                                            id="stop-recording" title="Stop recording">
                                                            <i class="fas fa-stop"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger d-none"
                                                            id="cancel-upload" title="Remove attachment">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                        <button class="btn btn-primary" id="send-message-btn">
                                                            <i class="far fa-paper-plane"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <small class="text-muted d-none mt-2 d-block" id="attachment-status"></small>
                                                <audio class="w-100 mt-2 d-none" id="recording-preview" controls></audio>
                                                <span class="text-danger d-block mt-2" id="msg-error"></span>
                                            </form>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <div id="noChatWall"
                                class="card col-xs-12 col-sm-12 col-md-9 col-lg-9 d-flex justify-content-center align-items-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div><i data-feather="message-square"></i>
                                        <span class="px-2"> Select a chat from the tabs aside.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    @push('scripts')
        @include('dashboard.chats.script')
    @endpush
@endsection
