@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ $channel->name }}
                </div>

                <div class="card-body">
                @if($channel->editable())
                    <form id="update-channel-form" action="{{ route('channels.update', $channel->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                @endif

                    <div class="form-group row justify-content-center">
                        <div class="channel-avatar">
                            @if($channel->editable())
                                <div onclick="document.querySelector('#image').click()" class="channel-avatar-overlay">
                                </div>
                            @endif
                            <img src="{{ $channel->image() }}" alt="">
                        </div>
                    </div>

                    <div class="form-group">
                        <h4 class="text-center">
                            {{ $channel->name }}
                        </h4>
                        <p class="text-center">
                            {{ $channel->description }}
                        </p>
                    </div>

                    @if($channel->editable())
                        <input onchange="document.querySelector('#update-channel-form').submit()" style="display: none;" id="image" type="file" name="image">

                        <div class="form-group">
                            <label for="name" class="form-control-label">
                                Name
                            </label>
                            <input id="name" name="name" value="{{$channel->name}}"type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-control-label">
                                Description
                            </label>
                            <textarea name="description" id="description" cols="3" rows="3" class="form-control">
                                {{ $channel->description }}
                            </textarea>
                        </div>

                        @if($errors->any())
                            <ul class="list-group mb-5">
                                @foreach($errors->all() as $error)
                                    <li class="text-danger list-group-item">
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <button type="submit" class="btn btn-info">
                            Update Channel
                        </button>
                    @endif

                @if($channel->editable())
                    </form>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
