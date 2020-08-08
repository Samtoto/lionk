@extends('layouts.app')

@section('content')

<div id="app">
    <edit-blog :blog="{{ $blog->toJson() }}"></edit-blog>
</div>
<script type="application/javascript">
    // Another way passing data from Laravel to Vue
    // function unescapeHtml(text) {
    //     var map = {
    //         '&amp;': '&',
    //         '&lt;': '<',
    //         '&gt;': '>',
    //         '&quot;': '"',
    //         '&#039;': "'",
    //     };

    //     return text.replace(/&(amp|lt|gt|quot|#039);/g, function(m) {
    //         return map[m];
    //     });
    // }

    // window.blog = {
    //     id: '{{$blog->id}}',
    //     title: unescapeHtml('{{ $blog->title }}'),
    //     content: unescapeHtml('{{ $blog->content }}'),
    //     user_id: '{{$blog->user_id}}',
    //     community_id: '{{$blog->community_id}}',
    //     img_path: '{{$blog->img_path}}'
    // }
</script>

@endsection