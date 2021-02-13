<div class="card mb-2">
  <div class="card-header p-1">
    <h5 class="m-1 p-0">@lang('widgets.latestnews.news')<i class="fas fa-newspaper float-right"></i></h5></div>
  <div class="card-body p-1">
  @if($news->count() === 0)
    <div class="text-center text-muted">@lang('widgets.latestnews.nonewsfound')</div>
  @endif
  @foreach($news as $item)
    @if (!$loop->first) <hr> @endif
    <h6 class="mt-1 mb-1">{{ $item->subject }}</h6>
    {{ $item->body }}
    <div class="float-right">{{ $item->user->name_private }} @ {{ show_datetime($item->created_at) }}</div>
  @endforeach
  </div>
</div>
