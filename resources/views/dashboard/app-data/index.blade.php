@extends('layouts.app')
@section('title', 'Admin | App Data')
@section('content')
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <style>
            .colors,
            .short-info {
                display: flex;
                align-items: center;
            }

            .colors i,
            .short-info i {
                color: #088178;
                margin-right: 10px;
                font-size: 20px;
                cursor: pointer;
            }

            .ql-editor strong {
                font-weight: 700;
            }

            .ql-editor em {
                font-style: italic;
            }
        </style>
    @endpush
    <div class="loader">
    </div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('layouts.navbar')
            <div class="main-content">
                <section class="section">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>App Data </h4>
                                    </div>
                                    <form enctype="multipart/form-data" method="post"
                                        action="{{ route('app-data.store') }}">
                                        @csrf

                                        <div class="card-body">
                                            @if (session('success'))
                                                {{-- <div class="alert alert-success">
                                                    {{ session('success') }}
                                                </div> --}}
                                                <x-alert type="success">{{ session('success') }}</x-alert>
                                            @endif

                                            @if (session('failed'))
                                                <x-alert type="danger">{{ session('failed') }}</x-alert>
                                            @endif


                                            <div class="row">

                                                <div class="form-group col-6 ">
                                                    <label>Info Dialog Bottom Message</label>
                                                    <div class="input-group">
                                                        <input type="text" name="info_dialog_bottom_text"
                                                            class="form-control"
                                                            value="{{ old('info_dialog_bottom_text', $appData->info_dialog_bottom_text ?? '') }}">
                                                    </div>
                                                    @error('info_dialog_bottom_text')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-6 ">
                                                    <label>TM Number</label>
                                                    <div class="input-group">
                                                        <input type="text" name="tm_no" class="form-control"
                                                            value="{{ old('tm_no', $appData->tm_no ?? '') }}">
                                                    </div>
                                                    @error('tm_no')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-6 ">
                                                    <label>ARN Number</label>
                                                    <div class="input-group">
                                                        <input type="text" name="arn_no" class="form-control"
                                                            value="{{ old('arn_no', $appData->arn_no ?? '') }}">
                                                    </div>
                                                    @error('arn_no')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-6 ">
                                                    <label>Provisional ID</label>
                                                    <div class="input-group">
                                                        <input type="text" name="provisoinal_id" class="form-control"
                                                            value="{{ old('provisoinal_id', $appData->provisoinal_id ?? '') }}">
                                                    </div>
                                                    @error('provisoinal_id')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-6 ">
                                                    <label>Whatsapp Group Join Link</label>
                                                    <div class="input-group">
                                                        <input type="text" name="whatsapp_group_join_link"
                                                            class="form-control"
                                                            value="{{ old('whatsapp_group_join_link', $appData->whatsapp_group_join_link ?? '') }}">
                                                    </div>
                                                    @error('whatsapp_group_join_link')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-6">
                                                    <label>Slider Image App
                                                        <a href="{{ route('app-data.HomePageImgDelete') }}" class="btn btn-danger">Delete</a>
                                                    </label>
                                                    <div class="input-group">
                                                        <img class="img-fluid"
                                                            src="{{ '/public' . $appData->homepage_image_url ?? '' }}"
                                                            style="height: 50px;">
                                                        <input accept="image/*" type="file" name="homepage_image_url"
                                                            class="form-control "
                                                            value="{{ old('homepage_image_url', $appData->homepage_image_url ?? '') }}">
                                                    </div>
                                                    @error('homepage_image_url')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-6">
                                                    <label>HomePage Slider URL</label>
                                                    <div class="input-group">
                                                        <input type="text" name="slider_url" class="form-control "
                                                            value="{{ old('slider_url', $appData->slider_url ?? '') }}">
                                                    </div>
                                                    @error('slider_url')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>


                                                <div class="form-group col-6">
                                                    <label>Version</label>
                                                    <div class="input-group">
                                                        <input type="number" min="1" name="version"
                                                            class="form-control "
                                                            value="{{ old('version', $appData->version ?? '') }}">
                                                    </div>
                                                    @error('version')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-12">
                                                    <label>Home Message</label>
                                                    <div class="input-group">
                                                        <input type="text" name="home_message" class="form-control "
                                                            value="{{ old('home_message', $appData->home_message ?? '') }}">
                                                    </div>
                                                    @error('home_message')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6 ">
                                                    <label>Support Number</label>
                                                    <div class="input-group">
                                                        <input type="text" name="support_number" class="form-control"
                                                            value="{{ old('support_number', $appData->support_number ?? '') }}">
                                                    </div>
                                                    @error('support_number')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-6 ">
                                                    <label>Support Time</label>
                                                    <div class="input-group">
                                                        <input type="text" name="support_time" class="form-control"
                                                            value="{{ old('support_time', $appData->support_time ?? '') }}">
                                                    </div>
                                                    @error('support_time')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label>Custom Message 1 (Homepage)</label>
                                                    <div class="input-group">
                                                        <input type="hidden" name="custom_message_1_homepage_1st"
                                                            id="custom-1"
                                                            value="{{ old('custom_message_1_homepage_1st', $appData->custom_message_1_homepage_1st ?? '') }}" />
                                                        <div id="custom-1-div" class="col-12">
                                                            {!! $appData->custom_message_1_homepage_1st ?? '' !!}
                                                        </div>

                                                    </div>
                                                    @error('custom_message_1_homepage_1st')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Custom Message 2 (Homagepage -Note)</label>
                                                    <div class="input-group">
                                                        <input type="hidden" name="custom_message_2_homepage_2nd_note"
                                                            id="custom-2"
                                                            value="{{ old('custom_message_2_homepage_2nd_note', $appData->custom_message_2_homepage_2nd_note ?? '') }}" />
                                                        <div id="custom-2-div" class="col-12">
                                                            {!! $appData->custom_message_2_homepage_2nd_note ?? '' !!}

                                                        </div>
                                                    </div>
                                                    @error('custom_message_2_homepage_2nd_note')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label>Custom Message 3 (Help Page-1st)</label>
                                                    <div class="input-group">
                                                        <input type="hidden" name="custom_message_3_help_page_1"
                                                            id="custom-3"
                                                            value="{{ old('custom_message_3_help_page_1', $appData->custom_message_3_help_page_1 ?? '') }}" />
                                                        <div id="custom-3-div" class="col-12">
                                                            {!! $appData->custom_message_3_help_page_1 ?? '' !!}
                                                        </div>

                                                    </div>
                                                    @error('custom_message_3_help_page_1')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Custom Message 4 (HelpPage-2nd)</label>
                                                    <div class="input-group">
                                                        <input type="hidden" name="custom_message_4_help_page_2nd"
                                                            id="custom-4"
                                                            value="{{ old('custom_message_4_help_page_2nd', $appData->custom_message_4_help_page_2nd ?? '') }}" />
                                                        <div id="custom-4-div" class="col-12">
                                                            {!! $appData->custom_message_4_help_page_2nd ?? '' !!}

                                                        </div>
                                                    </div>
                                                    @error('custom_message_4_help_page_2nd')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Custom Message 5 (Terms)</label>
                                                    <div class="input-group">
                                                        <input type="hidden" name="custom_message_5_terms"
                                                            id="custom-5"
                                                            value="{{ old('custom_message_5_terms', $appData->custom_message_5_terms ?? '') }}" />
                                                        <div id="custom-5-div" class="col-12">
                                                            {!! $appData->custom_message_5_terms ?? '' !!}

                                                        </div>
                                                    </div>
                                                    @error('custom_message_5_terms')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Info Dialog Message (Home)</label>
                                                    <div class="input-group">
                                                        <input type="text" name="info_dialog_message"
                                                            class="form-control "
                                                            value="{{ old('info_dialog_message', $appData->info_dialog_message ?? '') }}">
                                                    </div>
                                                    @error('info_dialog_message')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                {{-- new --}}
                                                <div class="form-group col-6">
                                                    <label>Important Dialog Heading</label>
                                                    <div class="input-group">
                                                        <input type="text" name="info_dialog_1_message"
                                                            class="form-control "
                                                            value="{{ old('info_dialog_1_message', $appData->info_dialog_1_message ?? '') }}">
                                                    </div>
                                                    @error('info_dialog_1_message')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Important Dialog Description</label>
                                                    <div class="input-group">
                                                        <input type="text" name="info_dialog_1_bottom_text"
                                                            class="form-control "
                                                            value="{{ old('info_dialog_1_bottom_text', $appData->info_dialog_1_bottom_text ?? '') }}">
                                                    </div>
                                                    @error('info_dialog_1_bottom_text')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Important Dialog Link</label>
                                                    <div class="input-group">
                                                        <input type="text" name="info_dialog_1_url"
                                                            class="form-control "
                                                            value="{{ old('info_dialog_1_url', $appData->info_dialog_1_url ?? '') }}">
                                                    </div>
                                                    @error('info_dialog_1_url')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Important Dialog</label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="info_dialog_1_message_show_hide" value="1" class="selectgroup-input-radio" @if (($appData->info_dialog_1_message_show_hide ?? false) == 1 || (old('info_dialog_1_message_show_hide') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="info_dialog_1_message_show_hide" value="0" class="selectgroup-input-radio" @if (($appData->info_dialog_1_message_show_hide ?? true) == 0 || (old('info_dialog_1_message_show_hide') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('info_dialog_1_message_show_hide')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                {{-- new end --}}

                                                <div class="form-group col-6">
                                                    <label>Faceook Url</label>
                                                    <div class="input-group">
                                                        <input type="text" name="facebook_url" class="form-control "
                                                            value="{{ old('facebook_url', $appData->facebook_url ?? '') }}">
                                                    </div>
                                                    @error('facebook_url')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-6">
                                                    <label>Instagram url</label>
                                                    <div class="input-group">
                                                        <input type="text" name="instagram_url" class="form-control "
                                                            value="{{ old('instagram_url', $appData->instagram_url ?? '') }}">
                                                    </div>
                                                    @error('instagram_url')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                @if (env('BET_LOSE_GIVE_MONEY', false))
                                                    <div class="form-group col-6">
                                                        <label>Bet Lose Bonus (%)</label>
                                                        <div class="input-group">
                                                            <input type="number" name="invite_bonus"
                                                                class="form-control "
                                                                value="{{ old('invite_bonus', $appData->invite_bonus ?? '') }}">
                                                        </div>
                                                        @error('invite_bonus')
                                                            <div class="alert alert-danger mt-2">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                @else
                                                    <div class="form-group col-6">
                                                        <label>Invite Bonus</label>
                                                        <div class="input-group">
                                                            <input type="number" name="invite_bonus"
                                                                class="form-control "
                                                                value="{{ old('invite_bonus', $appData->invite_bonus ?? '') }}">
                                                        </div>
                                                        @error('invite_bonus')
                                                            <div class="alert alert-danger mt-2">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                @endif



                                                <div class="form-group col-6">
                                                    <label>Welcome Bonus</label>
                                                    <div class="input-group">
                                                        <input type="number" name="welcome_bonus" class="form-control "
                                                            value="{{ old('welcome_bonus', $appData->welcome_bonus ?? '') }}">
                                                    </div>
                                                    @error('welcome_bonus')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-6">
                                                    <label>Self Recharge Bonus Bonus (%), Enter 0 for NONE</label>
                                                    <div class="input-group">
                                                        <input type="number" name="self_recharge_bonus"
                                                            class="form-control "
                                                            value="{{ old('self_recharge_bonus', $appData->self_recharge_bonus ?? '') }}" placeholder="Enter Amount in %">
                                                    </div>
                                                    @error('self_recharge_bonus')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label>Admin UPI</label>
                                                    <div class="input-group">
                                                        <input type="text" name="admin_upi" class="form-control"
                                                            value="{{ old('admin_upi', $appData->admin_upi ?? '') }}">
                                                    </div>
                                                    @error('admin_upi')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-6">
                                                    <label class="form-label">Telegram Enable </label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="telegram_enable" value="1"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->telegram_enable ?? false) == 1 || (old('telegram_enable') ?? false) == 1) checked @endif>

                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="telegram_enable" value="0"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->telegram_enable ?? true) == 0 || (old('telegram_enable') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('telegram_enable')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label>Telegram Link</label>
                                                    <div class="input-group">
                                                        <input type="url" name="telegram_link" class="form-control"
                                                            value="{{ old('telegram_link', $appData->telegram_link ?? '') }}">
                                                    </div>
                                                    @error('telegram_link')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-6">
                                                    <label class="form-label">WhatsApp Enable </label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="whatsapp_enable" value="1"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->whatsapp_enable ?? false) == 1 || (old('whatsapp_enable') ?? false) == 1) checked @endif.>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="whatsapp_enable" value="0"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->whatsapp_enable ?? true) == 0 || (old('whatsapp_enable') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('whatsapp_enable')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6 ">
                                                    <label>WhatsApp Number</label>
                                                    <div class="input-group">
                                                        <input type="text" name="whatsapp_number" class="form-control"
                                                            value="{{ old('whatsapp_number', $appData->whatsapp_number ?? '') }}">
                                                    </div>
                                                </div>
                                                @error('whatsapp_number')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <div class="form-group col-6 ">
                                                    <label for="app_update_link">App Update Link</label>
                                                    <input type="text" id="app_update_link" name="app_update_link"
                                                        class="form-control"
                                                        value="{{ old('app_update_link', $appData->app_update_link ?? '') }}">
                                                    @error('app_update_link')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label class="form-label">Invite System Enable </label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="invite_system_enable"
                                                                value="1" class="selectgroup-input-radio"
                                                                @if (($appData->invite_system_enable ?? false) == 1 || (old('invite_system_enable') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="invite_system_enable"
                                                                value="0" class="selectgroup-input-radio"
                                                                @if (($appData->invite_system_enable ?? true) == 0 || (old('invite_system_enable') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('invite_system_enable')
                                                        <div class="alert alert-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="min_withdraw">Minimum Withdraw</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">&#x20B9;</span>
                                                        </div>
                                                        <input type="number" id="min_withdraw" name="min_withdraw"
                                                            class="form-control" min="1"
                                                            value="{{ old('min_withdraw', $appData->min_withdraw ?? '') }}">
                                                    </div>
                                                    @error('min_withdraw')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label>Minimum Deposit</label>
                                                    <div class="input-group">
                                                        <input type="number" min="1" name="min_deposit"
                                                            class="form-control "
                                                            value="{{ old('min_deposit', $appData->min_deposit ?? '') }}">
                                                    </div>
                                                    @error('min_deposit')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Maximum Deposit</label>
                                                    <div class="input-group">
                                                        <input type="number" min="1" name="max_deposit"
                                                            class="form-control "
                                                            value="{{ old('max_deposit', $appData->max_deposit ?? '') }}">
                                                    </div>
                                                    @error('max_deposit')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>UPI Image</label>
                                                    <div class="input-group">
                                                        <img class="img-fluid" src="{{ $appData->upi_image ?? '' }}"
                                                            style="height: 150px;">
                                                        <input accept="image/*" type="file" name="upi_image"
                                                            class="form-control "
                                                            value="{{ old('upi_image', $appData->upi_image ?? '') }}">
                                                    </div>
                                                    @error('upi_image')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label>Minimum Bid Amount</label>
                                                    <div class="input-group">
                                                        <input type="number" min="1" name="min_bid_amount"
                                                            class="form-control "
                                                            value="{{ old('min_bid_amount', $appData->min_bid_amount ?? '') }}">
                                                    </div>
                                                    @error('min_bid_amount')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Max Bid Amount</label>
                                                    <div class="input-group">
                                                        <input type="number" min="1" name="max_bid_amount"
                                                            class="form-control "
                                                            value="{{ old('max_bid_amount', $appData->max_bid_amount ?? '') }}">
                                                    </div>
                                                    @error('max_bid_amount')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label for="auto_result_api">Auto Result API</label>
                                                    <input type="url" id="auto_result_api" name="auto_result_api"
                                                        class="form-control"
                                                        value="{{ old('auto_result_api', $appData->auto_result_api ?? '') }}">
                                                    @error('auto_result_api')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="withdraw_open_time">Withdraw Open Time</label>
                                                    <input type="time" id="withdraw_open_time"
                                                        name="withdraw_open_time" class="form-control"
                                                        value="{{ old('withdraw_open_time', date('H:i', strtotime($appData->withdraw_open_time ?? ''))) }}">
                                                    @error('withdraw_open_time')
                                                        <div class="alert alert-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="withdraw_close_time">Withdraw Close Time</label>
                                                    <input type="time" id="withdraw_close_time"
                                                        name="withdraw_close_time" class="form-control"
                                                        value="{{ old('withdraw_close_time', date('H:i', strtotime($appData->withdraw_close_time ?? ''))) }}">
                                                    @error('withdraw_close_time')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="payment_method">Payment Method</label>
                                                    <select id="payment_method" name="payment_method"
                                                        class="form-control">
                                                        <option value="auto"
                                                            {{ old('payment_method') == 'auto' ? ' selected' : '' }}
                                                            {{ $appData->payment_method == 'auto' ? ' selected' : '' }}>
                                                            Auto</option>
                                                        <option value="manual"
                                                            {{ old('payment_method') == 'manual' ? ' selected' : '' }}{{ $appData->payment_method == 'manual' ? ' selected' : '' }}>
                                                            Manual</option>
                                                        <option value="direct_upi"
                                                            {{ old('payment_method') == 'direct_upi' ? ' selected' : '' }}{{ $appData->payment_method == 'direct_upi' ? ' selected' : '' }}>
                                                            Dicret UPI</option>
                                                        <option value="ibr_pay"
                                                            {{ old('payment_method') == 'ibr_pay' ? ' selected' : '' }}{{ $appData->payment_method == 'ibr_pay' ? ' selected' : '' }}>
                                                            IBR Pay</option>
                                                        <option value="upi_money"
                                                            {{ old('payment_method') == 'upi_money' ? ' selected' : '' }}{{ $appData->payment_method == 'upi_money' ? ' selected' : '' }}>
                                                            UPI Money</option>
                                                        <option value="i_online_pay"
                                                            {{ old('payment_method') == 'i_online_pay' ? ' selected' : '' }}{{ $appData->payment_method == 'i_online_pay' ? ' selected' : '' }}>
                                                            I Online Pay</option>
                                                        <option value="payment_karo"
                                                            {{ old('payment_method') == 'payment_karo' ? ' selected' : '' }}{{ $appData->payment_method == 'payment_karo' ? ' selected' : '' }}>
                                                            Payment Karo</option>
                                                        <option value="planet_c"
                                                            {{ old('payment_method') == 'planet_c' ? ' selected' : '' }}{{ $appData->payment_method == 'planet_c' ? ' selected' : '' }}>
                                                            Planet C</option>
                                                        <option value="sonic_pe"
                                                            {{ old('payment_method') == 'sonic_pe' ? ' selected' : '' }}{{ $appData->payment_method == 'sonic_pe' ? ' selected' : '' }}>
                                                            Sonic Pe</option>
                                                        <option value="run_paisa"
                                                            {{ old('payment_method') == 'run_paisa' ? ' selected' : '' }}{{ $appData->payment_method == 'run_paisa' ? ' selected' : '' }}>
                                                            Run Paisa</option>
                                                        <option value="pay_from_upi"
                                                            {{ old('payment_method') == 'pay_from_upi' ? ' selected' : '' }}{{ $appData->payment_method == 'pay_from_upi' ? ' selected' : '' }}>
                                                            Pay From UPI</option>
                                                        <option value="rudrax_pay"
                                                            {{ old('payment_method') == 'rudrax_pay' ? ' selected' : '' }}{{ $appData->payment_method == 'rudrax_pay' ? ' selected' : '' }}>
                                                            Rudrax Pay</option>
                                                        <option value="pay_o_matix"
                                                            {{ old('payment_method') == 'pay_o_matix' ? ' selected' : '' }}{{ $appData->payment_method == 'pay_o_matix' ? ' selected' : '' }}>
                                                            Pay O Matix</option>
                                                    </select>
                                                    @error('payment_method')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="withdrawal_method">PayOut Method</label>
                                                    <select id="withdrawal_method" name="withdrawal_method"
                                                        class="form-control">
                                                        <option value="manual"
                                                            {{ old('withdrawal_method') == 'manual' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'manual' ? ' selected' : '' }}>
                                                            Manual</option>
                                                        <option value="ibr_pay"
                                                            {{ old('withdrawal_method') == 'ibr_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'ibr_pay' ? ' selected' : '' }}>
                                                            IBR Pay</option>
                                                        <option value="upi_money"
                                                            {{ old('withdrawal_method') == 'upi_money' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'upi_money' ? ' selected' : '' }}>
                                                            UPI Money</option>
                                                        <option value="i_online_pay"
                                                            {{ old('withdrawal_method') == 'i_online_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'i_online_pay' ? ' selected' : '' }}>
                                                            I Online Pay</option>
                                                        <option value="cub_pay"
                                                            {{ old('withdrawal_method') == 'cub_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'cub_pay' ? ' selected' : '' }}>
                                                            Cub Pay</option>
                                                        <option value="planet_c"
                                                            {{ old('withdrawal_method') == 'planet_c' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'planet_c' ? ' selected' : '' }}>
                                                            Planet C</option>
                                                        <option value="sonic_pe"
                                                            {{ old('withdrawal_method') == 'sonic_pe' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'sonic_pe' ? ' selected' : '' }}>
                                                            Sonic Pe</option>
                                                        <option value="run_paisa"
                                                            {{ old('withdrawal_method') == 'run_paisa' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'run_paisa' ? ' selected' : '' }}>
                                                            Run Paisa</option>
                                                        <option value="click_pay"
                                                            {{ old('withdrawal_method') == 'click_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'click_pay' ? ' selected' : '' }}>
                                                            Click Pay</option>
                                                        <option value="vagon_pay"
                                                            {{ old('withdrawal_method') == 'vagon_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'vagon_pay' ? ' selected' : '' }}>
                                                            Vagon Pay</option>
                                                        <option value="rudrax_pay"
                                                            {{ old('withdrawal_method') == 'rudrax_pay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'rudrax_pay' ? ' selected' : '' }}>
                                                            Rudrax Pay</option>
                                                        <option value="payinfintech" {{ old('withdrawal_method') == 'payinfintech' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'payinfintech' ? ' selected' : '' }}>Payin Fintech</option>
                                                        <option value="universepay" {{ old('withdrawal_method') == 'universepay' ? ' selected' : '' }}{{ $appData->withdrawal_method == 'universepay' ? ' selected' : '' }}>UniversePay</option>
                                                    </select>
                                                    @error('withdrawal_method')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="auto_result_api">UPI Gateway KEY</label>
                                                    <input type="text" id="upi_gateway_key" name="upi_gateway_key"
                                                        class="form-control"
                                                        value="{{ old('upi_gateway_key', $appData->upi_gateway_key ?? '') }}">
                                                    @error('upi_gateway_key')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>PayFromUPI API Key</label>
                                                    <div class="input-group">
                                                        <input type="text" name="payfromupi_api_key"
                                                            class="form-control "
                                                            value="{{ old('payfromupi_api_key', $appData->payfromupi_api_key ?? '') }}">
                                                    </div>
                                                    @error('payfromupi_api_key')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-6 ">
                                                    <label for="sms_api_key">SMS API Key</label>
                                                    <input type="text" id="sms_api_key" name="sms_api_key"
                                                        class="form-control"
                                                        value="{{ old('sms_api_key', $appData->sms_api_key ?? '') }}">
                                                    @error('sms_api_key')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="fcm_key">Fcm Key</label>
                                                    <input type="text" id="fcm_key" name="fcm_key"
                                                        class="form-control"
                                                        value="{{ old('fcm_key', $appData->fcm_key ?? '') }}">
                                                    @error('fcm_key')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="form-label"> Withdraw Enable</label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="bank_withdraw_enable"
                                                                value="1" class="selectgroup-input-radio"
                                                                @if (($appData->bank_withdraw_enable ?? false) == 1 || (old('bank_withdraw_enable') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="bank_withdraw_enable"
                                                                value="0" class="selectgroup-input-radio"
                                                                @if (($appData->bank_withdraw_enable ?? true) == 0 || (old('bank_withdraw_enable') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('bank_withdraw_enable')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label class="form-label"> UPI Withdraw Enable</label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="upi_withdraw_enable"
                                                                value="1" class="selectgroup-input-radio"
                                                                @if (($appData->upi_withdraw_enable ?? false) == 1 || (old('upi_withdraw_enable') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="upi_withdraw_enable"
                                                                value="0" class="selectgroup-input-radio"
                                                                @if (($appData->upi_withdraw_enable ?? true) == 0 || (old('upi_withdraw_enable') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('upi_withdraw_enable')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="form-label"> Enable Desawar</label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="enable_desawar" value="1"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->enable_desawar ?? false) == 1 || (old('enable_desawar') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="enable_desawar" value="0"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->enable_desawar ?? true) == 0 || (old('enable_desawar') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('enable_desawar')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Enable Desawar Only</label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="enable_desawar_only"
                                                                value="1" class="selectgroup-input-radio"
                                                                @if (($appData->enable_desawar_only ?? false) == 1 || (old('enable_desawar_only') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="enable_desawar_only"
                                                                value="0" class="selectgroup-input-radio"
                                                                @if (($appData->enable_desawar_only ?? true) == 0 || (old('enable_desawar_only') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('enable_desawar_only')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Maintainance Mode Enable?</label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="maintain_mode" value="1"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->maintain_mode ?? false) == 1 || (old('maintain_mode') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="maintain_mode" value="0"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->maintain_mode ?? true) == 0 || (old('maintain_mode') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('maintain_mode')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>


                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Play Store Enable?</label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="play_store" value="1"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->play_store ?? false) == 1 || (old('play_store') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="play_store" value="0"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->play_store ?? true) == 0 || (old('play_store') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('play_store')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>


                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Show Only Resutls?</label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="show_results_only" value="1"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->show_results_only ?? false) == 1 || (old('show_results_only') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="show_results_only" value="0"
                                                                class="selectgroup-input-radio"
                                                                @if (($appData->show_results_only ?? true) == 0 || (old('show_results_only') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('play_store')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Holiday</label>
                                                    <div class="selectgroup w-100">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="holiday" value="1" class="selectgroup-input-radio" @if (($appData->holiday ?? false) == 1 || (old('holiday') ?? false) == 1) checked @endif>
                                                            <span class="selectgroup-button">Enable </span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="holiday" value="0" class="selectgroup-input-radio" @if (($appData->holiday ?? true) == 0 || (old('holiday') ?? true) == 0) checked @endif>
                                                            <span class="selectgroup-button">Disable</span>
                                                        </label>
                                                    </div>
                                                    @error('holiday')
                                                        <div class="alert alert-danger mt-2">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>


                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-outline-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        var quill1Result = new Quill('#custom-1-div', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
                    ['blockquote', 'code-block'],

                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }], // custom button values
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }], // superscript/subscript
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }], // outdent/indent
                    [{
                        'direction': 'rtl'
                    }], // text direction

                    [{
                        'size': ['small', false, 'large', 'huge']
                    }], // custom dropdown
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],

                    [{
                        'color': []
                    }, {
                        'background': []
                    }], // dropdown with defaults from theme
                    [{
                        'font': []
                    }],
                    [{
                        'align': []
                    }],

                    ['clean'] // remove formatting button
                ]
            },
            theme: 'snow'
        });

        var quill2Result = new Quill('#custom-2-div', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
                    ['blockquote', 'code-block'],

                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }], // custom button values
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }], // superscript/subscript
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }], // outdent/indent
                    [{
                        'direction': 'rtl'
                    }], // text direction

                    [{
                        'size': ['small', false, 'large', 'huge']
                    }], // custom dropdown
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],

                    [{
                        'color': []
                    }, {
                        'background': []
                    }], // dropdown with defaults from theme
                    [{
                        'font': []
                    }],
                    [{
                        'align': []
                    }],

                    ['clean'] // remove formatting button
                ]
            },
            theme: 'snow'
        });

        var quill3Result = new Quill('#custom-3-div', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
                    ['blockquote', 'code-block'],

                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }], // custom button values
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }], // superscript/subscript
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }], // outdent/indent
                    [{
                        'direction': 'rtl'
                    }], // text direction

                    [{
                        'size': ['small', false, 'large', 'huge']
                    }], // custom dropdown
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],

                    [{
                        'color': []
                    }, {
                        'background': []
                    }], // dropdown with defaults from theme
                    [{
                        'font': []
                    }],
                    [{
                        'align': []
                    }],

                    ['clean'] // remove formatting button
                ]
            },
            theme: 'snow'
        });

        var quill4Result = new Quill('#custom-4-div', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
                    ['blockquote', 'code-block'],

                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }], // custom button values
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }], // superscript/subscript
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }], // outdent/indent
                    [{
                        'direction': 'rtl'
                    }], // text direction

                    [{
                        'size': ['small', false, 'large', 'huge']
                    }], // custom dropdown
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],

                    [{
                        'color': []
                    }, {
                        'background': []
                    }], // dropdown with defaults from theme
                    [{
                        'font': []
                    }],
                    [{
                        'align': []
                    }],

                    ['clean'] // remove formatting button
                ]
            },
            theme: 'snow'
        });

        var quill5Result = new Quill('#custom-5-div', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
                    ['blockquote', 'code-block'],

                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }], // custom button values
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }], // superscript/subscript
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }], // outdent/indent
                    [{
                        'direction': 'rtl'
                    }], // text direction

                    [{
                        'size': ['small', false, 'large', 'huge']
                    }], // custom dropdown
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],

                    [{
                        'color': []
                    }, {
                        'background': []
                    }], // dropdown with defaults from theme
                    [{
                        'font': []
                    }],
                    [{
                        'align': []
                    }],

                    ['clean'] // remove formatting button
                ]
            },
            theme: 'snow'
        });

        quill1Result.on('text-change', function(delta, oldDelta, source) {
            var editorHtml = quill1Result.root.innerHTML;
            $('#custom-1').val(editorHtml);
        });
        quill2Result.on('text-change', function(delta, oldDelta, source) {
            var editorHtml = quill2Result.root.innerHTML;
            $('#custom-2').val(editorHtml);
        });
        quill3Result.on('text-change', function(delta, oldDelta, source) {
            var editorHtml = quill3Result.root.innerHTML;
            $('#custom-3').val(editorHtml);
        });
        quill4Result.on('text-change', function(delta, oldDelta, source) {
            var editorHtml = quill4Result.root.innerHTML;
            $('#custom-4').val(editorHtml);
        });
        quill5Result.on('text-change', function(delta, oldDelta, source) {
            var editorHtml = quill5Result.root.innerHTML;
            $('#custom-5').val(editorHtml);
        });
    </script>
@endpush
