<div class="my-2 text-sm">
    @foreach($crumbs as $crumb)
        <a href="{{ $crumb['route'] }}" class="anchor">{{ $crumb['label'] }}</a> ~ 
    @endforeach
    <h1 class="text-2xl mb-2 inline-block">{{ $title }}</h1>
</div>