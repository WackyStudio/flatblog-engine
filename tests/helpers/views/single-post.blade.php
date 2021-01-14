<p>{{$post->seo_title}}</p>
<p>{{$post->seo_description}}</p>
<p>{{$post->seo_keywords}}</p>
<p>{{$post->fb_url}}</p>
<p>{{$post->header_image}}</p>
<p>{{$post->thumbnail->getFilename()}}</p>
<p>{{$post->alt}}</p>
<h1>{{$post->title}}</h1>
{!! $post->content !!}
@if($post->relations)
@foreach($post->relations as $related)
<h1>{{$related->title}}</h1>
@endforeach
@endif