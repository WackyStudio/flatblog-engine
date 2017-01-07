<ul>
@foreach($categories as $link=>$singleCategory)
<li><a href="{{$link}}">{{$singleCategory->title}} ({{$singleCategory->postsCount}})</a></li>
@endforeach
</ul>
<h1>{{$category}}</h1>
@foreach($posts as $post)
<div>
<h1>{{$post->title}}</h1>
<p>{{$post->summary}}</p>
</div>
@endforeach