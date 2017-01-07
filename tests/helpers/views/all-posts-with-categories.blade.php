<ul>
@foreach($categories as $link=>$category)
<li><a href="{{$link}}">{{$category->title}} ({{$category->postsCount}})</a></li>
@endforeach
</ul>
@foreach($posts as $post)
<div>
<h1>{{$post->title}}</h1>
<p>{{$post->summary}}</p>
</div>
@endforeach