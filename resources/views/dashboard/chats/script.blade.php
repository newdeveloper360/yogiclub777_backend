<script>
    $(document).ready(function() {
        window.Echo.channel("new-chat-created").listen(".new.chat.created", (e) => {
            registerChatToWebScoket(e.chat.id)
            const chat = e.chat;
            const capitalizedType = chat.type.replace(/_/g, ' ').charAt(0).toUpperCase() + chat.type
                .replace(/_/g, ' ').slice(1);
            const html = `
        <li class="chats clearfix" data-chat="${chat.id}">
            <div class="row">
                <div class="col-8">
                    <div class="about">
                        <div class="name">
                         ${chat.user.name}
                        </div>
                        <div class="status">
                            ${capitalizedType}
                        </div>
                    </div>
                </div>
                <div class="col-4 text-right unreadMessage">
                    <span class="badge badge-danger">${1}</span>
                </div>
            </div>
        </li>`;
            // Append the new chat HTML to the chats list
            $('#chatList').prepend(html);
            bindClickEvent()
        });

        const chatForm = $("#chat-form");
        const messageInput = $("#chat-message-input");
        const fileInput = $("#chat-file-input");
        const cancelUploadButton = $("#cancel-upload");
        const startRecordingButton = $("#start-recording");
        const stopRecordingButton = $("#stop-recording");
        const attachmentStatus = $("#attachment-status");
        const recordingPreview = $("#recording-preview");
        const sendMessageButton = $("#send-message-btn");

        let mediaRecorder = null;
        let recordingStream = null;
        let recordingChunks = [];
        let recordedFile = null;
        let discardRecording = false;

        function getSupportedAudioMimeType() {
            const mimeTypes = [
                'audio/webm;codecs=opus',
                'audio/webm',
                'audio/ogg;codecs=opus',
                'audio/ogg',
                'audio/mp4',
            ];

            for (let i = 0; i < mimeTypes.length; i++) {
                if (MediaRecorder.isTypeSupported(mimeTypes[i])) {
                    return mimeTypes[i];
                }
            }

            return '';
        }

        function stopRecordingStream() {
            if (recordingStream) {
                recordingStream.getTracks().forEach(function(track) {
                    track.stop();
                });
                recordingStream = null;
            }
        }

        function clearRecordingPreview() {
            const previewUrl = recordingPreview.data('preview-url');
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }
            recordingPreview.removeData('preview-url');
            recordingPreview.attr('src', '').addClass('d-none');
        }

        function setAttachmentStatus(text) {
            attachmentStatus.text(text).removeClass('d-none');
        }

        function clearAttachmentState() {
            fileInput.val('');
            recordedFile = null;
            clearRecordingPreview();
            attachmentStatus.text('').addClass('d-none');
            cancelUploadButton.addClass('d-none');
            $("#msg-error").text("");

            if (mediaRecorder && mediaRecorder.state === 'recording') {
                discardRecording = true;
                mediaRecorder.stop();
            } else {
                stopRecordingStream();
                startRecordingButton.removeClass('d-none');
                stopRecordingButton.addClass('d-none');
                fileInput.prop('disabled', false);
            }
        }

        fileInput.on('change', function() {
            const selectedFile = this.files[0];
            recordedFile = null;
            clearRecordingPreview();

            if (!selectedFile) {
                attachmentStatus.text('').addClass('d-none');
                cancelUploadButton.addClass('d-none');
                return;
            }

            setAttachmentStatus(`Attached file: ${selectedFile.name}`);
            cancelUploadButton.removeClass('d-none');
        });

        cancelUploadButton.on('click', function() {
            clearAttachmentState();
        });

        startRecordingButton.on('click', async function() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia || !window.MediaRecorder) {
                $("#msg-error").text("Audio recording is not supported in this browser.");
                return;
            }

            clearAttachmentState();
            $("#msg-error").text("");

            try {
                recordingStream = await navigator.mediaDevices.getUserMedia({
                    audio: true
                });
                recordingChunks = [];
                discardRecording = false;
                const mimeType = getSupportedAudioMimeType();
                mediaRecorder = mimeType ? new MediaRecorder(recordingStream, {
                    mimeType: mimeType
                }) : new MediaRecorder(recordingStream);

                mediaRecorder.ondataavailable = function(event) {
                    if (event.data.size > 0) {
                        recordingChunks.push(event.data);
                    }
                };

                mediaRecorder.onstop = function() {
                    const shouldDiscard = discardRecording;
                    discardRecording = false;
                    stopRecordingStream();
                    startRecordingButton.removeClass('d-none');
                    stopRecordingButton.addClass('d-none');
                    fileInput.prop('disabled', false);

                    if (shouldDiscard || recordingChunks.length === 0) {
                        recordedFile = null;
                        attachmentStatus.text('').addClass('d-none');
                        cancelUploadButton.addClass('d-none');
                        clearRecordingPreview();
                        return;
                    }

                    const recordedMimeType = mediaRecorder.mimeType || mimeType || 'audio/webm';
                    const extension = recordedMimeType.indexOf('ogg') !== -1 ? 'ogg' : recordedMimeType
                        .indexOf('wav') !== -1 ? 'wav' : recordedMimeType.indexOf('mp4') !== -1 ?
                        'mp4' : 'webm';
                    const audioBlob = new Blob(recordingChunks, {
                        type: recordedMimeType
                    });
                    const previewUrl = URL.createObjectURL(audioBlob);

                    recordedFile = new File([audioBlob], `recording-${Date.now()}.${extension}`, {
                        type: recordedMimeType
                    });

                    recordingPreview.attr('src', previewUrl).data('preview-url', previewUrl).removeClass(
                        'd-none');
                    setAttachmentStatus(`Recorded audio ready: ${recordedFile.name}`);
                    cancelUploadButton.removeClass('d-none');
                };

                fileInput.val('');
                fileInput.prop('disabled', true);
                cancelUploadButton.removeClass('d-none');
                setAttachmentStatus('Recording audio...');
                startRecordingButton.addClass('d-none');
                stopRecordingButton.removeClass('d-none');
                mediaRecorder.start();
            } catch (error) {
                stopRecordingStream();
                $("#msg-error").text("Microphone permission is required to record audio.");
            }
        });

        stopRecordingButton.on('click', function() {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
            }
        });

        var chatIds = $('#divOfChatIdsData').data('chat-ids');
        var chatExists = $('#divOfChatIdsData').data('chat-exists');
        if (chatExists) {
            chatIds = chatIds + '';
            if (!Array.isArray(chatIds)) {
                chatIds = chatIds.split(',').map(Number);
            }
            chatIds.forEach(function(chatId) {
                // Laravel Echo
                registerChatToWebScoket(chatId)
            });
        }

        function registerChatToWebScoket(chatId) {
            window.Echo.channel(`chats.${chatId}`).listen(".message.sent", (e) => {
                if (e.message.user_id !== {{ auth()->id() }}) {
                    let currentChatId = $('.chats.clearfix.active').data('chat');
                    if (e.chatId == currentChatId) {
                        $.chatCtrl('#mychatbox', {
                            text: e.message.message,
                            time: moment(new Date().toISOString()).format(
                                'D MMM YYYY, hh:mm a'),
                            position: 'chat-left',
                            type: e.message.type,
                            file_url: e.message.file_url,
                        });
                        // reading all messages
                        let csrfToken = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            type: "POST",
                            url: `/chats/read-message/${e.chatId}`,
                            data: {
                                _token: csrfToken,
                            },
                            dataType: "json",
                        });
                    } else {
                        var $chatItem = $("li.chats[data-chat='" + e.chatId + "']");
                        var unreadCounter = $chatItem.find('.unreadMessage .badge');
                        var currentUnreadCount = parseInt(unreadCounter.text()) || 0;
                        if (isNaN(currentUnreadCount)) {
                            currentUnreadCount = 0;
                        }
                        unreadCounter.text(currentUnreadCount + 1);
                        // Move the chat div to the top
                        $chatItem.prependTo($chatItem.parent());
                    }
                }
            });
        }

        // When user select click on chats list
        // This script handles the click event on chat items. It removes the 'active' class from all other chat items,
        // adds the 'active' class to the clicked chat item, retrieves the chat data using AJAX, updates the UI with the chat data,
        // empties the unread message counter for the clicked chat item, and updates the total unread chats count.
        function bindClickEvent() {
            $('.chats.clearfix').click(function() {
                $('.chats.clearfix').removeClass('active');
                // Add active class to the clicked chat item
                $(this).addClass('active');
                // Get the chatId
                var chatId = $(this).data('chat');
                $.get(`/chats/get/${chatId}`, function(data, status) {
                    const chat = data.response.chat;
                    const userName = chat.user.name
                    $("#headerUserName").text(userName)
                    $('.card-body.chat-content').empty()
                    setChatWall(chat.messages)
                    $('#noChatWall').empty();
                    $('#divOfChatWall').removeClass('d-none');
                    $("li.chats[data-chat='" + chat.id + "']")
                        .find('.unreadMessage .badge')
                        .text("");
                    $.get("/chats/unread-chats-count", function(data, status, xhr) {
                        if (xhr.status == 200) {
                            $("#unreadChatsCount").text(data.response.countUnreadChats)
                        }
                    });
                });
            });
        }

        bindClickEvent()

        function setChatWall(chats) {
            for (var i = 0; i < chats.length; i++) {
                var type = 'text';
                var isMine = chats[i].is_mine
                var position = isMine ? 'right' : 'left';
                $.chatCtrl('#mychatbox', {
                    text: (chats[i].message != undefined ? chats[i].message : ''),
                    type: chats[i].type,
                    time: moment(chats[i].created_at).format('D MMM YYYY, hh:mm a'),
                    position: 'chat-' + position,
                    file_url: chats[i].file_url,
                });
            }
            scrollToBottom();
        }

        // Chat control
        $.chatCtrl = function(element, chat) {
            var chat = $.extend({
                position: 'chat-right',
                text: '',
                time: '',
                type: 'text',
                timeout: 1,
                file_url: '',
                onShow: function() {}
            }, chat);
            console.log(chat);
            console.log(chat.file_url);
            var target = $(element);
            var element = '<div class="chat-item ' + chat.position + '">' +
                '<div class="chat-details">';
            if (chat.type === 'text') {
                element += '<div class="chat-text">' + chat.text + '</div>';
            } else if (chat.type === 'image') {
                element += "<img width='250px' height='250px' src='" + chat.file_url + "'>";
            } else if (chat.type === 'pdf') {
                var lastSlashIndex = chat.file_url.lastIndexOf('/');
                var filename = chat.file_url.substring(lastSlashIndex + 1);
                element += "<div><a class='chat-text' href='" + chat.file_url +
                    "' target='_blank'>" + filename + "</a></div>";
            } else if (chat.type === 'audio') {
                element += "<audio controls src='" + chat.file_url + "'></audio>";
            } else if (chat.type === 'video') {
                element += "<video width='320' height='240' controls><source src='" + chat.file_url +
                    "' type='video/mp4'></video>";
            } else {
                element += '<div class="chat-text">' + chat.file_url + '</div>';
            }
            element += '<div class="chat-time">' + chat.time + '</div>' +
                '</div>' +
                '</div>';
            var append_element = element;

            if (chat.timeout > 0) {
                setTimeout(function() {
                    target.find('.chat-content').append($(append_element).fadeIn());
                    scrollToBottom();
                }, chat.timeout);
            } else {
                target.find('.chat-content').append($(append_element).fadeIn());
                scrollToBottom();
            }
            chat.onShow.call(this, append_element);
        }

        // start message submit
        chatForm.submit(function(event) {
            event.preventDefault();
            $('.chats.clearfix.active').prependTo($('.chats.clearfix').parent());
            var formInputText = messageInput;
            var formFileInput = recordedFile || fileInput[0].files[0];

            if (formInputText.val().trim().length == 0 && !formFileInput) {
                $("#msg-error").text("Message or file is required.");
                return;
            }
            if (formInputText.val().trim().length > 255) {
                $("#msg-error").text("Message should be less than 255.");
                return;
            }
            $("#msg-error").text("");
            let currentChatId = $('.chats.clearfix.active').data('chat');
            let message = formInputText.val();
            let file = formFileInput;
            sendMessage(currentChatId, message, file);
        });

        function sendMessage(chatId, message, file) {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('message', String(message.trim()) || 'dummy-text');

            if (file) {
                formData.append('file', file);
            }

            sendMessageButton.prop('disabled', true);
            $.ajax({
                type: "POST",
                url: `/chats/send-message/${chatId}`,
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(res) {
                    let message = res.response.message;
                    $.chatCtrl('#mychatbox', {
                        text: message.message,
                        time: moment(new Date().toISOString()).format(
                            'D MMM YYYY, hh:mm a'),
                        type: message.type,
                        file_url: message.file_url,
                    });
                    messageInput.val('');
                    clearAttachmentState();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $("#msg-error").text("Unable to send the message right now.");
                },
                complete: function() {
                    sendMessageButton.prop('disabled', false);
                }
            });
        }

        // ends message submit

        // styling and scrolling etc
        if ($("#chat-scroll").length) {
            $("#chat-scroll").animate({
                height: 450
            }, 'slow', function() {
                $(this).niceScroll();
            });
        }
        if ($(".chat-content").length) {
            $(".chat-content").niceScroll({
                cursoropacitymin: 0.3,
                cursoropacitymax: 0.8,
            });
        }

        function scrollToBottom() {
            var target_height = $(".chat-content")[0].scrollHeight;
            $(".chat-content").scrollTop(target_height);
        }
    });
</script>
