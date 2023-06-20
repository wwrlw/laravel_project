@extends('layouts.layout')
@section('content')
<div class="card" style="margin-top">
  <div class="card-body">
    <h5 class="card-title">{{$article->name}} ({{$article->date}})</h5>
    <h6 class="card-subtitle mb-2 text-muted">{{$article->shortDesc}}</h6>
    <p class="card-text">{{$article->desc}}</p>
    <a href="/article/{{$article->id}}/edit" class="btn btn-info">Редактирование</a>
    <a href="/article/{{$article->id}}/delete" class="btn btn-warning">Удаление</a>
  </div>

  <h3 class="text-center">Комментарии</h3>
  @isset($_GET['result'])
    @if($_GET['result'])
      <div class="alert alert-primary">
        Ваш комментарий ожидает модерации!
      </div>
    @endif
  @endisset

  @foreach($comments as $comment)
  <form action="/comment/{{$comment->id}}" method="post">
    @csrf
    @method('DELETE')
    <div class="card-body">
    <h5 class="card-title">{{$comment->title}} ({{$comment->created_at}})</h5>
    <p class="card-text">{{$comment->text}}</p>
    @can('update-comment', $comment)
      <a href="/comment/{{$comment->id}}/edit" class="btn btn-secondary">Редактирование</a>
      <button type="submit" class="btn btn-secondary">Удалить</button>
    @endcan
  </div>
  </form>
  @endforeach
  {{$comments->links()}}
</div>

@if($errors->any())
    <div class="alert-danger">
        <ul>
        @foreach($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
        </ul>
    </div>
@endif

<form action="/comment" method="post">
    @csrf
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Заголовок</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="title">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Текст</label>
    <input type="text" class="form-control" id="exampleInputPassword1" name="text">
  </div>
  <input type="hidden" name="id" value="{{$article->id}}">
  <button type="submit" class="btn btn-primary">Добавить новый комментарий</button>
</form>
@endsection